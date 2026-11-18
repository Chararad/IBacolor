<?php

require_once __DIR__ . '/../../includes/page-auth.php';
require_once __DIR__ . '/../../includes/transparency-config.php';
require_once __DIR__ . '/../../includes/transparency-ots.php';

$_SESSION['portal_csrf_token'] ??= bin2hex(random_bytes(32));
$csrfToken = $_SESSION['portal_csrf_token'];
$formError = '';
$notice = (string) ($_SESSION['transparency_documents_notice'] ?? '');
unset($_SESSION['transparency_documents_notice']);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? '';
    if (!is_string($submittedToken) || !hash_equals($csrfToken, $submittedToken)) {
        http_response_code(403);
        exit('Your session expired. Reload the page and try again.');
    }

    $action = is_string($_POST['action'] ?? null) ? $_POST['action'] : '';
    if (in_array($action, ['publish', 'draft', 'archive'], true)) {
        $documentId = filter_var($_POST['document_id'] ?? null, FILTER_VALIDATE_INT);
        if (!$documentId || $documentId < 1) {
            $formError = 'Select a valid document.';
        } else {
            $existsStatement = $iBacolorConnection->prepare(
                'SELECT document_id FROM transparency_documents WHERE document_id = :document_id LIMIT 1'
            );
            $existsStatement->execute(['document_id' => $documentId]);
            if (!$existsStatement->fetchColumn()) {
                $formError = 'The selected document could not be found.';
            } else {
                if ($action === 'publish') {
                    $updateStatement = $iBacolorConnection->prepare(
                        "UPDATE transparency_documents
                         SET status = 'Published', published_at = UTC_TIMESTAMP()
                         WHERE document_id = :document_id"
                    );
                } elseif ($action === 'draft') {
                    $updateStatement = $iBacolorConnection->prepare(
                        "UPDATE transparency_documents
                         SET status = 'Draft', published_at = NULL
                         WHERE document_id = :document_id"
                    );
                } else {
                    $updateStatement = $iBacolorConnection->prepare(
                        "UPDATE transparency_documents
                         SET status = 'Archived'
                         WHERE document_id = :document_id"
                    );
                }
                $updateStatement->execute(['document_id' => $documentId]);
                $_SESSION['transparency_documents_notice'] = 'Document status updated.';
                header('Location: transparency-documents.php');
                exit;
            }
        }
    } elseif ($action === 'upload') {
        $title = is_string($_POST['title'] ?? null) ? trim($_POST['title']) : '';
        $description = is_string($_POST['description'] ?? null) ? trim($_POST['description']) : '';
        $category = is_string($_POST['category'] ?? null) ? $_POST['category'] : '';
        $year = filter_var($_POST['document_year'] ?? null, FILTER_VALIDATE_INT);
        $file = $_FILES['pdf_file'] ?? null;

        if ($title === '' || mb_strlen($title, 'UTF-8') > 255) {
            $formError = 'Enter a document title of 255 characters or fewer.';
        } elseif (mb_strlen($description, 'UTF-8') > 5000) {
            $formError = 'The description must be 5,000 characters or fewer.';
        } elseif (!in_array($category, TRANSPARENCY_CATEGORIES, true)) {
            $formError = 'Select a valid transparency category.';
        } elseif ($year === false || $year < 1901 || $year > 2155) {
            $formError = 'Enter a valid document year.';
        } elseif (!is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            $formError = 'Choose a PDF file to upload.';
        } elseif (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'] ?? '')) {
            $formError = 'The file upload failed. Please try again.';
        } elseif (($file['size'] ?? 0) < 1 || $file['size'] > 10 * 1024 * 1024) {
            $formError = 'PDF files must be 10 MB or smaller.';
        } else {
            $originalName = basename((string) ($file['name'] ?? ''));
            $mimeType = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
            $signature = file_get_contents($file['tmp_name'], false, null, 0, 5);
            if (strtolower(pathinfo($originalName, PATHINFO_EXTENSION)) !== 'pdf'
                || $mimeType !== 'application/pdf'
                || $signature !== '%PDF-') {
                $formError = 'The selected file must be a valid PDF.';
            } else {
                $storageDirectory = TRANSPARENCY_STORAGE_DIRECTORY;
                if (!is_dir($storageDirectory)
                    && !mkdir($storageDirectory, 0700, true)
                    && !is_dir($storageDirectory)) {
                    $formError = 'Private PDF storage is unavailable.';
                } else {
                    $storageKey = bin2hex(random_bytes(24)) . '.pdf';
                    $storagePath = $storageDirectory . DIRECTORY_SEPARATOR . $storageKey;
                    if (!move_uploaded_file($file['tmp_name'], $storagePath)) {
                        $formError = 'The PDF could not be saved to private storage.';
                    } else {
                        try {
$otsProofPath = null;

try {
    // 1. Calculate the PDF hash.
    $sha256Hash = hash_file('sha256', $storagePath);

    if ($sha256Hash === false) {
        throw new RuntimeException('Could not calculate PDF hash.');
    }

    // 2. Generate the OpenTimestamps proof.
    $proofDirectory = $storageDirectory . DIRECTORY_SEPARATOR . 'proofs';

    try {
        $otsResult = transparencyCreateOtsProof(
            $storagePath,
            $proofDirectory
        );
    } catch (Throwable $otsException) {
        error_log('OpenTimestamps error: ' . $otsException->getMessage());
        $otsResult = ['success' => false, 'storage_key' => null];
    }

    // 3. Set blockchain information.
    $otsProofStorageKey = $otsResult['success']
        ? $otsResult['storage_key']
        : null;

    $otsStatus = $otsResult['success'] ? 'pending' : 'failed';

    $otsSubmittedAt = $otsResult['success']
        ? gmdate('Y-m-d H:i:s')
        : null;

    if ($otsProofStorageKey !== null) {
        $otsProofPath = $proofDirectory . DIRECTORY_SEPARATOR . $otsProofStorageKey;
    }

    // 4. Save document and blockchain information.
    $iBacolorConnection->beginTransaction();

    $previousStatement = $iBacolorConnection->prepare(
        'SELECT document_id, version_number
         FROM transparency_documents
         WHERE title = :title AND category = :category
         ORDER BY version_number DESC, document_id DESC
         LIMIT 1 FOR UPDATE'
    );

    $previousStatement->execute([
        'title' => $title,
        'category' => $category,
    ]);

    $previous = $previousStatement->fetch(PDO::FETCH_ASSOC);

    $insertStatement = $iBacolorConnection->prepare(
        "INSERT INTO transparency_documents
            (title, description, category, document_year, file_name, storage_key,
             mime_type, file_size, version_number, previous_document_id, status,
             uploaded_by, sha256_hash, ots_proof_storage_key, ots_status,
             ots_submitted_at)
         VALUES
            (:title, :description, :category, :document_year, :file_name, :storage_key,
             'application/pdf', :file_size, :version_number, :previous_document_id,
             'Draft', :uploaded_by, :sha256_hash, :ots_proof_storage_key, :ots_status,
             :ots_submitted_at)"
    );

    $insertStatement->execute([
        'title' => $title,
        'description' => $description !== '' ? $description : null,
        'category' => $category,
        'document_year' => $year,
        'file_name' => mb_substr($originalName, 0, 255, 'UTF-8'),
        'storage_key' => $storageKey,
        'file_size' => (int) $file['size'],
        'version_number' => $previous ? (int) $previous['version_number'] + 1 : 1,
        'previous_document_id' => $previous ? (int) $previous['document_id'] : null,
        'uploaded_by' => (int) $accountId,
        'sha256_hash' => $sha256Hash,
        'ots_proof_storage_key' => $otsProofStorageKey,
        'ots_status' => $otsStatus,
        'ots_submitted_at' => $otsSubmittedAt,
    ]);

    $iBacolorConnection->commit();

    $_SESSION['transparency_documents_notice'] =
        $otsStatus === 'pending'
            ? 'PDF uploaded as a draft. Blockchain timestamp submitted and awaiting confirmation.'
            : 'PDF uploaded as a draft. Blockchain timestamp submission failed and requires retry.';

    header('Location: transparency-documents.php');
    exit;

} catch (Throwable $exception) {

    if ($iBacolorConnection->inTransaction()) {
        $iBacolorConnection->rollBack();
    }

    if (is_file($storagePath) && !unlink($storagePath)) {
        error_log('Unable to remove an unrecorded transparency PDF: ' . $storagePath);
    }

    if ($otsProofPath !== null && is_file($otsProofPath)) {
        unlink($otsProofPath);
    }

    error_log('Transparency PDF registration failed: ' . $exception->getMessage());

    $formError = 'The PDF could not be registered. Please try again.';
}

                        } catch (PDOException $exception) {
                            if ($iBacolorConnection->inTransaction()) {
                                $iBacolorConnection->rollBack();
                            }
                            if (is_file($storagePath) && !unlink($storagePath)) {
                                error_log('Unable to remove an unrecorded transparency PDF: ' . $storagePath);
                            }
                            error_log('Transparency PDF metadata could not be saved: ' . $exception->getMessage());
                            $formError = 'The PDF could not be registered. Check the database table configuration and try again.';
                        }
                    }
                }
            }
        }
    } else {
        $formError = 'The requested action is not valid.';
    }
}

