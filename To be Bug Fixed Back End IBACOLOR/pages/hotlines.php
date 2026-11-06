<?php
    session_start();
    $isAuthenticated = isset($_SESSION['account_id']) && in_array($_SESSION['role'] ?? '', ['resident', 'admin'], true);
    require_once '../includes/connection.php';

    $contactsQuery = 'SELECT name, `type`, telephone, email, address, is_verified, social_link, contact_name, purpose, category, fb_page_link, website_link, official_socmed FROM public_contacts ORDER BY `type`, category, name';
    $contactsStatement = $iBacolorConnection->prepare($contactsQuery);
    $contactsStatement->execute();
    $contacts = $contactsStatement->fetchAll(PDO::FETCH_ASSOC);

    $emergencyContacts = [];
    $officeContacts = [];

    foreach ($contacts as $contact) {
        $contactType = strtolower(trim((string) $contact['type']));
        $contactCategory = strtolower(trim((string) ($contact['category'] ?? '')));
        if (in_array($contactType, ['emergency', 'hotline'], true) || $contactCategory === 'emergency') {
            $emergencyContacts[] = $contact;
        } else {
            $officeContacts[] = $contact;
        }
    }

    $barangaysQuery = 'SELECT name FROM barangays ORDER BY name';
    $barangaysStatement = $iBacolorConnection->prepare($barangaysQuery);
    $barangaysStatement->execute();
    $barangays = $barangaysStatement->fetchAll(PDO::FETCH_COLUMN);

    $getContactLinks = static function (array $contact): array {
        $links = [];
        $linkFields = [
            'Social' => 'social_link',
            'Facebook' => 'fb_page_link',
            'Website' => 'website_link',
            'Official social media' => 'official_socmed',
        ];

        foreach ($linkFields as $label => $field) {
            $url = trim((string) ($contact[$field] ?? ''));
            if ($url === '' || filter_var($url, FILTER_VALIDATE_URL) === false) {
                continue;
            }

            $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
            if ($scheme === 'https' && !in_array($url, $links, true)) {
                $links[$label] = $url;
            }
        }

        return $links;
    };

    $getContactPurpose = static function (?string $purpose): array {
        if ($purpose === null || trim($purpose) === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', preg_split('/[;\r\n]+/', $purpose))));
    };
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Emergency Hotlines | iBacolor</title>
        <link rel="stylesheet" href="../css/pages/hotlines.css" />
    </head>
    <body data-page="hotlines">
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
                        <div class="breadcrumbs"><a href="../main.php">Home</a><span>/</span>Emergency Hotlines</div>
                        <p class="eyebrow">Help when you need it</p>
                        <h1>Emergency hotlines</h1>
                        <p class="lead">For immediate danger, call the appropriate office below. Keep these contacts within reach.</p>
                    </div>
                </section>
                <section class="section">
                    <div class="container">
                        <div class="notice notice-warning">
                            <span class="icon" aria-hidden="true">!</span>
                            <strong>For life-threatening emergencies, call the appropriate emergency service immediately.</strong>
                        </div>
                        <div class="section-heading">
                            <div>
                                <p class="eyebrow">Emergency contacts</p>
                                <h2>Official response offices</h2>
                            </div>
                        </div>
                        <div class="hotline-grid">
                            <?php foreach ($emergencyContacts as $contact): ?>
                                <?php $isVerified = in_array(strtolower(trim((string) $contact['is_verified'])), ['1', 'true', 'yes', 'verified'], true); ?>
                                <article class="card hotline-card">
                                    <div class="hotline-icon"><span class="icon" aria-hidden="true">☎</span></div>
                                    <h3><?= htmlspecialchars($contact['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                                    <span class="tag"><?= $isVerified ? 'Verified' : 'Unverified' ?></span>
                                    <?php if (!empty($contact['contact_name'])): ?>
                                        <p><strong>Contact:</strong> <?= htmlspecialchars($contact['contact_name'], ENT_QUOTES, 'UTF-8') ?></p>
                                    <?php endif; ?>
                                    <p><strong>Telephone:</strong> <?= htmlspecialchars($contact['telephone'] ?: 'Not provided', ENT_QUOTES, 'UTF-8') ?></p>
                                    <p><strong>Email:</strong> <?= htmlspecialchars($contact['email'] ?: 'Not provided', ENT_QUOTES, 'UTF-8') ?></p>
                                    <p><strong>Location:</strong> <?= htmlspecialchars($contact['address'] ?: 'Not provided', ENT_QUOTES, 'UTF-8') ?></p>
                                    <?php $contactPurpose = $getContactPurpose($contact['purpose'] ?? null); ?>
                                    <?php if ($contactPurpose): ?>
                                        <ul class="compact-list">
                                            <?php foreach ($contactPurpose as $purposeItem): ?>
                                                <li><?= htmlspecialchars($purposeItem, ENT_QUOTES, 'UTF-8') ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                    <?php foreach ($getContactLinks($contact) as $label => $url): ?>
                                        <p><strong><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>:</strong> <a href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">Open link</a></p>
                                    <?php endforeach; ?>
                                </article>
                            <?php endforeach; ?>
                        </div>
                        <?php if (!$emergencyContacts): ?>
                            <div class="empty-state"><strong>No emergency contacts are listed yet.</strong><p>Please contact the municipality directly for assistance.</p></div>
                        <?php endif; ?>
                        <div class="section-heading">
                            <div>
                                <p class="eyebrow">Municipal office directory</p>
                                <h2>Office contacts</h2>
                                <p class="section-intro">Unverified records are marked. Confirm contact details with the municipality before relying on them.</p>
                                <p class="section-intro">For offices without a verified direct contact, call <a href="tel:+63459001000">(045) 900-1000</a>.</p>
                            </div>
                        </div>
                        <div class="hotline-grid" id="office-directory">
                            <?php foreach ($officeContacts as $contact): ?>
                                <?php $isVerified = in_array(strtolower(trim((string) $contact['is_verified'])), ['1', 'true', 'yes', 'verified'], true); ?>
                                <article class="card office-card">
                                    <div class="hotline-icon"><span class="icon" aria-hidden="true">▣</span></div>
                                    <h3><?= htmlspecialchars($contact['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                                    <p class="meta"><?= htmlspecialchars($contact['category'] ?: $contact['type'], ENT_QUOTES, 'UTF-8') ?></p>
                                    <span class="tag"><?= $isVerified ? 'Verified' : 'Unverified' ?></span>
                                    <?php if (!empty($contact['contact_name'])): ?>
                                        <p><strong>Official:</strong> <?= htmlspecialchars($contact['contact_name'], ENT_QUOTES, 'UTF-8') ?></p>
                                    <?php endif; ?>
                                    <p><strong>Telephone:</strong> <?= htmlspecialchars($contact['telephone'] ?: 'Not provided', ENT_QUOTES, 'UTF-8') ?></p>
                                    <p><strong>Email:</strong> <?= htmlspecialchars($contact['email'] ?: 'Not provided', ENT_QUOTES, 'UTF-8') ?></p>
                                    <p><strong>Location:</strong> <?= htmlspecialchars($contact['address'] ?: 'Not provided', ENT_QUOTES, 'UTF-8') ?></p>
                                    <?php $contactPurpose = $getContactPurpose($contact['purpose'] ?? null); ?>
                                    <?php if ($contactPurpose): ?>
                                        <ul class="compact-list">
                                            <?php foreach ($contactPurpose as $purposeItem): ?>
                                                <li><?= htmlspecialchars($purposeItem, ENT_QUOTES, 'UTF-8') ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                    <?php foreach ($getContactLinks($contact) as $label => $url): ?>
                                        <p><strong><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>:</strong> <a href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">Open link</a></p>
                                    <?php endforeach; ?>
                                </article>
                            <?php endforeach; ?>
                        </div>
                        <?php if (!$officeContacts): ?>
                            <div class="empty-state"><strong>No office contacts are listed yet.</strong><p>Please contact the municipality directly for assistance.</p></div>
                        <?php endif; ?>
                        <div class="section-heading">
                            <div>
                                <p class="eyebrow">Local community contacts</p>
                                <h2>Barangay contacts</h2>
                                <p class="section-intro">Select your barangay and contact its barangay hall for local assistance. Contact details are shown only after official verification.</p>
                            </div>
                        </div>
                        <div class="hotline-grid barangay-contact-grid" id="barangay-contacts">
                            <?php foreach ($barangays as $barangayName): ?>
                                <article class="card office-card barangay-contact-card">
                                    <div class="hotline-icon"><span class="icon" aria-hidden="true">⌂</span></div>
                                    <h3>Barangay <?= htmlspecialchars((string) $barangayName, ENT_QUOTES, 'UTF-8') ?></h3>
                                    <p>Contact the barangay hall for local assistance. Phone and social media details are awaiting official confirmation.</p>
                                </article>
                            <?php endforeach; ?>
                        </div>
                        <?php if (!$barangays): ?>
                            <div class="empty-state"><strong>No barangays are listed yet.</strong><p>Barangay contact cards will appear after barangays are added to the database.</p></div>
                        <?php endif; ?>
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
            <div class="modal" id="demo-modal" role="dialog" aria-modal="true" aria-hidden="true">
                <div class="modal-content">
                    <button class="modal-close" type="button" aria-label="Close">×</button>
                    <h2>iBacolor</h2>
                    <p>This dialog is part of the frontend component foundation.</p>
                </div>
            </div>
        </div>
        <script src="../js/navigation.js"></script>
        <script src="../js/helper.js"></script>
    </body>
</html>
