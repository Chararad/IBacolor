<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once __DIR__ . '/../includes/connection.php';
require_once __DIR__ . '/../includes/transparency-config.php';

function transparencyFileNotFound(): never
{
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    exit('Document not found.');
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    http_response_code(405);
    header('Allow: GET');
    exit('Method not allowed.');
}
$documentId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$documentId || $documentId < 1) {
    transparencyFileNotFound();
}

$isActiveAdmin = false;
if (($_SESSION['role'] ?? '') === 'admin') {
    $accountId = filter_var($_SESSION['account_id'] ?? null, FILTER_VALIDATE_INT);
    if ($accountId) {
        $adminStatement = $iBacolorConnection->prepare(
            "SELECT 1 FROM accounts WHERE account_id = :account_id AND roles = 'admin' AND status = 'active' LIMIT 1"
        );
        $adminStatement->execute(['account_id' => $accountId]);
        $isActiveAdmin = (bool) $adminStatement->fetchColumn();
    }
}
$documentQuery = $isActiveAdmin
    ? 'SELECT title, storage_key, mime_type FROM transparency_documents WHERE document_id = :document_id LIMIT 1'
    : "SELECT title, storage_key, mime_type FROM transparency_documents
       WHERE document_id = :document_id AND status = 'Published' AND published_at IS NOT NULL LIMIT 1";
$documentStatement = $iBacolorConnection->prepare($documentQuery);
$documentStatement->execute(['document_id' => $documentId]);
$document = $documentStatement->fetch(PDO::FETCH_ASSOC);
if (!$document || $document['mime_type'] !== 'application/pdf'
    || !preg_match('/^[a-f0-9]{48}\.pdf$/', (string) $document['storage_key'])) {
    transparencyFileNotFound();
}

$storageDirectory = realpath(TRANSPARENCY_STORAGE_DIRECTORY);
$storagePath = realpath(TRANSPARENCY_STORAGE_DIRECTORY . DIRECTORY_SEPARATOR . $document['storage_key']);
if ($storageDirectory === false || $storagePath === false
    || !str_starts_with($storagePath, rtrim($storageDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR)
    || !is_file($storagePath)) {
    transparencyFileNotFound();
}

$download = ($_GET['download'] ?? '') === '1';
$disposition = $download ? 'attachment' : 'inline';
header('Content-Type: application/pdf');
header('Content-Length: ' . (string) filesize($storagePath));
header('Content-Disposition: ' . $disposition . '; filename="transparency-document-' . $documentId . '.pdf"');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, no-store, max-age=0');
readfile($storagePath);
exit;
