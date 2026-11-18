<?php
declare(strict_types=1);

$headerScriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
$headerIsAdminPage = preg_match('~/pages/admin/[^/]+$~i', $headerScriptName) === 1;
if (preg_match('~/pages/(?:admin|resident)/[^/]+$~i', $headerScriptName) === 1) {
    $headerBasePath = '../../';
} elseif (preg_match('~/pages/[^/]+$~i', $headerScriptName) === 1) {
    $headerBasePath = '../';
} else {
    $headerBasePath = './';
}

$headerAccountId = filter_var($_SESSION['account_id'] ?? null, FILTER_VALIDATE_INT);
$headerRole = $_SESSION['role'] ?? '';
$headerIsAuthenticated = $headerAccountId !== false
    && $headerAccountId !== null
    && in_array($headerRole, ['resident', 'admin'], true);
$headerProfileName = '';
$headerEmail = '';
$headerDashboardHref = $headerBasePath . ($headerRole === 'admin' ? 'pages/admin/dashboard.php' : 'pages/resident/dashboard.php');

if ($headerIsAuthenticated) {
    require_once __DIR__ . '/connection.php';
    $profileNameStatement = $iBacolorConnection->prepare(
        "SELECT a.email, COALESCE(
            NULLIF(TRIM(CONCAT_WS(' ', NULLIF(rp.first_name, ''), NULLIF(rp.middle_name, ''), NULLIF(rp.last_name, ''))), ''),
            a.email
        ) AS display_name
        FROM accounts a
        LEFT JOIN resident_profiles rp ON rp.account_id = a.account_id
        WHERE a.account_id = :account_id
        LIMIT 1"
    );
    $profileNameStatement->execute(['account_id' => $headerAccountId]);
    $headerProfile = $profileNameStatement->fetch(PDO::FETCH_ASSOC);
    if ($headerProfile) {
        $headerProfileName = (string) $headerProfile['display_name'];
        $headerEmail = (string) $headerProfile['email'];
    }
}

?>
<header class="site-header">
    <div class="container header-inner">
        <?php if ($headerIsAdminPage): ?>
            <button class="menu-toggle admin-menu-toggle" aria-label="Open admin menu" aria-expanded="false" aria-controls="admin-sidebar" title="Open admin menu"><span class="menu-icon" aria-hidden="true"><span></span><span></span><span></span></span></button>
        <?php endif; ?>
        <a class="brand" href="<?= $headerIsAdminPage ? $headerBasePath . 'pages/admin/dashboard.php' : $headerBasePath . 'main.php' ?>">
            <img class="brand-logo" src="<?= $headerBasePath ?>assets/bacolor-seal.webp" alt="" />
            <span><strong>iBacolor</strong><small>Municipal Public Service Portal</small></span>
        </a>
        <?php if (!$headerIsAdminPage): ?>
            <button class="menu-toggle" aria-expanded="false" aria-controls="main-nav">Menu</button>
        <?php endif; ?>
        <div id="main-nav">
            <nav class="site-nav" aria-label="primary navigation">
                <?php if (!$headerIsAdminPage): ?>
                    <div class="nav-links">
                        <a href="<?= $headerBasePath ?>main.php">Home</a>
                        <a href="<?= $headerBasePath ?>pages/services.php">Services</a>
                        <a href="<?= $headerBasePath ?>pages/news.php">News</a>
                        <a href="<?= $headerBasePath ?>pages/hotlines.php">Emergency Hotlines</a>
                        <a href="<?= $headerBasePath ?>pages/transparency.php">Transparency</a>
                        <a href="<?= $headerBasePath ?>pages/resident/reports.php">Community Reports</a>
                        <a href="<?= $headerBasePath ?>pages/resident/requests.php">My Request Status</a>
                    </div>
                <?php endif; ?>
                <div class="header-actions">
                    <?php if ($headerIsAuthenticated): ?>
                        <details class="profile-menu">
                            <summary class="profile-link">
                                <span class="profile-name"><?= htmlspecialchars($headerProfileName, ENT_QUOTES, 'UTF-8') ?></span>
                                <img src="<?= $headerBasePath ?>assets/default-profile.svg" alt="" />
                                <span class="profile-caret" aria-hidden="true">▾</span>
                            </summary>
                            <div class="profile-dropdown">
                                <p><strong><?= htmlspecialchars($headerProfileName, ENT_QUOTES, 'UTF-8') ?></strong><small><?= htmlspecialchars($headerEmail, ENT_QUOTES, 'UTF-8') ?></small></p>
                                <?php if (!$headerIsAdminPage && $headerRole === 'resident'): ?>
                                    <a href="<?= $headerBasePath ?>pages/resident/profile.php">Profile</a>
                                <?php endif; ?>
                                <a href="<?= htmlspecialchars($headerDashboardHref, ENT_QUOTES, 'UTF-8') ?>">Dashboard</a>
                                <?php if ($headerRole === 'resident'): ?>
                                    <a href="<?= $headerBasePath ?>pages/resident/settings.php">Settings</a>
                                <?php endif; ?>
                                <a href="<?= $headerBasePath ?>pages/logout.php">Sign out</a>
                            </div>
                        </details>
                    <?php else: ?>
                        <a
                            id="resident-login-button"
                            class="button button-small"
                            href="<?= $headerBasePath ?>pages/login.php"
                            data-authenticated="false"
                            data-logout-href="<?= $headerBasePath ?>pages/logout.php"
                        >Resident sign in</a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </div>
</header>
