<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../includes/connection.php';
require_once __DIR__ . '/../includes/transparency-config.php';
require_once __DIR__ . '/../includes/transparency-ots.php';


/**
 * Return a generic 404 response.
 */
function verificationNotFound(): never
{
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    exit('Document not found.');
}


/**
 * Accept GET requests only.
 */
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    http_response_code(405);
    header('Allow: GET');
    exit('Method not allowed.');
}


/**
 * Validate document ID.
 */
$documentId = filter_var(
    $_GET['id'] ?? null,
    FILTER_VALIDATE_INT
);

if (!$documentId || $documentId < 1) {
    verificationNotFound();
}


/**
 * Retrieve published document information.
 */
$statement = $iBacolorConnection->prepare(
    "SELECT
        document_id,
        title,
        storage_key,
        mime_type,
        sha256_hash,
        ots_proof_storage_key,
        ots_status,
        ots_submitted_at,
        ots_confirmed_at,
        ots_bitcoin_block_height
    FROM transparency_documents
    WHERE document_id = :document_id
      AND status = 'Published'
      AND published_at IS NOT NULL
    LIMIT 1"
);

$statement->execute([
    'document_id' => $documentId
]);

$document = $statement->fetch(PDO::FETCH_ASSOC);

if (!$document) {
    verificationNotFound();
}


/* =========================================
   1. SHA-256 INTEGRITY VERIFICATION
========================================= */

$hashVerified = false;
$hashMessage = '';

$storedHash = strtolower(
    (string) ($document['sha256_hash'] ?? '')
);

$storageDirectory = realpath(
    TRANSPARENCY_STORAGE_DIRECTORY
);

$storagePath = false;

