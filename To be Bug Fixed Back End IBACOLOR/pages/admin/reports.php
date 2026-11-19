<?php require_once __DIR__ . '/../../includes/page-auth.php'; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Manage Reports | iBacolor</title>
        <link rel="stylesheet" href="../../css/pages/admin-reports.css?v=admin-sidebar-12" />
    </head>
    <body data-page="admin/reports" class="admin-view">
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
                        <div class="breadcrumbs">
                            Administration <span>/</span>Reports
                        </div>
                        <p class="eyebrow">Administration</p>
                        <h1>Community reports</h1>
                        <p class="lead">Review resident issues and route reports to the responsible department.</p>
                    </div>
                </section>
                <section class="section">
                    <div class="container">
                        <article class="card">
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Report</th>
                                            <th>Category</th>
                                            <th>Location</th>
                                            <th>Submitted</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="admin-reports-list">
                                        <tr><td colspan="6">Loading reports...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </article>
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
                    </div>
                </div>
                <div class="container footer-bottom">
                    <span>© 2026 Municipality of Bacolor</span>
                </div>
            </footer>
            <div class="modal" id="demo-modal" role="dialog" aria-modal="true" aria-hidden="true">
                <div class="modal-content">
                    <button class="modal-close" type="button" aria-label="Close">
                        ×
                    </button>
                    <h2>iBacolor</h2>
                    <p>This dialog is part of the frontend component foundation.</p>
                </div>
            </div>
        </div>
    
        <script src="../../js/portal.js?v=admin-sidebar-6"></script>
        <script src="../../js/pages/admin-reports.js"></script>
</body>
</html>
