<?php
session_start();
require_once __DIR__ . '/includes/connection.php';

$isAuthenticated = isset($_SESSION['account_id']) && in_array($_SESSION['role'] ?? '', ['resident', 'admin'], true);
$servicesQuery = 'SELECT code, name, description, category, office FROM services ORDER BY name LIMIT 3';
$servicesStatement = $iBacolorConnection->prepare($servicesQuery);
$servicesStatement->execute();
$services = $servicesStatement->fetchAll(PDO::FETCH_ASSOC);

function home_escape(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// ---- Site search (?q=...) ----------------------------------------------
$searchQuery = trim((string) ($_GET['q'] ?? ''));
$searchQuery = function_exists('mb_substr') ? mb_substr($searchQuery, 0, 100) : substr($searchQuery, 0, 100);
$searchServices = [];
$searchPages = [];

if ($searchQuery !== '') {
    $searchTerms = array_slice(array_values(array_filter(preg_split('/\s+/', $searchQuery) ?: [], 'strlen')), 0, 5);

    // Services: every term must match at least one column.
    $searchConditions = [];
    $searchParams = [];
    foreach ($searchTerms as $termIndex => $term) {
        $like = '%' . addcslashes($term, '\\%_') . '%';
        $searchConditions[] = "(name LIKE :t{$termIndex}a OR description LIKE :t{$termIndex}b OR category LIKE :t{$termIndex}c OR office LIKE :t{$termIndex}d OR code LIKE :t{$termIndex}e)";
        foreach (['a', 'b', 'c', 'd', 'e'] as $suffix) {
            $searchParams["t{$termIndex}{$suffix}"] = $like;
        }
    }
    if ($searchConditions) {
        $searchStatement = $iBacolorConnection->prepare(
            'SELECT code, name, description, category, office FROM services WHERE '
            . implode(' AND ', $searchConditions)
            . ' ORDER BY name LIMIT 12'
        );
        $searchStatement->execute($searchParams);
        $searchServices = $searchStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Site pages and announcements.
    $siteSearchIndex = [
        ['title' => 'Municipal services', 'url' => 'pages/services.php', 'description' => 'Requirements, responsible offices and application guidance.', 'keywords' => 'services permits taxes requirements office apply'],
        ['title' => 'News and announcements', 'url' => 'pages/news.php', 'description' => 'Municipal announcements, advisories and community updates.', 'keywords' => 'news announcements advisory updates events'],
        ['title' => 'Emergency hotlines', 'url' => 'pages/hotlines.php', 'description' => 'Emergency numbers and municipal office contacts.', 'keywords' => 'emergency hotline contact phone mdrrmo police fire health office directory barangay'],
        ['title' => 'Transparency', 'url' => 'pages/transparency.php', 'description' => 'Public reports, budgets and projects.', 'keywords' => 'transparency budget reports projects documents public'],
        ['title' => 'Community Report', 'url' => 'pages/resident/reports.php', 'description' => 'Report a community concern to the municipality.', 'keywords' => 'report issue concern complaint community'],
        ['title' => 'My request status', 'url' => 'pages/resident/requests.php', 'description' => 'Track the status of your service requests.', 'keywords' => 'request status track application'],
        ['title' => 'History of Bacolor', 'url' => 'main.php#history', 'description' => 'Learn about the history of the municipality.', 'keywords' => 'history heritage capital pampanga lahar pinatubo church'],
        ['title' => 'Bacolor launches iBacolor public service portal', 'url' => 'pages/news.php', 'description' => 'Announcement, September 18, 2026.', 'keywords' => 'portal launch announcement'],
        ['title' => 'Scheduled maintenance of municipal offices', 'url' => 'pages/news.php', 'description' => 'Advisory, September 12, 2026.', 'keywords' => 'maintenance offices hours advisory'],
        ['title' => 'Community clean-up drive this October', 'url' => 'pages/news.php', 'description' => 'Community, September 05, 2026.', 'keywords' => 'clean-up cleanup barangay environment'],
        ['title' => 'Public health and safety reminders', 'url' => 'pages/news.php', 'description' => 'Health, August 28, 2026.', 'keywords' => 'health safety rural health unit records'],
    ];
    foreach ($siteSearchIndex as $entry) {
        $haystack = $entry['title'] . ' ' . $entry['description'] . ' ' . $entry['keywords'];
        $allTermsMatch = true;
        foreach ($searchTerms as $term) {
            if (stripos($haystack, $term) === false) {
                $allTermsMatch = false;
                break;
            }
        }
        if ($allTermsMatch) {
            $searchPages[] = $entry;
        }
    }
}
$searchTotal = count($searchServices) + count($searchPages);
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>iBacolor | Municipal Public Service Portal</title>
        <link rel="stylesheet" href="css/pages/home.css" />
    </head>
    <body data-page="home">
        <div id="app">
            <?php include __DIR__ . '/includes/site-header.php'; ?>
            <main>
                <section class="hero">
                    <div class="container hero-grid">
                        <div class="hero-copy-block">
                            <p class="eyebrow">Municipality of Bacolor · Pampanga</p>
                            <h1>Bacolor’s services and updates, in one place.</h1>
                            <p class="hero-copy">Pay taxes, apply for permits, request assistance and report concerns with guidance from your municipal government.</p>
                            <form class="home-search" action="main.php#search-results" method="GET" role="search">
                                <label class="visually-hidden" for="home-service-search">Search municipal services, offices and requirements </label>
                                <span>
                                    <span class="icon" aria-hidden="true">⌕</span>
                                </span>
                                <input id="home-service-search" name="q" type="search" aria-label="Search municipal services, offices and requirements" placeholder="Search services, offices or requirements" value="<?= home_escape($searchQuery) ?>" maxlength="100" autocomplete="off"/>
                                <button type="submit" aria-label="Search">
                                    <span class="search-label">Search</span>
                                    <span class="icon" aria-hidden="true">→</span>
                                </button>
                            </form>
                            <div class="button-row">
                                <a class="button" href="pages/services.php">Browse services</a>
                                <a class="button button-outline" href="pages/resident/reports.php">Report an issue</a>
                            </div>
                        </div>
                        <aside class="hero-panel">
                            <div class="hero-panel-top">
                                <span class="hero-kicker">Your municipal guide</span>
                                <img src="assets/bacolor-seal.webp" alt="Municipality of Bacolor seal"/>
                            </div>
                            <h2>One municipality.<br/>Many ways to get help.</h2>
                            <p>Explore local services, important contacts and the latest municipal updates.</p>
                            <div class="hero-panel-links">
                                <a href="pages/hotlines.php">
                                    <span class="hero-link-icon">☎</span>
                                    <span>
                                        <small>Emergency contact</small>
                                        <strong>Smart: 0929-446-1940</strong>
                                    </span>
                                    <span aria-hidden="true">→</span>
                                </a>
                                <a href="pages/services.php">
                                    <span class="hero-link-icon">▦</span>
                                    <span>
                                        <small>Service directory</small>
                                        <strong>Browse municipal services</strong>
                                    </span>
                                    <span aria-hidden="true">→</span>
                                </a>
                            </div>
                        </aside>
                    </div>
                </section>
                <?php if ($searchQuery !== ''): ?>
                <section class="section search-results-section" id="search-results" aria-labelledby="search-results-title">
                    <div class="container">
                        <div class="section-heading">
                            <div>
                                <p class="eyebrow">Site search</p>
                                <h2 id="search-results-title">Results for “<?= home_escape($searchQuery) ?>”</h2>
                                <p class="section-intro"><?= $searchTotal === 0 ? 'No results found.' : $searchTotal . ($searchTotal === 1 ? ' result found.' : ' results found.') ?></p>
                            </div>
                            <a class="text-link" href="main.php">Clear search</a>
                        </div>
                        <?php if ($searchTotal === 0): ?>
                            <p class="empty-state">Nothing matched your search. Try a different keyword, or <a class="text-link" href="pages/services.php">browse all services</a>.</p>
                        <?php endif; ?>
                        <?php if ($searchServices): ?>
                            <div class="search-result-group">
                                <h3>Services</h3>
                                <ul class="search-result-list">
                                    <?php foreach ($searchServices as $result): ?>
                                        <li>
                                            <span class="tag"><?= home_escape($result['category']) ?></span>
                                            <h4><?= home_escape($result['name']) ?></h4>
                                            <p><?= home_escape($result['description']) ?></p>
                                            <a class="text-link" href="pages/services.php?service=<?= home_escape(rawurlencode((string) $result['code'])) ?>">View service details
                                                <span class="icon" aria-hidden="true">→</span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <?php if ($searchPages): ?>
                            <div class="search-result-group">
                                <h3>Pages and announcements</h3>
                                <ul class="search-result-list">
                                    <?php foreach ($searchPages as $result): ?>
                                        <li>
                                            <h4><?= home_escape($result['title']) ?></h4>
                                            <p><?= home_escape($result['description']) ?></p>
                                            <a class="text-link" href="<?= home_escape($result['url']) ?>">Open
                                                <span class="icon" aria-hidden="true">→</span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
                <?php endif; ?>
                <section class="section quick-services-section">
                    <div class="container">
                        <div class="section-heading">
                            <div>
                                <p class="eyebrow">Start here</p>
                                <h2>Quick services</h2>
                            </div>
                            <a class="text-link" href="pages/services.php">Browse all services
                                <span class="icon" aria-hidden="true">→</span>
                            </a>
                        </div>
                        <div class="quick-grid">
                            <a href="pages/services.php">
                                <span aria-hidden="true">▦</span>
                                <strong>Find a service</strong>
                                <small>Requirements, office and next steps</small>
                            </a>
                            <a href="pages/resident/reports.php">
                                <span aria-hidden="true">!</span>
                                <strong>Report an issue</strong>
                                <small>Tell us about a community concern</small>
                            </a>
                            <a href="pages/hotlines.php">
                                <span aria-hidden="true">☎</span>
                                <strong>Contact an office</strong>
                                <small>Find municipal and emergency contacts</small>
                            </a>
                            <a href="pages/news.php">
                                <span aria-hidden="true">▤</span>
                                <strong>Latest updates</strong>
                                <small>Read announcements and advisories</small>
                            </a>
                            <a href="pages/transparency.php">
                                <span aria-hidden="true">▧</span>
                                <strong>Transparency</strong>
                                <small>Public reports, budgets and projects</small>
                            </a>
                        </div>
                    </div>
                </section>
                <section class="section section-services">
                    <div class="container">
                        <div class="section-heading">
                            <div>
                                <p class="eyebrow">Municipal services</p>
                                <h2>Browse services</h2>
                                <p class="section-intro">Review the responsible office, requirements and application guidance before you begin.</p>
                            </div>
                            <a class="button button-outline" href="pages/services.php">View all services</a>
                        </div>
                        <div class="card-grid home-service-grid">
                            <?php if ($services): ?>
                                <?php foreach ($services as $service): ?>
                                    <article class="card home-service-card">
                                        <span class="tag"><?= htmlspecialchars($service['category'], ENT_QUOTES, 'UTF-8') ?></span>
                                        <h3><?= htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                                        <p><?= htmlspecialchars($service['description'], ENT_QUOTES, 'UTF-8') ?></p>
                                        <p class="service-office">
                                            <span>Responsible office</span>
                                            <strong><?= htmlspecialchars($service['office'], ENT_QUOTES, 'UTF-8') ?></strong>
                                        </p>
                                        <a class="text-link" href="pages/services.php?service=<?= htmlspecialchars(rawurlencode($service['code']), ENT_QUOTES, 'UTF-8') ?>">View service details
                                            <span class="icon" aria-hidden="true">→</span>
                                        </a>
                                    </article>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="empty-state">Services are being updated. Please check back soon.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
                <section class="section section-muted home-news-section">
                    <div class="container">
                        <div class="section-heading">
                            <div>
                                <p class="eyebrow">From the municipality</p>
                                <h2>News and announcements</h2>
                            </div>
                        <a class="button button-outline" href="pages/news.php">All updates</a>
                    </div>
                    <div class="card-grid card-grid-news">
                        <article class="card news-card news-card-1">
                            <span class="tag">Announcement</span>
                            <p class="meta">September 18, 2026</p>
                            <h3 id="news-1">Bacolor launches iBacolor public service portal</h3>
                            <p>Residents can now find municipal services, contact details, and request guidance in one place.</p>
                            <details class="news-details">
                                <summary>Read full announcement</summary>
                                <img class="announcement-image" src="assets/bacolor-church.png" alt="Bacolor church shown with the public service portal announcement" />
                                <p>The Municipality of Bacolor has launched a new public-facing portal to help residents understand available access points for government services, municipal offices, and emergency contact information. The portal is intended to make common transactions easier to locate and understand.</p>
                            </details>
                        </article>
                        <article class="card news-card news-card-2">
                            <span class="tag">Advisory</span>
                            <p class="meta">September 12, 2026</p>
                            <h3 id="news-2">Scheduled maintenance of municipal offices</h3>
                            <p>Selected offices will observe adjusted hours for internal systems maintenance and office servicing.</p>
                            <details class="news-details">
                                <summary>Read full announcement</summary>
                                <img class="announcement-image" src="assets/bacolor-church.png" alt="Bacolor church shown with the municipal office maintenance advisory"/>
                                <p>Selected municipal offices will adjust service hours during the scheduled maintenance period. Residents are encouraged to plan their transactions and contact the office in advance for urgent concerns.</p>
                            </details>
                        </article>
                        <article class="card news-card news-card-3">
                            <span class="tag">Community</span>
                            <p class="meta">September 05, 2026</p>
                            <h3 id="news-3">Community clean-up drive this October</h3>
                            <p>Join the municipal clean-up drive across participating barangays to keep public spaces safe and healthy.</p>
                            <details class="news-details">
                                <summary>Read full announcement</summary>
                                <img class="announcement-image" src="assets/bacolor-church.png" alt="Bacolor church shown with the community clean-up announcement" />
                                <p>The municipality is organizing a clean-up drive as part of its environmental and community safety programs. Barangays and residents are encouraged to participate and support public cleanliness initiatives.</p>
                            </details>
                        </article>
                        <article class="card news-card news-card-4">
                            <span class="tag">Health</span>
                            <p class="meta">August 28, 2026</p>
                            <h3 id="news-4">Public health and safety reminders</h3>
                            <p>Residents are reminded to coordinate with the Rural Health Unit for preventive services and health records.</p>
                            <details class="news-details">
                                <summary>Read full announcement</summary>
                                <img class="announcement-image" src="assets/bacolor-church.png" alt="Bacolor church shown with the public health announcement" />
                                <p>The Municipal Health Office encourages residents to keep updated with health services, preventive care, and public health advisories available through local facilities and barangay coordination.</p>
                            </details>
                        </article>
                    </div>
                </div>
            </section>
            <section class="section history-section" id="history" aria-labelledby="history-title">
                <div class="container history-layout">
                    <div class="history-copy">
                        <p class="eyebrow">Our heritage</p>
                        <h2 id="history-title">History of Bacolor</h2>
                        <p class="section-intro">Once the capital of Pampanga, Bacolor has long been a center of faith, governance and culture, and a community that rebuilt itself after the 1991 Mount Pinatubo lahar.</p>
                        <details class="history-details">
                            <summary class="button button-outline">Read more</summary>
                            <div class="history-more">
                                <p>Bacolor served as the capital of Pampanga from 1756 until the provincial seat moved to San Fernando in 1904. During the British occupation of Manila in 1762, the town became the base of Spanish resistance under Simón de Anda.</p>
                                <p>In the 1990s, lahar flows from Mount Pinatubo buried large parts of the town, including part of the San Guillermo Parish Church. Today the partly buried church stands as a reminder of the community’s resilience.</p>
                            </div>
                        </details>
                    </div>
                    <img class="history-image" src="assets/bacolor-church.png" alt="The Bacolor church, a landmark of the town’s history" loading="lazy" />
                </div>
            </section>
            <section class="section municipality-section">
                <div class="container municipality-layout">
                    <div>
                        <p class="eyebrow">A place with history</p>
                        <h2>Get to know Bacolor</h2>
                        <p>Find municipal information, important contacts and services for the 21 barangays of Bacolor.</p>
                        <a class="text-link" href="pages/hotlines.php">
                            Explore the municipal directory
                            <span class="icon" aria-hidden="true">→</span>
                        </a>
                    </div>
                    <div class="municipality-facts">
                        <div>
                            <strong>01</strong>
                            <span>21 barangays under the municipality</span>
                        </div>
                        <div>
                            <strong>02</strong>
                            <span>Located in the Province of Pampanga</span>
                        </div>
                    </div>
                </div>
            </section>
            <footer class="home-contact-strip">
                <div class="container contact-grid">
                    <div>
                        <p class="eyebrow">Need urgent assistance?</p>
                        <h2>Keep important contacts close.</h2>
                    </div>
                    <div>
                        <strong>Smart: 0929-446-1940</strong>
                        <span>Municipal Disaster Risk Reduction Management Office (MDRRMO - Bacolor)</span>
                    </div>
                    <div>
                        <a class="button button-light" href="pages/hotlines.php">View emergency contacts</a>
                    </div>
                </div>
            </footer>
        </main>
        <footer class="site-footer">
            <div class="container footer-grid">
                <div>
                    <a class="brand brand-footer" href="index.php">
                        <img class="brand-logo" src="assets/bacolor-seal.webp" alt="" />
                        <span>
                            <strong>iBacolor</strong>
                            <small>Municipal Public Service Portal</small>
                        </span>
                    </a>
                    <p>A simpler way to access municipal information and public services.</p>
                </div>
                <div>
                    <h3>Explore</h3>
                    <a href="pages/services.php">Services</a>
                    <a href="pages/news.php">News and announcements</a>
                    <a href="pages/hotlines.php">Emergency hotlines</a>
                    <a href="pages/transparency.php">Transparency</a>
                    <a href="pages/resident/reports.php">Community Report</a>
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
        <div class="cookie-banner" id="cookie-banner" role="region" aria-label="Cookie consent" hidden>
            <p>iBacolor uses cookies to keep you signed in and to improve your experience on this site.</p>
            <button class="button button-light" type="button" id="cookie-accept">Accept cookies</button>
        </div>
        <div class="modal" id="demo-modal" role="dialog" aria-modal="true" aria-hidden="true">
            <div class="modal-content">
                <button class="modal-close" type="button" aria-label="Close">
                    ×
                </button>
                <h2>iBacolor</h2>
                <p>This dialog is part of the frontend component foundation.</p>
            </div>
        </div>
        <nav class="bottom-nav" aria-label="Quick navigation">
            <a href="index.php">
                <span class="icon" aria-hidden="true">⌂</span>
                <span>Home</span>
            </a>
            <a href="pages/services.php">
                <span class="icon" aria-hidden="true">▦</span>
                <span>Services</span>
            </a>
            <a class="emergency-action" href="pages/hotlines.php">
                <span class="icon" aria-hidden="true">☎</span>
                <span>Emergency</span>
            </a>
            <a href="pages/news.php">
                <span class="icon" aria-hidden="true">▤</span>
                <span>Updates</span>
            </a>
            <a href="pages/login.php?next=resident/profile.php">
                <span class="icon" aria-hidden="true">◯</span>
                <span>Profile</span>
            </a>
        </nav>
    </div>

    <script src="js/helper.js"></script>
    <script src="js/navigation.js"></script>
    <script src="main.js"></script>
</body>
</html>