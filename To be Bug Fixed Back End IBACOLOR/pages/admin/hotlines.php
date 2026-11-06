<?php require_once __DIR__ . '/../../includes/page-auth.php'; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Manage Hotlines | iBacolor</title>
        <link rel="stylesheet" href="../../css/pages/admin-hotlines.css" />
    </head>
    <body data-page="admin/hotlines" class="admin-view">
        <div id="app">
            <aside class="admin-sidebar">
                <a class="admin-sidebar-brand" href="../admin/dashboard.php"><img class="brand-logo" src="../../assets/bacolor-seal.webp" alt="" /><span><strong>iBacolor</strong><small>Admin portal</small></span></a>
                <p class="admin-label">Workspace</p>
                <a href="../admin/dashboard.php">▦ Dashboard</a><a href="../admin/requests.php">▤ Service requests</a><a href="../admin/documents.php">▧ Documents</a><a href="../admin/reports.php">! Community reports</a>
                <a href="../resident/dashboard.php">Resident portal</a>
                <p class="admin-label">Content</p>
                <a href="../admin/news.php">✦ News</a><a href="../admin/services.php">▣ Services</a><a href="../admin/hotlines.php">☎ Hotlines</a><a href="../admin/users.php">◯ Users</a>
                <div class="admin-sidebar-footer">
                    <a href="../../main.php">← Public website</a>
                </div>
            </aside>
            <header class="site-header">
                <div class="container header-inner">
                    <a class="brand" href="../../main.php"><img class="brand-logo" src="../../assets/bacolor-seal.webp" alt="" /><span><strong>iBacolor</strong><small>Municipal Public Service Portal</small></span></a><button class="menu-toggle" aria-expanded="false" aria-controls="main-nav">
                        Menu
                    </button>
                    <div id="main-nav">
                        <nav class="site-nav" aria-label="admin navigation">
                            <div class="nav-links">
                                <a href="../admin/dashboard.php">Dashboard</a><a href="../admin/requests.php">Requests</a><a href="../admin/reports.php">Reports</a><a href="../admin/documents.php">Documents</a><a href="../admin/users.php">Users</a>
                                <div class="nav-group">
                                    <span>Content management</span><a href="../admin/news.php">News</a><a href="../admin/services.php">Services</a><a href="../admin/hotlines.php">Hotlines</a>
                                </div>
                            </div>
                            <a class="button button-small button-outline" href="../../main.php">Sign out</a>
                        </nav>
                    </div>
                </div>
            </header>
            <main>
                <section class="page-header">
                    <div class="container">
                        <div class="breadcrumbs">
                            <a href="../../main.php">Home</a><span>/</span>Hotlines
                            management
                        </div>
                        <p class="eyebrow">Administration</p>
                        <h1>Hotlines management</h1>
                        <p class="lead">Maintain the public information displayed in the iBacolor portal.</p>
                    </div>
                </section>
                <section class="section">
                    <div class="container">
                        <article class="card">
                            <div class="section-heading compact">
                                <h2>Published hotlines</h2>
                                <button class="button" type="button">+ Add hotline</button>
                            </div>
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Office</th>
                                            <th>Contact</th>
                                            <th>Description</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Municipality of Bacolor</strong></td>
                                            <td>(045) 900-1000</td>
                                            <td>General municipal inquiries and office directory.</td>
                                            <td class="table-actions">
                                                <button class="button button-small button-outline" type="button">
                                                    Edit</button><button class="button button-small button-ghost" type="button">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>MDRRMO - Bacolor</strong></td>
                                            <td>Smart: 0929-446-1940 | Globe: 0966-136-5586</td>
                                            <td>
                                                Disaster response, rescue operations, emergency
                                                coordination.
                                            </td>
                                            <td class="table-actions">
                                                <button class="button button-small button-outline" type="button">
                                                    Edit</button><button class="button button-small button-ghost" type="button">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Bacolor Police Station</strong></td>
                                            <td>(045) 901-0833</td>
                                            <td>
                                                Public safety, police assistance, and traffic matters.
                                            </td>
                                            <td class="table-actions">
                                                <button class="button button-small button-outline" type="button">
                                                    Edit</button><button class="button button-small button-ghost" type="button">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Bacolor Fire Station</strong></td>
                                            <td>(045) 436-1828 | 0923-103-8363</td>
                                            <td>
                                                Fire prevention, rescue response, and emergency fire
                                                assistance.
                                            </td>
                                            <td class="table-actions">
                                                <button class="button button-small button-outline" type="button">
                                                    Edit</button><button class="button button-small button-ghost" type="button">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
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
                        <a class="brand brand-footer" href="../../main.php"><img class="brand-logo" src="../../assets/bacolor-seal.webp" alt="" /><span><strong>iBacolor</strong><small>Municipal Public Service Portal</small></span></a>
                        <p>A simpler way to access municipal information and public services.</p>
                    </div>
                    <div>
                        <h3>Explore</h3>
                        <a href="../services.php">Services</a><a href="../news.php">News and announcements</a><a href="../hotlines.php">Emergency hotlines</a>
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
    
        <script src="../../js/portal.js"></script>
        <script src="../../js/pages/admin-hotlines.js"></script>
</body>
</html>
