<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$requiredRole = basename(dirname($_SERVER['SCRIPT_NAME'] ?? ''));
if (!in_array($requiredRole, ['resident', 'admin'], true)) {
    http_response_code(403);
    exit('Access denied.');
}
$allowedRoles = $requiredRole === 'resident' ? ['resident', 'admin'] : ['admin'];

require_once __DIR__ . '/connection.php';

$accountId = filter_var($_SESSION['account_id'] ?? null, FILTER_VALIDATE_INT);
if (!$accountId || !in_array($_SESSION['role'] ?? '', $allowedRoles, true)) {
    header('Location: ../login.php');
    exit;
}

$statement = $iBacolorConnection->prepare(
    'SELECT roles, status FROM accounts WHERE account_id = :account_id LIMIT 1'
);
$statement->execute(['account_id' => $accountId]);
$account = $statement->fetch(PDO::FETCH_ASSOC);
if (!$account || !in_array($account['roles'], $allowedRoles, true) || $account['roles'] !== $_SESSION['role'] || $account['status'] !== 'active') {
    $_SESSION = [];
    session_destroy();
    header('Location: ../login.php');
    exit;
}

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
