<?php

/**
 * Create an OpenTimestamps proof for an uploaded PDF.
 */
function transparencyCreateOtsProof(
    string $sourceFile,
    string $proofDirectory
): array {

    $python = 'C:\xampp\private\IBACOLOR\ots-venv\Scripts\python.exe';
    $launcher = 'C:\xampp\private\IBACOLOR\ots_launcher.py';

    if (
        !is_file($sourceFile) ||
        !is_file($python) ||
        !is_file($launcher)
    ) {
        return [
            'success' => false,
            'storage_key' => null
        ];
    }

    if (
        !is_dir($proofDirectory) &&
        !mkdir($proofDirectory, 0700, true) &&
        !is_dir($proofDirectory)
    ) {
        return [
            'success' => false,
            'storage_key' => null
        ];
    }

    $tempFile = tempnam($proofDirectory, 'ots_');

    if ($tempFile === false) {
        return [
            'success' => false,
            'storage_key' => null
        ];
    }

    $tempProof = $tempFile . '.ots';
    $proofKey = bin2hex(random_bytes(24)) . '.ots';

    try {

        if (!copy($sourceFile, $tempFile)) {
            return [
                'success' => false,
                'storage_key' => null
            ];
        }

        $process = proc_open(
            [$python, $launcher, 'stamp', $tempFile],
            [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w']
            ],
            $pipes
        );

        if (!is_resource($process)) {
            return [
                'success' => false,
                'storage_key' => null
            ];
        }

        fclose($pipes[0]);

        $output = stream_get_contents($pipes[1]);
        $error = stream_get_contents($pipes[2]);

        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        if ($exitCode !== 0 || !is_file($tempProof)) {
            error_log(
                'OpenTimestamps stamping failed: ' .
                (is_string($error) ? $error : '')
            );

            return [
                'success' => false,
                'storage_key' => null
            ];
        }

        $finalProof = $proofDirectory . DIRECTORY_SEPARATOR . $proofKey;

        if (!rename($tempProof, $finalProof)) {
            return [
                'success' => false,
                'storage_key' => null
            ];
        }

        return [
            'success' => true,
            'storage_key' => $proofKey
        ];

    } finally {

        if (is_file($tempFile)) {
            unlink($tempFile);
        }

        if (is_file($tempProof)) {
            unlink($tempProof);
        }
    }
}


/**
 * Verify an OpenTimestamps proof against its original PDF.
 *
 * Bitcoin node verification is disabled because this installation
 * does not have a locally configured Bitcoin Core node.
 *
 * This checks the PDF/proof match and recognizes timestamp
 * attestations. It does NOT independently verify Bitcoin blocks.
 */
function transparencyVerifyOtsProof(
    string $sourceFile,
    string $proofFile
): array {

    $python = 'C:\xampp\private\IBACOLOR\ots-venv\Scripts\python.exe';
    $launcher = 'C:\xampp\private\IBACOLOR\ots_launcher.py';

    if (
        !is_file($sourceFile) ||
        !is_file($proofFile) ||
        !is_file($python) ||
        !is_file($launcher)
    ) {
        return [
            'success' => false,
            'bitcoin_verified' => false,
            'output' => '',
            'error' => 'Required PDF, proof, or OTS launcher file is missing.'
        ];
    }

    $process = proc_open(
        [
            $python,
            $launcher,
            '--no-bitcoin',
            'verify',
            '-f',
            $sourceFile,
            $proofFile
        ],
        [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w']
        ],
        $pipes
    );

    if (!is_resource($process)) {
        return [
            'success' => false,
            'bitcoin_verified' => false,
            'output' => '',
            'error' => 'Could not start the OpenTimestamps verification process.'
        ];
    }

    fclose($pipes[0]);

    $output = stream_get_contents($pipes[1]);
    $error = stream_get_contents($pipes[2]);

    fclose($pipes[1]);
    fclose($pipes[2]);

    $exitCode = proc_close($process);

    $output = is_string($output) ? $output : '';
    $error = is_string($error) ? $error : '';

    $combinedOutput = $output . "\n" . $error;

    // Reject a proof if the PDF does not match.
    if (
        stripos($combinedOutput, 'File does not match original') !== false
    ) {
        return [
            'success' => false,
            'bitcoin_verified' => false,
            'output' => $output,
            'error' => $error
        ];
    }

    // Recognize the available OpenTimestamps proof messages.
    // These messages can appear on stdout OR stderr.
    $validProof =
        stripos($combinedOutput, 'Pending confirmation') !== false ||
        stripos($combinedOutput, 'Timestamped by transaction') !== false ||
        stripos($combinedOutput, 'Bitcoin block') !== false;

    return [
        'success' => $validProof,
        'bitcoin_verified' => false,
        'output' => $output,
        'error' => $error
    ];
}