if (!preg_match('/^[a-f0-9]{64}$/', $storedHash)) {

    $hashMessage = 'No valid SHA-256 hash is recorded for this document.';

} elseif ($storageDirectory === false) {

    $hashMessage = 'The private document storage directory could not be located.';

} else {

    $storagePath = realpath(
        TRANSPARENCY_STORAGE_DIRECTORY .
        DIRECTORY_SEPARATOR .
        (string) $document['storage_key']
    );

    if (
        $storagePath === false ||
        !str_starts_with(
            $storagePath,
            rtrim($storageDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
        ) ||
        !is_file($storagePath) ||
        $document['mime_type'] !== 'application/pdf'
    ) {

        $hashMessage = 'The original PDF could not be located.';

    } else {

        $currentHash = hash_file('sha256', $storagePath);

        if ($currentHash === false) {

            $hashMessage = 'Unable to calculate the PDF hash.';

        } elseif (hash_equals($storedHash, strtolower($currentHash))) {

            $hashVerified = true;

            $hashMessage = 'The PDF matches its originally recorded SHA-256 hash.';

        } else {

            $hashMessage = 'The PDF does not match its originally recorded SHA-256 hash.';
        }
    }
}


/* =========================================
   2. OPENTIMESTAMPS PROOF VERIFICATION
========================================= */

$otsVerified = false;
$otsMessage = '';
$otsOutput = '';
$otsError = '';

$proofKey = (string) (
    $document['ots_proof_storage_key'] ?? ''
);

if ($proofKey === '') {

    $otsMessage = 'No OpenTimestamps proof is recorded for this document.';

} elseif (!preg_match('/^[a-f0-9]{48}\.ots$/', $proofKey)) {

    $otsMessage = 'The recorded timestamp proof key is invalid.';

} elseif (
    !$hashVerified ||
    $storagePath === false
) {

    $otsMessage = 'OpenTimestamps verification cannot proceed because the PDF failed the integrity check.';

} else {

    $proofDirectory = realpath(
        TRANSPARENCY_STORAGE_DIRECTORY .
        DIRECTORY_SEPARATOR .
        'proofs'
    );

    $proofPath = realpath(
        TRANSPARENCY_STORAGE_DIRECTORY .
        DIRECTORY_SEPARATOR .
        'proofs' .
        DIRECTORY_SEPARATOR .
        $proofKey
    );

    if (
        $proofDirectory === false ||
        $proofPath === false ||
        !str_starts_with(
            $proofPath,
            rtrim($proofDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
        ) ||
        !is_file($proofPath)
    ) {

        $otsMessage = 'The timestamp proof file could not be located.';

    } else {

        $otsResult = transparencyVerifyOtsProof(
            $storagePath,
            $proofPath
        );

        $otsVerified = $otsResult['success'];

        $otsOutput = $otsResult['output'];
        $otsError = $otsResult['error'];

        $otsMessage = $otsVerified
            ? 'The OpenTimestamps proof matches the original PDF.'
            : 'OpenTimestamps could not verify the PDF against its recorded proof.';
    }
}


/* =========================================
   3. BITCOIN TIMESTAMP STATUS
========================================= */

$blockchainStatus = (string) (
    $document['ots_status'] ?? 'not_submitted'
);

$blockchainMessage = match ($blockchainStatus) {

    'confirmed' =>
        'The timestamp has been confirmed on the Bitcoin blockchain.',

    'pending' =>
        'The timestamp has been submitted and is awaiting Bitcoin blockchain confirmation.',

    'failed' =>
        'The timestamp submission or verification previously failed.',

    default =>
        'No blockchain timestamp has been submitted for this document.'
};

$confirmedAt = $document['ots_confirmed_at'] ?? null;
$blockHeight = $document['ots_bitcoin_block_height'] ?? null;
$submittedAt = $document['ots_submitted_at'] ?? null;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Document Verification | iBacolor</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px 15px;
            color: #333;
        }

        .verification-container {
            max-width: 750px;
            margin: 30px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
        }

        h1 {
            color: #541616;
            margin-top: 0;
        }

        .document-title {
            color: #555;
            margin-bottom: 25px;
        }

        .result {
            padding: 16px;
            margin: 15px 0;
            border-radius: 7px;
            border-left: 5px solid;
        }

        .success {
            background: #eaf7ee;
            border-color: #238b45;
        }

        .warning {
            background: #fff6df;
            border-color: #d99a16;
        }

        .failed {
            background: #fdecec;
            border-color: #c62828;
        }

        .result strong {
            display: block;
            margin-bottom: 7px;
        }

        .details {
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }

        .details p {
            overflow-wrap: anywhere;
        }


        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #541616;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="verification-container">

    <h1>Document Verification</h1>

    <p class="document-title">
        <strong>Document:</strong>
        <?= htmlspecialchars(
            (string) $document['title'],
            ENT_QUOTES,
            'UTF-8'
        ) ?>
    </p>


    <!-- PDF INTEGRITY RESULT -->

    <div class="result <?= $hashVerified ? 'success' : 'failed' ?>">

        <strong>1. PDF Integrity Check</strong>

        <?= htmlspecialchars(
            $hashMessage,
            ENT_QUOTES,
            'UTF-8'
        ) ?>

    </div>


    <!-- OPENTIMESTAMPS RESULT -->

    <div class="result <?= $otsVerified ? 'success' : 'failed' ?>">

        <strong>2. OpenTimestamps Proof Verification</strong>

        <?= htmlspecialchars(
            $otsMessage,
            ENT_QUOTES,
            'UTF-8'
        ) ?>

    </div>




    <!-- BITCOIN BLOCKCHAIN RESULT -->

    <div class="result <?= 
        $blockchainStatus === 'confirmed'
            ? 'success'
            : (
                $blockchainStatus === 'pending'
                    ? 'warning'
                    : 'failed'
            )
    ?>">

        <strong>3. Bitcoin Blockchain Timestamp</strong>

        <?= htmlspecialchars(
            $blockchainMessage,
            ENT_QUOTES,
            'UTF-8'
        ) ?>

    </div>


    <!-- VERIFICATION DETAILS -->

    <div class="details">

        <h3>Verification Details</h3>

        <p>
            <strong>SHA-256 Hash:</strong><br>

            <?= htmlspecialchars(
                $storedHash,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>

        <p>
            <strong>Timestamp Submission:</strong>

            <?= htmlspecialchars(
                (string) ($submittedAt ?? 'Not available'),
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>

        <p>
            <strong>Confirmation Date:</strong>

            <?= htmlspecialchars(
                (string) ($confirmedAt ?? 'Not yet confirmed'),
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>

        <p>
            <strong>Bitcoin Block Height:</strong>

            <?= htmlspecialchars(
                (string) ($blockHeight ?? 'Not yet available'),
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>

    </div>


    <a class="back-link" href="transparency-documents.php">
        &larr; Back to Transparency Documents
    </a>

</div>

</body>
</html>
