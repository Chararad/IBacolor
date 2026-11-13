
<?php
if (!in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    exit('Access denied');
}

$python = 'C:\xampp\private\IBACOLOR\ots-venv\Scripts\python.exe';
$launcher = 'C:\xampp\private\IBACOLOR\ots_launcher.py';
$file = 'C:\xampp\private\IBACOLOR\apache-stamp-test.txt';

$command = [
    $python,
    $launcher,
    'stamp',
    $file
];

$process = proc_open($command, [
    0 => ['pipe', 'r'],
    1 => ['pipe', 'w'],
    2 => ['pipe', 'w']
], $pipes);

if (!is_resource($process)) {
    exit('Could not start OpenTimestamps');
}

fclose($pipes[0]);
$output = stream_get_contents($pipes[1]);
$error = stream_get_contents($pipes[2]);

fclose($pipes[1]);
fclose($pipes[2]);

$exitCode = proc_close($process);

echo "Exit Code: " . $exitCode . "<br>";
echo "Proof Created: " . (file_exists($file . '.ots') ? 'YES' : 'NO') . "<br>";
echo "<pre>" . htmlspecialchars($output . $error) . "</pre>";
