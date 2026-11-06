<?php
    session_start();
    $isAuthenticated = isset($_SESSION['account_id']) && in_array($_SESSION['role'] ?? '', ['resident', 'admin'], true);
    require_once '../includes/connection.php';

    $servicesQuery = 'SELECT service_id, code, name, description, category, office, status, fee_information, processing_info, instructions FROM services ORDER BY name';
    $servicesStatement = $iBacolorConnection->prepare($servicesQuery);
    $servicesStatement->execute();
    $services = $servicesStatement->fetchAll(PDO::FETCH_ASSOC);

    $requirementsByService = [];
    $requirementsQuery = 'SELECT service_id, requirement, display_order FROM service_requirements ORDER BY display_order, requirement_id';
    $requirementsStatement = $iBacolorConnection->prepare($requirementsQuery);
    $requirementsStatement->execute();

    foreach ($requirementsStatement->fetchAll(PDO::FETCH_ASSOC) as $requirement) {
        $requirementsByService[$requirement['service_id']][] = $requirement['requirement'];
    }

    $categories = [];
    foreach ($services as $service) {
        if (!in_array($service['category'], $categories, true)) {
            $categories[] = $service['category'];
        }
    }
    sort($categories, SORT_NATURAL | SORT_FLAG_CASE);
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Services | iBacolor</title>
        <link rel="stylesheet" href="../css/pages/services.css" />
    </head>
    <body data-page="services">
        <div id="app">
            <header class="site-header">
                <div class="container header-inner">
                    <a class="brand" href="../main.php">
                        <img class="brand-logo" src="../assets/bacolor-seal.webp" alt="" />
                        <span><strong>iBacolor</strong><small>Municipal Public Service Portal</small></span>
                    </a>
                    <button class="menu-toggle" aria-expanded="false" aria-controls="main-nav">Menu</button>
                    <div id="main-nav">
                        <nav class="site-nav" aria-label="public navigation">
                            <div class="nav-links">
                                <a href="../main.php">Home</a>
                                <a href="services.php">Services</a>
                                <a href="news.php">News</a>
                                <a href="hotlines.php">Emergency Hotlines</a>
                                <a href="transparency.php">Transparency</a>
                            </div>
                            <a id="resident-login-button" class="button button-small" href="login.php" data-authenticated="<?= $isAuthenticated ? 'true' : 'false' ?>" data-logout-href="logout.php">Resident sign in</a>
                        </nav>
                    </div>
                </div>
            </header>
            <main>
                <section class="page-header">
                    <div class="container">
                        <div class="breadcrumbs"><a href="../main.php">Home</a><span>/</span>Services</div>
                        <p class="eyebrow">Public services</p>
                        <h1>Municipal services</h1>
                        <p class="lead">Find the right service, review requirements, and start a request when you are ready.</p>
                    </div>
                </section>
                <section class="section service-directory">
                    <div class="container">
                        <div class="filter-bar">
                            <label>Search services<input id="service-search" type="search" placeholder="Search by service, office or keyword" aria-label="Search services" /></label>
                            <label>Category<select id="service-category" aria-label="Filter by category">
                                <option value="">All categories</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?></option>
                                <?php endforeach; ?>
                            </select></label>
                        </div>
                        <p id="service-count" class="results-count" aria-live="polite"><?= count($services) ?> services available</p>
                        <div id="service-list" class="card-grid">
                            <?php foreach ($services as $service): ?>
                                <?php
                                    $requirements = $requirementsByService[$service['service_id']] ?? [];
                                    $keyRequirements = array_slice($requirements, 0, 2);
                                    $otherRequirements = array_slice($requirements, 2);
                                ?>
                                <article class="card service-card" data-category="<?= htmlspecialchars($service['category'], ENT_QUOTES, 'UTF-8') ?>" data-service-code="<?= htmlspecialchars($service['code'], ENT_QUOTES, 'UTF-8') ?>">
                                    <div class="service-card-heading">
                                        <div class="service-icon" aria-hidden="true">▣</div>
                                        <span class="tag"><?= htmlspecialchars($service['category'], ENT_QUOTES, 'UTF-8') ?></span>
                                    </div>
                                    <h3><?= htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                                    <p><?= htmlspecialchars($service['description'], ENT_QUOTES, 'UTF-8') ?></p>
                                    <p class="service-office"><span>Responsible office</span><strong><?= htmlspecialchars($service['office'], ENT_QUOTES, 'UTF-8') ?></strong></p>
                                    <h4>Key requirements</h4>
                                    <ul class="compact-list">
                                        <?php if ($keyRequirements): ?>
                                            <?php foreach ($keyRequirements as $requirement): ?>
                                                <li><?= htmlspecialchars($requirement, ENT_QUOTES, 'UTF-8') ?></li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li>Contact the responsible office for current requirements.</li>
                                        <?php endif; ?>
                                    </ul>
                                    <details class="service-details">
                                        <summary>View full requirements and details</summary>
                                        <?php if ($otherRequirements): ?>
                                            <ul class="compact-list">
                                                <?php foreach ($otherRequirements as $requirement): ?>
                                                    <li><?= htmlspecialchars($requirement, ENT_QUOTES, 'UTF-8') ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                        <?php if ($service['fee_information'] !== null && $service['fee_information'] !== ''): ?>
                                            <p><strong>Fees:</strong> <?= htmlspecialchars($service['fee_information'], ENT_QUOTES, 'UTF-8') ?></p>
                                        <?php endif; ?>
                                        <?php if ($service['processing_info'] !== null && $service['processing_info'] !== ''): ?>
                                            <p><strong>Processing:</strong> <?= htmlspecialchars($service['processing_info'], ENT_QUOTES, 'UTF-8') ?></p>
                                        <?php endif; ?>
                                        <?php if ($service['instructions'] !== null && $service['instructions'] !== ''): ?>
                                            <p><strong>How to apply:</strong> <?= htmlspecialchars($service['instructions'], ENT_QUOTES, 'UTF-8') ?></p>
                                        <?php endif; ?>
                                    </details>
                                    <a class="button button-outline" href="resident/request-form.php?service=<?= htmlspecialchars(rawurlencode($service['code']), ENT_QUOTES, 'UTF-8') ?>">Start request</a>
                                </article>
                            <?php endforeach; ?>
                        </div>
                        <div id="service-empty" class="empty-state" <?= $services ? 'hidden' : '' ?>>
                            <strong>No services found.</strong>
                            <p>Try a different keyword or category.</p>
                        </div>
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
            <div class="modal" id="demo-modal" role="dialog" aria-modal="true" aria-hidden="true">
                <div class="modal-content">
                    <button class="modal-close" type="button" aria-label="Close">×</button>
                    <h2>iBacolor</h2>
                    <p>This dialog is part of the frontend component foundation.</p>
                </div>
            </div>
            <nav class="bottom-nav" aria-label="Quick navigation">
                <a href="../main.php"><span class="icon" aria-hidden="true">⌂</span><span>Home</span></a>
                <a href="services.php"><span class="icon" aria-hidden="true">▦</span><span>Services</span></a>
                <a class="emergency-action" href="hotlines.php"><span class="icon" aria-hidden="true">☎</span><span>Emergency</span></a>
                <a href="news.php"><span class="icon" aria-hidden="true">▤</span><span>Updates</span></a>
                <a href="login.php?next=resident/profile.php"><span class="icon" aria-hidden="true">◯</span><span>Profile</span></a>
            </nav>
        </div>
        <script src="../js/services.js"></script>
        <script src="../js/helper.js"></script>
    </body>
</html>