$search = is_string($_GET['q'] ?? null) ? trim($_GET['q']) : '';
$filterCategory = is_string($_GET['category'] ?? null) ? $_GET['category'] : '';
$filterStatus = is_string($_GET['status'] ?? null) ? $_GET['status'] : '';
$filterYear = filter_var($_GET['year'] ?? null, FILTER_VALIDATE_INT);
$conditions = [];
$parameters = [];
if ($search !== '') {
    $conditions[] = '(title LIKE :search_title OR description LIKE :search_description)';
    $parameters['search_title'] = '%' . $search . '%';
    $parameters['search_description'] = '%' . $search . '%';
}
if (in_array($filterCategory, TRANSPARENCY_CATEGORIES, true)) {
    $conditions[] = 'category = :category';
    $parameters['category'] = $filterCategory;
} else {
    $filterCategory = '';
}
if (in_array($filterStatus, ['Draft', 'Published', 'Archived'], true)) {
    $conditions[] = 'status = :status';
    $parameters['status'] = $filterStatus;
} else {
    $filterStatus = '';
}
if ($filterYear !== false && $filterYear >= 1901 && $filterYear <= 2155) {
    $conditions[] = 'document_year = :document_year';
    $parameters['document_year'] = $filterYear;
} else {
    $filterYear = null;
}
$listQuery = 'SELECT document_id, title, category, document_year, uploaded_at, status, version_number
              FROM transparency_documents';
