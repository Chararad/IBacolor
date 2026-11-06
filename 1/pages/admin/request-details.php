<?php require_once __DIR__ . '/../../includes/page-auth.php'; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Admin Request Details | iBacolor</title>
        <link rel="stylesheet" href="../../css/pages/admin-request-details.css" />
    </head>
    <body data-page="admin/request-details" class="admin-view">
        <div id="app">
            <aside class="admin-sidebar">
                <a class="admin-sidebar-brand" href="../admin/dashboard.php"><img class="brand-logo" src="../../assets/bacolor-seal.webp" alt="" /><span><strong>iBacolor</strong><small>Admin portal</small></span></a>
                <p class="admin-label">Workspace</p>
                <a href="../admin/dashboard.php">▦ Dashboard</a><a href="../admin/requests.php">▤ Service requests</a><a href="../admin/documents.php">▧ Documents</a><a href="../admin/reports.php">! Community reports</a>
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
                            <a href="../../main.php">Home</a><span>/</span>Requests / Details
                        </div>
                        <p class="eyebrow">Administration</p>
                        <h1>Request details</h1>
                        <p class="lead">Review the resident request and update its processing status.</p>
                    </div>
                </section>
                <section class="section">
                    <div class="container content-grid">
                        <div class="stack">
                            <article class="card">
                                <div class="detail-heading">
                                    <div>
                                        <p class="eyebrow">Reference number</p>
                                        <h2>IB-2026-00124</h2>
                                    </div>
                                    <span class="status status-under-review">Under Review</span>
                                </div>
                                <dl class="detail-list">
                                    <div>
                                        <dt>Applicant</dt>
                                        <dd>Maria Santos · Cabambangan (Pob.)</dd>
                                    </div>
                                    <div>
                                        <dt>Service</dt>
                                        <dd>Business Permit</dd>
                                    </div>
                                    <div>
                                        <dt>Submitted</dt>
                                        <dd>September 20, 2026</dd>
                                    </div>
                                </dl>
                            </article>
                            <article class="card">
                                <h2>Review notes</h2>
                                <textarea rows="5" placeholder="Add an internal note for this request"></textarea>
                                <div class="button-row">
                                    <button class="button" type="button">Save note</button><button class="button button-outline" type="button">
                                        Request revision
                                    </button>
                                </div>
                            </article>
                        </div>
                        <aside class="stack">
                            <article class="card">
                                <h3>Update status</h3>
                                <select>
                                    <option>Under Review</option>
                                    <option>Pending</option>
                                    <option>For Revision</option>
                                    <option>Completed</option>
                                    <option>Rejected</option></select><button class="button button-block" type="button">
                                    Update status
                                </button>
                            </article>
                            <article class="card">
                                <h3>Documents</h3>
                                <div class="file-row">
                                    <span><span class="icon" aria-hidden="true">▧</span> Business
                                        Permit Application</span><span class="status status-accepted">Accepted</span>
                                </div>
                                <div class="file-row">
                                    <span><span class="icon" aria-hidden="true">▧</span> Valid
                                        Government ID</span><span class="status status-accepted">Accepted</span>
                                </div>
                            </article>
                        </aside>
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
        <script src="../../js/pages/admin-request-details.js"></script>
</body>
</html>
