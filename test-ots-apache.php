
<?php

// Allow access only from the local computer.
$allowed = ['127.0.0.1', '::1'];

if (!in_array($_SERVER['REMOTE_ADDR'] ?? '', $allowed, true)) {
    http_response_code(403);
    exit('Access denied.');
}

$python = 'C:\\xampp\\private\\IBACOLOR\\ots-venv\\Scripts\\python.exe';
$launcher = 'C:\\xampp\\private\\IBACOLOR\\ots_launcher.py';
$proof = 'C:\\xampp\\private\\IBACOLOR\\php-stamp-test-2.txt.ots';

$command = [
    $python,
    $launcher,
    'info',
    $proof
];

$descriptors = [
    0 => ['pipe', 'r'],
    1 => ['pipe', 'w'],
    2 => ['pipe', 'w']
];

echo '<h2>IBACOLOR Apache OpenTimestamps Test</h2>';

$process = proc_open($command, $descriptors, $pipes);

if (!is_resource($process)) {
    exit('Failed to start OpenTimestamps.');
}

fclose($pipes[0]);

$output = stream_get_contents($pipes[1]);
$error = stream_get_contents($pipes[2]);

fclose($pipes[1]);
fclose($pipes[2]);

$exitCode = proc_close($process);

echo '<h3>Exit Code</h3>';
echo '<pre>' . htmlspecialchars((string) $exitCode) . '</pre>';

echo '<h3>Output</h3>';
echo '<pre>' . htmlspecialchars($output) . '</pre>';

echo '<h3>Error</h3>';
echo '<pre>' . htmlspecialchars($error) . '</pre>';
