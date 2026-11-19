<?php require_once __DIR__ . '/../../includes/page-auth.php'; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Resident details | iBacolor</title>
        <link rel="stylesheet" href="../../css/pages/admin-user-details.css?v=resident-details-1" />
    </head>
    <body data-page="admin/user-details" class="admin-view">
        <div id="app">
            <aside class="admin-sidebar" id="admin-sidebar" aria-label="Admin menu" aria-hidden="true" hidden>
                <div class="admin-sidebar-header">
                    <button class="admin-menu-toggle admin-menu-close" type="button" aria-label="Close admin menu" aria-expanded="false" aria-controls="admin-sidebar"><span class="menu-icon" aria-hidden="true"><span></span><span></span><span></span></span></button>
                    <a class="admin-sidebar-brand" href="../admin/dashboard.php"><img class="brand-logo" src="../../assets/bacolor-seal.webp" alt="" /><span><strong>iBacolor</strong><small>Admin portal</small></span></a>
                </div>
                <p class="admin-label">Workspace</p>
                <a href="../admin/dashboard.php"><?= adminSidebarIcon('dashboard') ?>Dashboard</a><a href="../admin/requests.php"><?= adminSidebarIcon('clipboard-list') ?>Service requests</a><a href="../admin/documents.php"><?= adminSidebarIcon('file-text') ?>Documents</a><a href="../admin/reports.php"><?= adminSidebarIcon('alert-circle') ?>Community reports</a>
                <p class="admin-label">Content</p>
                <a href="../admin/news.php"><?= adminSidebarIcon('newspaper') ?>News</a><a href="../admin/transparency-documents.php"><?= adminSidebarIcon('file-search') ?>Transparency Documents</a><a href="../admin/services.php"><?= adminSidebarIcon('briefcase') ?>Services</a><a href="../admin/hotlines.php"><?= adminSidebarIcon('phone') ?>Hotlines</a><a href="../admin/users.php"><?= adminSidebarIcon('users') ?>Users</a>
                <p class="admin-label">System</p>
                <a href="../admin/history.php"><?= adminSidebarIcon('history') ?>History logs</a>
                <?php if (adminIsMasterAdmin($iBacolorConnection, (int) $accountId)): ?><a href="../admin/authorization.php"><?= adminSidebarIcon('shield-check') ?>Admin access</a><?php endif; ?>
                <div class="admin-sidebar-footer">
                    <a href="../logout.php"><?= adminSidebarIcon('logout') ?>Sign out</a>
                </div>
            </aside>
            <?php require __DIR__ . '/../../includes/site-header.php'; ?>
            <main>
                <section class="page-header">
                    <div class="container">
                        <div class="breadcrumbs">Administration <span>/</span><a href="users.php">Registered users</a><span>/</span>Resident details</div>
                        <p class="eyebrow">Administration</p>
                        <h1>Resident details</h1>
                        <p class="lead">Review the resident profile, recorded activity, service requests, and community reports.</p>
                    </div>
                </section>
                <section class="section">
                    <div class="container">
                        <p><a class="button button-outline" href="users.php">← Back to residents</a></p>
                        <p id="resident-details-error" class="notice notice-warning" role="alert" hidden></p>
                        <div id="resident-details-content" hidden>
                            <div class="resident-details-grid">
                                <article class="card">
                                    <h2 id="resident-name">Resident profile</h2>
                                    <dl class="detail-list" id="resident-profile-fields"></dl>
                                </article>
                                <article class="card">
                                    <h2>Verification history</h2>
                                    <div class="resident-timeline" id="resident-verification-history"></div>
                                </article>
                            </div>
                            <article class="card">
                                <h2>Activity history</h2>
                                <p>Recorded account, verification, service-request, and community-report updates.</p>
                                <div class="resident-timeline" id="resident-activity-history"></div>
                            </article>
                            <article class="card">
                                <h2>Service requests</h2>
                                <div id="resident-service-requests"></div>
                            </article>
                            <article class="card">
                                <h2>Community reports</h2>
                                <div id="resident-community-reports"></div>
                            </article>
                        </div>
                    </div>
                </section>
            </main>
            <footer class="site-footer">
                <div class="container footer-grid">
                    <div>
                        <div class="brand brand-footer"><img class="brand-logo" src="../../assets/bacolor-seal.webp" alt="" /><span><strong>iBacolor</strong><small>Administration portal</small></span></div>
                        <p>Administrative workspace for municipal operations.</p>
                    </div>
                    <div>
                        <h3>Explore</h3>
                        <a href="../services.php">Services</a>
                        <a href="../news.php">News and announcements</a>
                        <a href="../hotlines.php">Emergency hotlines</a>
                        <a href="../transparency.php">Transparency</a>
                        <a href="../resident/reports.php">Community Report</a>
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
                <div class="container footer-bottom">
                    <span>© 2026 Municipality of Bacolor</span>
                </div>
            </footer>
        </div>
        <script src="../../js/portal.js?v=admin-sidebar-6"></script>
        <script src="../../js/pages/admin-user-details.js?v=resident-details-1"></script>
    </body>
</html>
