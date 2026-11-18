<?php
    session_start();
    $isAuthenticated = isset($_SESSION['account_id']) && in_array($_SESSION['role'] ?? '', ['resident', 'admin'], true);
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Transparency | iBacolor</title>
        <link rel="stylesheet" href="../css/pages/transparency.css" />
    </head>
    <body data-page="transparency">
        <div id="app">
            <?php require __DIR__ . '/../includes/site-header.php'; ?>
            <main>
                <section class="page-header">
                    <div class="container">
                        <p class="eyebrow">Open information</p>
                        <h1>Transparency and public records</h1>
                        <p class="lead">A centralized platform for public access to government reports, public documents, and official records—supporting accountability, openness, and informed public participation.</p>
                    </div>
                </section>
                <section class="section">
                    <div class="container">
                        <div class="notice notice-info">Official documents should be published here by the responsible municipal office. This prototype does not contain verified public records or downloadable government documents.</div>
                        <div class="card-grid transparency-grid">
                            <article class="card transparency-card">
                                <span class="transparency-icon" aria-hidden="true">₱</span>
                                <h2>Budget allocations</h2>
                                <p>Approved budgets, appropriations, and allocation summaries.</p>
                                <a class="status status-pending" href="transparency-documents.php?category=<?= rawurlencode('Budget allocations') ?>">Browse documents</a>
                            </article>
                            <article class="card transparency-card">
                                <span class="transparency-icon" aria-hidden="true">▤</span>
                                <h2>Financial statements</h2>
                                <p>Audited statements, financial reports, and public expenditure records.</p>
                                <a class="status status-pending" href="transparency-documents.php?category=<?= rawurlencode('Financial statements') ?>">Browse documents</a>
                            </article>
                            <article class="card transparency-card">
                                <span class="transparency-icon" aria-hidden="true">⌂</span>
                                <h2>Projects and updates</h2>
                                <p>Project descriptions, timelines, implementation status, and progress updates.</p>
                                <a class="status status-pending" href="transparency-documents.php?category=<?= rawurlencode('Projects and updates') ?>">Browse documents</a>
                            </article>
                            <article class="card transparency-card">
                                <span class="transparency-icon" aria-hidden="true">✓</span>
                                <h2>Accomplishment reports</h2>
                                <p>Annual and program-level accomplishments and performance reports.</p>
                                <a class="status status-pending" href="transparency-documents.php?category=<?= rawurlencode('Accomplishment reports') ?>">Browse documents</a>
                            </article>
                            <article class="card transparency-card">
                                <span class="transparency-icon" aria-hidden="true">▣</span>
                                <h2>Procurement records</h2>
                                <p>Procurement plans, bid opportunities, awards, and contract information.</p>
                                <a class="status status-pending" href="transparency-documents.php?category=<?= rawurlencode('Procurement records') ?>">Browse documents</a>
                            </article>
                            <article class="card transparency-card">
                                <span class="transparency-icon" aria-hidden="true">▧</span>
                                <h2>Official publications</h2>
                                <p>Ordinances, resolutions, notices, and other public government records.</p>
                                <a class="status status-pending" href="transparency-documents.php?category=<?= rawurlencode('Official publications') ?>">Browse documents</a>
                            </article>
                        </div>
                        <article class="card transparency-contact">
                            <h2>Request public information</h2>
                            <p>For records not yet published, contact the appropriate municipal office and confirm any applicable access procedures.</p>
                            <a class="button button-outline" href="hotlines.php">Find an office contact</a>
                        </article>
                    </div>
                </section>
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
                    </div>
                    <div>
                        <h3>Need assistance?</h3>
                        <p>Municipal Hall<br />(045) 900-1000<br />Mon–Fri, 8:00 AM–5:00 PM</p>
                    </div>
                </div>
                <div class="container footer-bottom">
                    <span>© 2026 Municipality of Bacolor</span>
                </div>
            </footer>
        </div>
        <script src="../js/navigation.js"></script>
        <script src="../js/helper.js"></script>
    </body>
</html>
