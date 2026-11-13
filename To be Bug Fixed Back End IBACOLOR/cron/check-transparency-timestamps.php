<?php
declare(strict_types=1);

/**
 * IBACOLOR - Automatic OpenTimestamps Checker
 * Run using Windows Task Scheduler.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Access denied.");
}

require_once __DIR__ . '/../includes/connection.php';
require_once __DIR__ . '/../includes/transparency-config.php';
require_once __DIR__ . '/../includes/transparency-ots.php';

$proofDirectory = TRANSPARENCY_STORAGE_DIRECTORY
    . DIRECTORY_SEPARATOR . 'proofs';

$logFile = TRANSPARENCY_STORAGE_DIRECTORY
    . DIRECTORY_SEPARATOR . 'ots-checker.log';

$lockFile = sys_get_temp_dir()
    . DIRECTORY_SEPARATOR . 'ibacolor-ots-checker.lock';

$python = 'C:\xampp\private\IBACOLOR\ots-venv\Scripts\python.exe';
$launcher = 'C:\xampp\private\IBACOLOR\ots_launcher.py';

function otsLog(string $message, string $logFile): void
{
    $line = '[' . gmdate('Y-m-d H:i:s') . ' UTC] ' . $message;

    echo $line . PHP_EOL;

    file_put_contents(
        $logFile,
        $line . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
}

if (
    !is_dir($proofDirectory) ||
    !is_file($python) ||
    !is_file($launcher)
) {
    exit("Required OTS files or proof directory are missing.\n");
}

// Prevent overlapping checker runs.
$lockHandle = fopen($lockFile, 'c');

if ($lockHandle === false) {
    exit("Could not create checker lock file.\n");
}

if (!flock($lockHandle, LOCK_EX | LOCK_NB)) {
    fclose($lockHandle);
    exit("Another timestamp checker is already running.\n");
}

try {

    otsLog("Timestamp checker started.", $logFile);

    $statement = $iBacolorConnection->prepare(
        "SELECT document_id, storage_key, sha256_hash,
                ots_proof_storage_key
         FROM transparency_documents
         WHERE ots_status = 'pending'
           AND ots_proof_storage_key IS NOT NULL"
    );

    $statement->execute();
    $documents = $statement->fetchAll(PDO::FETCH_ASSOC);

    otsLog("Pending documents: " . count($documents), $logFile);

    $lastCheckedUpdate = $iBacolorConnection->prepare(
        "UPDATE transparency_documents
         SET ots_last_checked_at = UTC_TIMESTAMP()
         WHERE document_id = :document_id
           AND ots_status = 'pending'"
    );

    foreach ($documents as $document) {

        $documentId = (int) $document['document_id'];

        $lastCheckedUpdate->execute([
            'document_id' => $documentId
        ]);

        $pdfPath = TRANSPARENCY_STORAGE_DIRECTORY
            . DIRECTORY_SEPARATOR . $document['storage_key'];

        $proofKey = (string) $document['ots_proof_storage_key'];

        if (
            !preg_match('/^[a-f0-9]{48}\.ots$/i', $proofKey) ||
            !is_file($pdfPath) ||
            !is_file($proofDirectory . DIRECTORY_SEPARATOR . $proofKey)
        ) {
            otsLog("Document {$documentId}: Missing or invalid files.", $logFile);
            continue;
        }

        $currentHash = hash_file('sha256', $pdfPath);

        if (
            $currentHash === false ||
            !hash_equals(
                strtolower((string) $document['sha256_hash']),
                strtolower($currentHash)
            )
        ) {
            otsLog("Document {$documentId}: PDF integrity check failed.", $logFile);
            continue;
        }

        $proofPath = $proofDirectory . DIRECTORY_SEPARATOR . $proofKey;
        $backupPath = $proofPath . '.bak';

        // Verify the existing proof.
        $initialResult = transparencyVerifyOtsProof($pdfPath, $proofPath);

        if (!$initialResult['success']) {
            otsLog("Document {$documentId}: Existing proof verification failed.", $logFile);
            continue;
        }

        // Attempt to upgrade the timestamp.
        $process = proc_open(
            [$python, $launcher, 'upgrade', '--timeout', '10', $proofPath],
            [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w']
            ],
            $pipes
        );

        if (!is_resource($process)) {
            otsLog("Document {$documentId}: Could not start upgrade.", $logFile);
            continue;
        }

        fclose($pipes[0]);

        $output = stream_get_contents($pipes[1]);
        $error = stream_get_contents($pipes[2]);

        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        otsLog("Document {$documentId}: Upgrade exit code {$exitCode}.", $logFile);

        if (is_string($output) && $output !== '') {
            otsLog($output, $logFile);
        }

        if (is_string($error) && $error !== '') {
            otsLog($error, $logFile);
        }

        if (is_file($backupPath)) {
            otsLog("Document {$documentId}: Previous proof backup preserved.", $logFile);
        }

        // Verify the proof again after upgrading.
        $result = transparencyVerifyOtsProof($pdfPath, $proofPath);

        if (!$result['success']) {
            otsLog("Document {$documentId}: Proof verification failed after upgrade.", $logFile);
            continue;
        }

        // Never confirm without independent Bitcoin verification.
        if (empty($result['bitcoin_verified'])) {
            otsLog(
                "Document {$documentId}: PDF proof matches, but Bitcoin confirmation is not independently verified. Keeping pending.",
                $logFile
            );
            continue;
        }

        $combinedOutput = $result['output'] . "\n" . $result['error'];

        if (!preg_match('/Bitcoin block (\d+)/i', $combinedOutput, $matches)) {
            otsLog("Document {$documentId}: Bitcoin block height unavailable.", $logFile);
            continue;
        }

        $blockHeight = (int) $matches[1];

        if ($blockHeight < 1) {
            otsLog("Document {$documentId}: Invalid Bitcoin block height.", $logFile);
            continue;
        }

        $update = $iBacolorConnection->prepare(
            "UPDATE transparency_documents
             SET ots_status = 'confirmed',
                 ots_confirmed_at = UTC_TIMESTAMP(),
                 ots_bitcoin_block_height = :block_height,
                 ots_last_checked_at = UTC_TIMESTAMP()
             WHERE document_id = :document_id
               AND ots_status = 'pending'"
        );

        $update->execute([
            'block_height' => $blockHeight,
            'document_id' => $documentId
        ]);

        otsLog(
            $update->rowCount() === 1
                ? "Document {$documentId}: CONFIRMED at Bitcoin block {$blockHeight}."
                : "Document {$documentId}: Database status was not changed.",
            $logFile
        );
    }

    otsLog("Timestamp checking finished.", $logFile);

} catch (Throwable $exception) {

    otsLog("Checker error: " . $exception->getMessage(), $logFile);

} finally {

    flock($lockHandle, LOCK_UN);
    fclose($lockHandle);
}
