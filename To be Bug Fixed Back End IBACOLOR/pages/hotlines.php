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

    $officeIconPatterns = [
        'municipality of bacolor' => 'municipality',
        'general services' => 'maintenance',
        'social welfare' => 'welfare',
        'natural resources' => 'environment',
        'environment' => 'environment',
        'civil registry' => 'registry',
        'assessor' => 'property',
        'engineer' => 'engineering',
        'office of the mayor' => 'leadership',
        'mayor' => 'leadership',
        'treasurer' => 'treasury',
        'budget' => 'budget',
        'rural health' => 'health',
        'health unit' => 'health',
        'planning and development' => 'planning',
        'agriculture' => 'agriculture',
    ];
    $officeIconLabels = [
        'agriculture' => 'Agriculture and crops',
        'property' => 'Property assessment',
        'registry' => 'Civil registry documents',
        'engineering' => 'Drafting ruler',
        'environment' => 'Environment and natural resources',
        'leadership' => 'Municipal leadership',
        'budget' => 'Municipal budget',
        'treasury' => 'Treasury and payments',
        'municipality' => 'Municipality of Bacolor',
        'maintenance' => 'General services and maintenance',
        'health' => 'Rural health care',
        'planning' => 'Planning and development',
        'welfare' => 'Helping hands and community support',
        'building' => 'Municipal office',
    ];
    $getEmergencyIcon = static function (string $name): ?string {
        $name = strtolower($name);
        if (strpos($name, 'pnp bacolor') !== false) {
            return 'police';
        }
        if (strpos($name, 'fire department bacolor') !== false) {
            return 'fire';
        }

        return null;
    };
    $getOfficeIcon = static function (string $officeName) use ($officeIconPatterns): string {
        $officeName = strtolower($officeName);
        foreach ($officeIconPatterns as $pattern => $icon) {
            if (strpos($officeName, $pattern) !== false) {
                return $icon;
            }
        }

        return 'building';
    };
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Emergency Hotlines | iBacolor</title>
        <link rel="stylesheet" href="../css/pages/hotlines.css?v=4" />
    </head>
    <body data-page="hotlines">
        <div id="app">
            <?php require __DIR__ . '/../includes/site-header.php'; ?>
            <main>
                <svg class="office-icon-library" aria-hidden="true" focusable="false">
                    <symbol id="office-icon-agriculture" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 21v-8m0 1c-3.1 0-5-2-5-5 3 0 5 2 5 5Zm0-3c0-3 2-5 5-5 0 3-2 5-5 5ZM4 21h16" />
                    </symbol>
                    <symbol id="office-icon-property" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="5" y="4" width="14" height="17" rx="2" />
                        <path d="M9 4.5h6v3H9zM9 12h6M9 16h6" />
                        <path d="m7.5 12 .5.5.8-1m-.8 4 .5.5.8-1" />
                    </symbol>
                    <symbol id="office-icon-registry" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 3.5h8l4 4V20H6zM14 3.5V8h4M9 12h6M9 15h6M9 18h4" />
                        <path d="m15 17 1 1 2-2" />
                    </symbol>
                    <symbol id="office-icon-engineering" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 17.5 17.5 3 21 6.5 6.5 21H3z" />
                        <path d="m8 16 2 2m1-5 2 2m1-5 2 2m1-5 2 2" />
                    </symbol>
                    <symbol id="office-icon-environment" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 21v-7m0 0c-4.5 0-7-2.8-7-7 4.2 0 7 2.5 7 7Zm0-3c0-4.5 2.8-7 7-7 0 4.2-2.5 7-7 7Zm-8 10h16" />
                    </symbol>
                    <symbol id="office-icon-leadership" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m3 9 9-5 9 5H3ZM5 20h14M7 10v9m5-9v9m5-9v9M4 22h16" />
                    </symbol>
                    <symbol id="office-icon-budget" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3v9h9A9 9 0 1 1 12 3Z" />
                        <path d="M16 3.8A9 9 0 0 1 20.2 8H16z" />
                    </symbol>
                    <symbol id="office-icon-treasury" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="6" width="18" height="14" rx="2" />
                        <path d="M3 9h18m-6 4h6v4h-6a2 2 0 0 1 0-4Z" />
                        <circle cx="17" cy="15" r=".7" />
                    </symbol>
                    <symbol id="office-icon-municipality" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 21h16M6 21V9h12v12M4 9l8-5 8 5M9 12h2m2 0h2m-6 3h2m2 0h2m-5 6v-3h2v3M12 4V2m0 0 4 1-4 1" />
                    </symbol>
                    <symbol id="office-icon-maintenance" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 6a5 5 0 0 0-6 6l-4.5 4.5a2.1 2.1 0 0 0 3 3L11 15a5 5 0 0 0 6-6l-3 3-3-3 3-3Z" />
                    </symbol>
                    <symbol id="office-icon-health" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 21s-8-4.5-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 6.5-8 11-8 11Z" />
                        <path d="M12 8v7m-3.5-3.5h7" />
                    </symbol>
                    <symbol id="office-icon-planning" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 20V5m0 15h17M7 16l4-4 3 2 6-7m-4 0h4v4" />
                    </symbol>
                    <symbol id="office-icon-welfare" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 12 6.8 7.2A3 3 0 0 1 11 3l1 1 1-1a3 3 0 0 1 4.2 4.2Z" />
                        <path d="M2.5 14.5h3l3.2 2.7h5.1l4.8-3a1.7 1.7 0 0 1 2 2.8l-4.8 3.3H9l-6.5-3.5z" />
                        <path d="m5.5 14.5 2.2-2a2.1 2.1 0 0 1 2.8 0l1.5 1.3h2.5" />
                    </symbol>
                    <symbol id="office-icon-building" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 21h16M6 21V4h12v17M9 8h2m2 0h2m-6 4h2m2 0h2m-6 4h2m2 0h2m-5 5v-3h2v3" />
                    </symbol>
                    <symbol id="emergency-icon-police" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3 20 6v5c0 5-3.4 8.2-8 10-4.6-1.8-8-5-8-10V6z" />
                        <path d="m12 7 1.2 2.4 2.7.4-2 1.9.5 2.7-2.4-1.3-2.4 1.3.5-2.7-2-1.9 2.7-.4z" />
                    </symbol>
                    <symbol id="emergency-icon-fire" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13.5 3c.7 4-2.8 5-2.1 8.2-1.4-.7-2.2-2-2.3-3.6C6.5 10 5 12.5 5 15a7 7 0 0 0 14 0c0-4.4-2.7-8.4-5.5-12Z" />
                        <path d="M12.2 13.2c.4 1.8-1.5 2.5-1.5 4a2.3 2.3 0 0 0 4.6 0c0-1.6-1.3-3.1-3.1-4Z" />
                    </symbol>
                </svg>
                <section class="page-header">
                    <div class="container">
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
                                <?php $emergencyIcon = $getEmergencyIcon((string) $contact['name']); ?>
                                <article class="card hotline-card">
                                    <div class="hotline-icon">
                                        <?php if ($emergencyIcon !== null): ?>
                                            <svg class="hotline-card-icon" viewBox="0 0 24 24" role="img" aria-label="<?= $emergencyIcon === 'police' ? 'Police shield' : 'Fire safety' ?>">
                                                <use href="#emergency-icon-<?= htmlspecialchars($emergencyIcon, ENT_QUOTES, 'UTF-8') ?>"></use>
                                            </svg>
                                        <?php else: ?>
                                            <span class="icon" aria-hidden="true">☎</span>
                                        <?php endif; ?>
                                    </div>
                                    <h3><?= htmlspecialchars($contact['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                                    <span class="tag"><?= $isVerified ? 'Verified' : 'Unverified' ?></span>
                                    <?php if (!empty($contact['contact_name'])): ?>
                                        <p><strong>Head:</strong> <?= htmlspecialchars($contact['contact_name'], ENT_QUOTES, 'UTF-8') ?></p>
                                    <?php endif; ?>
                                    <p><strong>Hotline Numbers:</strong> <?= htmlspecialchars($contact['telephone'] ?: 'Not provided', ENT_QUOTES, 'UTF-8') ?></p>
                                    <?php if (!empty($contact['email'])): ?>
                                        <p><strong>Email:</strong> <?= htmlspecialchars($contact['email'], ENT_QUOTES, 'UTF-8') ?></p>
                                    <?php endif; ?>
                                    <p><strong>Location:</strong> <?= htmlspecialchars($contact['address'] ?: 'Not provided', ENT_QUOTES, 'UTF-8') ?></p>
                                    <?php $contactPurpose = $getContactPurpose($contact['purpose'] ?? null); ?>
                                    <?php if ($contactPurpose): ?>
                                        <p><strong>Services:</strong></p>
                                        <ul class="compact-list">
                                            <?php foreach ($contactPurpose as $purposeItem): ?>
                                                <li><?= htmlspecialchars($purposeItem, ENT_QUOTES, 'UTF-8') ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                    <?php foreach ($getContactLinks($contact) as $label => $url): ?>
                                        <p><strong><?= $label === 'Facebook' ? 'Social Media' : htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>:</strong> <a href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer"><?= $label === 'Facebook' ? 'Facebook' : 'Open link' ?></a></p>
                                    <?php endforeach; ?>
                                </article>
                            <?php endforeach; ?>
                        </div>
                        <?php if (!$emergencyContacts): ?>
                            <div class="empty-state"><strong>No emergency contacts are listed yet.</strong><p>Please contact the municipality directly for assistance.</p></div>
                        <?php endif; ?>
                        <div class="section-heading hotline-section-heading">
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
                                <?php $officeIcon = $getOfficeIcon((string) $contact['name']); ?>
                                <article class="card office-card">
                                    <div class="hotline-icon">
                                        <svg class="office-card-icon" viewBox="0 0 24 24" role="img" aria-label="<?= htmlspecialchars($officeIconLabels[$officeIcon], ENT_QUOTES, 'UTF-8') ?>">
                                            <use href="#office-icon-<?= htmlspecialchars($officeIcon, ENT_QUOTES, 'UTF-8') ?>"></use>
                                        </svg>
                                    </div>
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
                        <div class="section-heading hotline-section-heading">
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
