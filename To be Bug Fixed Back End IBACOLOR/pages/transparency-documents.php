<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once __DIR__ . '/../includes/connection.php';
require_once __DIR__ . '/../includes/transparency-config.php';
require_once __DIR__ . '/../../includes/transparency-ots.php';

$category = is_string($_GET['category'] ?? null) ? $_GET['category'] : '';
if (!in_array($category, TRANSPARENCY_CATEGORIES, true)) {
    http_response_code(404);
    $category = '';
}
$search = is_string($_GET['q'] ?? null) ? trim($_GET['q']) : '';
$filterYear = filter_var($_GET['year'] ?? null, FILTER_VALIDATE_INT);
$conditions = ["category = :category", "status = 'Published'", 'published_at IS NOT NULL'];
$parameters = ['category' => $category];
if ($search !== '') {
    $conditions[] = '(title LIKE :search_title OR description LIKE :search_description)';
    $parameters['search_title'] = '%' . $search . '%';
    $parameters['search_description'] = '%' . $search . '%';
}
if ($filterYear !== false && $filterYear >= 1901 && $filterYear <= 2155) {
    $conditions[] = 'document_year = :document_year';
    $parameters['document_year'] = $filterYear;
} else {
    $filterYear = null;
}
$statement = $iBacolorConnection->prepare(
    'SELECT document_id, title, description, document_year, published_at
     FROM transparency_documents
     WHERE ' . implode(' AND ', $conditions) . '
     ORDER BY document_year DESC, published_at DESC, document_id DESC'
);
$statement->execute($parameters);
$documents = $category !== '' ? $statement->fetchAll(PDO::FETCH_ASSOC) : [];

function transparencyPublicEscape(string|int|null $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title><?= $category !== '' ? transparencyPublicEscape($category) . ' | Transparency' : 'Transparency documents' ?> | iBacolor</title>
        <link rel="stylesheet" href="../css/pages/transparency-documents.css" />
    </head>
    <body data-page="transparency-documents">
        <div id="app">
            <?php require __DIR__ . '/../includes/site-header.php'; ?>
            <main>
                <section class="page-header">
                    <div class="container">
                        <div class="breadcrumbs"><a href="../main.php">Home</a><span>/</span><a href="transparency.php">Transparency</a><span>/</span><?= transparencyPublicEscape($category !== '' ? $category : 'Documents') ?></div>
                        <p class="eyebrow">Open information</p>
                        <h1><?= transparencyPublicEscape($category !== '' ? $category : 'Transparency documents') ?></h1>
                        <p class="lead">Official public documents published by the Municipality of Bacolor.</p>
                        <a class="button button-outline" href="transparency.php">← Back to Transparency</a>
                    </div>
                </section>
                <?php if ($category !== ''): ?>
                    <section class="section">
                        <div class="container">
                            <form class="transparency-public-filters" method="get">
                                <input type="hidden" name="category" value="<?= transparencyPublicEscape($category) ?>" />
                                <label>Search documents<input type="search" name="q" value="<?= transparencyPublicEscape($search) ?>" placeholder="Search title or description" /></label>
                                <label>Year<input type="number" name="year" min="1901" max="2155" value="<?= $filterYear ? transparencyPublicEscape($filterYear) : '' ?>" /></label>
                                <button class="button button-outline" type="submit">Search</button>
                            </form>
                            <?php if (!$documents && ($search !== '' || $filterYear !== null)): ?>
                                <p class="notice notice-info">No published documents match your search.</p>
                            <?php elseif (!$documents): ?>
                                <p class="notice notice-info">No published documents available in this category yet.</p>
                            <?php else: ?>
                                <div class="transparency-public-documents">
                                    <?php foreach ($documents as $document): ?>
                                        <article class="card transparency-public-document">
                                            <p class="eyebrow"><?= transparencyPublicEscape($document['document_year']) ?></p>
                                            <h2><?= transparencyPublicEscape($document['title']) ?></h2>
                                            <p><?= transparencyPublicEscape($document['description'] ?: 'No description provided.') ?></p>
                                            <p class="transparency-public-date">Published <?= transparencyPublicEscape(date('F j, Y', strtotime((string) $document['published_at']))) ?></p>
                                            <div class="transparency-public-actions">
                                                <a class="button" href="transparency-file.php?id=<?= (int) $document['document_id'] ?>" target="_blank" rel="noopener">View PDF</a>
                                                <a class="button button-outline" href="transparency-file.php?id=<?= (int) $document['document_id'] ?>&amp;download=1">Download PDF</a>
                                            </div>
                                        </article>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                <?php endif; ?>
            </main>
            <footer class="site-footer">
                <div class="container footer-grid">
                    <div>
                        <a class="brand brand-footer" href="../main.php">
                            <img class="brand-logo" src="../assets/bacolor-seal.webp" alt="" />
                            <span><strong>iBacolor</strong><small>Municipal Public Service Portal</small></span>
                        </a>
                        <p>A simpler way to access municipal information and public services.</p>
                    </div>
                    <div>
                        <h3>Explore</h3>
                        <a href="services.php">Services</a>
                        <a href="news.php">News and announcements</a>
                        <a href="hotlines.php">Emergency hotlines</a>
                        <a href="transparency.php">Transparency</a>
                        <a href="resident/reports.php">Community Report</a>
                    </div>
                    <div>
                        <h3>Need assistance?</h3>
                        <p>Municipal Hall<br />(045) 900-1000<br />Mon–Fri, 8:00 AM–5:00 PM</p>
                        <a class="footer-facebook-link" href="https://www.facebook.com/SupportersOfMayorDimanDatu" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                            <svg viewBox="0 0 24 24" width="17" height="17" fill="currentColor" aria-hidden="true" focusable="false">
                                <path d="M13.7 21v-8h2.7l.4-3h-3.1V8.1c0-.9.3-1.5 1.6-1.5H17V3.9c-.3 0-1.3-.1-2.5-.1-2.5 0-4.2 1.5-4.2 4.3V10H7.5v3h2.8v8h3.4z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="container footer-bottom"><span>© 2026 Municipality of Bacolor</span></div>
            </footer>
        </div>
        <script src="../js/navigation.js"></script>
        <script src="../js/helper.js"></script>
    </body>
</html>