if ($conditions) {
    $listQuery .= ' WHERE ' . implode(' AND ', $conditions);
}
$listQuery .= ' ORDER BY uploaded_at DESC, document_id DESC';
$listStatement = $iBacolorConnection->prepare($listQuery);
$listStatement->execute($parameters);
$documents = $listStatement->fetchAll(PDO::FETCH_ASSOC);

function transparencyAdminEscape(string|int|null $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Transparency Documents | iBacolor</title>
        <link rel="stylesheet" href="../../css/pages/admin-transparency-documents.css?v=admin-sidebar-12" />
    </head>
    <body data-page="admin/transparency-documents" class="admin-view">
        <div id="app">
            <aside class="admin-sidebar" id="admin-sidebar" aria-label="Admin menu" aria-hidden="true" hidden>
                <div class="admin-sidebar-header">
                    <button class="admin-menu-toggle admin-menu-close" type="button" aria-label="Close admin menu" aria-expanded="false" aria-controls="admin-sidebar"><span class="menu-icon" aria-hidden="true"><span></span><span></span><span></span></span></button>
                    <a class="admin-sidebar-brand" href="../admin/dashboard.php"><img class="brand-logo" src="../../assets/bacolor-seal.webp" alt="" /><span><strong>iBacolor</strong><small>Admin portal</small></span></a>
                </div>
                <p class="admin-label">Workspace</p>
                <a href="../admin/dashboard.php">▦ Dashboard</a><a href="../admin/requests.php">▤ Service requests</a><a href="../admin/documents.php">▧ Documents</a><a href="../admin/reports.php">! Community reports</a>
                <p class="admin-label">Content</p>
                <a href="../admin/news.php">✦ News</a><a href="../admin/transparency-documents.php">▤ Transparency Documents</a><a href="../admin/services.php">▣ Services</a><a href="../admin/hotlines.php">☎ Hotlines</a><a href="../admin/users.php">◯ Users</a>
                <div class="admin-sidebar-footer">
                    <a href="../logout.php">Sign out</a>
                </div>
            </aside>
            <?php require __DIR__ . '/../../includes/site-header.php'; ?>
            <main>
                <section class="page-header">
                    <div class="container">
                        <div class="breadcrumbs">Administration <span>/</span>Transparency Documents</div>
                        <p class="eyebrow">Administration</p>
                        <h1>Transparency Documents</h1>
                        <p class="lead">Upload official PDFs, keep document versions, and manage public availability.</p>
                    </div>
                </section>
                <section class="section">
                    <div class="container transparency-admin-layout">
                        <?php if ($notice !== ''): ?>
                            <p class="notice notice-info" role="status"><?= transparencyAdminEscape($notice) ?></p>
                        <?php endif; ?>
                        <?php if ($formError !== ''): ?>
                            <p class="notice notice-warning" role="alert"><?= transparencyAdminEscape($formError) ?></p>
                        <?php endif; ?>
                        <form class="form-card transparency-upload-form" method="post" enctype="multipart/form-data">
                            <h2>Upload an official PDF</h2>
                            <input type="hidden" name="csrf_token" value="<?= transparencyAdminEscape($csrfToken) ?>" />
                            <input type="hidden" name="action" value="upload" />
                            <label>Document title<input name="title" maxlength="255" required /></label>
                            <label>Description<textarea name="description" rows="3" maxlength="5000"></textarea></label>
                            <div class="transparency-form-row">
                                <label>Category
                                    <select name="category" required>
                                        <option value="">Select a category</option>
                                        <?php foreach (TRANSPARENCY_CATEGORIES as $category): ?>
                                            <option value="<?= transparencyAdminEscape($category) ?>"><?= transparencyAdminEscape($category) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                                <label>Document year<input name="document_year" type="number" min="1901" max="2155" value="<?= (int) date('Y') ?>" required /></label>
                            </div>
                            <label>PDF file<input name="pdf_file" type="file" accept=".pdf,application/pdf" required /><small>PDF only, up to 10 MB. Uploading the same title and category creates a new version.</small></label>
                            <div class="form-actions"><button class="button" type="submit">Upload PDF as Draft</button></div>
                        </form>

                        <article class="card transparency-document-management">
                            <h2>Manage documents</h2>
                            <form class="transparency-filters" method="get">
                                <label>Search<input type="search" name="q" value="<?= transparencyAdminEscape($search) ?>" placeholder="Title or description" /></label>
                                <label>Category
                                    <select name="category">
                                        <option value="">All categories</option>
                                        <?php foreach (TRANSPARENCY_CATEGORIES as $category): ?>
                                            <option value="<?= transparencyAdminEscape($category) ?>" <?= $filterCategory === $category ? 'selected' : '' ?>><?= transparencyAdminEscape($category) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                                <label>Year<input type="number" name="year" min="1901" max="2155" value="<?= $filterYear ? transparencyAdminEscape($filterYear) : '' ?>" /></label>
                                <label>Status
                                    <select name="status">
                                        <option value="">All statuses</option>
                                        <?php foreach (['Draft', 'Published', 'Archived'] as $status): ?>
                                            <option value="<?= transparencyAdminEscape($status) ?>" <?= $filterStatus === $status ? 'selected' : '' ?>><?= transparencyAdminEscape($status) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                                <button class="button button-outline" type="submit">Filter</button>
                            </form>
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr><th>Title</th><th>Category</th><th>Year</th><th>Uploaded</th><th>Status</th><th>Actions</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!$documents): ?>
                                            <tr><td colspan="6">No transparency documents match these filters.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($documents as $document): ?>
                                                <tr>
                                                    <td><?= transparencyAdminEscape($document['title']) ?> <small>v<?= transparencyAdminEscape($document['version_number']) ?></small></td>
                                                    <td><?= transparencyAdminEscape($document['category']) ?></td>
                                                    <td><?= transparencyAdminEscape($document['document_year']) ?></td>
                                                    <td><?= transparencyAdminEscape(date('M j, Y', strtotime((string) $document['uploaded_at']))) ?></td>
                                                    <td><span class="document-status"><?= transparencyAdminEscape($document['status']) ?></span></td>
                                                    <td>
                                                        <div class="document-actions">
                                                            <a href="../transparency-file.php?id=<?= (int) $document['document_id'] ?>" target="_blank" rel="noopener">View PDF</a>
                                                            <a href="../transparency-file.php?id=<?= (int) $document['document_id'] ?>&amp;download=1">Download</a>
                                                            <?php if ($document['status'] === 'Published'): ?>
                                                                <form method="post">
                                                                    <input type="hidden" name="csrf_token" value="<?= transparencyAdminEscape($csrfToken) ?>" />
                                                                    <input type="hidden" name="action" value="draft" />
                                                                    <input type="hidden" name="document_id" value="<?= (int) $document['document_id'] ?>" />
                                                                    <button type="submit">Return to Draft</button>
                                                                </form>
                                                            <?php else: ?>
                                                                <form method="post">
                                                                    <input type="hidden" name="csrf_token" value="<?= transparencyAdminEscape($csrfToken) ?>" />
                                                                    <input type="hidden" name="action" value="publish" />
                                                                    <input type="hidden" name="document_id" value="<?= (int) $document['document_id'] ?>" />
                                                                    <button type="submit">Publish</button>
                                                                </form>
                                                            <?php endif; ?>
                                                            <?php if ($document['status'] !== 'Archived'): ?>
                                                                <form method="post">
                                                                    <input type="hidden" name="csrf_token" value="<?= transparencyAdminEscape($csrfToken) ?>" />
                                                                    <input type="hidden" name="action" value="archive" />
                                                                    <input type="hidden" name="document_id" value="<?= (int) $document['document_id'] ?>" />
                                                                    <button type="submit">Archive</button>
                                                                </form>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </article>
                    </div>
                </section>
            </main>
        </div>
        <script src="../../js/portal.js?v=admin-sidebar-6"></script>
    </body>
</html>
