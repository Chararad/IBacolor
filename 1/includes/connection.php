<?php
declare(strict_types=1);

$host = 'localhost';
$dbname = 'ibacolor';
$username = 'root';
$password = '';

try {
    $iBacolorConnection = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $exception) {
    error_log('iBacolor database connection failed: ' . $exception->getMessage());
    throw new RuntimeException('The application database is unavailable.', 0, $exception);
}