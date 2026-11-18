<?php require_once __DIR__ . '/../../includes/page-auth.php'; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Manage Requests | iBacolor</title>
        <link rel="stylesheet" href="../../css/pages/admin-requests.css" />
    </head>
    <body data-page="admin/requests" class="admin-view">
        <div id="app">
            <aside class="admin-sidebar">
                <a class="admin-sidebar-brand" href="../admin/dashboard.php"><img class="brand-logo" src="../../assets/bacolor-seal.webp" alt="" /><span><strong>iBacolor</strong><small>Admin portal</small></span></a>
                <p class="admin-label">Workspace</p>
                <a href="../admin/dashboard.php">▦ Dashboard</a><a href="../admin/requests.php" aria-current="page">▤ Service requests</a><a href="../admin/documents.php">▧ Documents</a><a href="../admin/reports.php">! Community reports</a>
                <p class="admin-label">Content</p>
                <a href="../admin/news.php">✦ News</a><a href="../admin/transparency-documents.php">▤ Transparency Documents</a><a href="../admin/services.php">▣ Services</a><a href="../admin/hotlines.php">☎ Hotlines</a><a href="../admin/users.php">◯ Users</a>
                <div class="admin-sidebar-footer">
                    <a href="../../main.php">← Public website</a>
                </div>
            </aside>
            <?php require __DIR__ . '/../../includes/site-header.php'; ?>
            <main>
                <section class="page-header">
                    <div class="container">
                        <div class="breadcrumbs">
                            <a href="../../main.php">Home</a><span>/</span><a href="dashboard.php">Dashboard</a><span>/</span>Manage requests
                        </div>
                        <p class="eyebrow">Administration</p>
                        <h1>Manage requests</h1>
                        <p class="lead">Work through the request queue: see what needs action, update statuses, and open a request to review documents or schedule an appointment.</p>
                    </div>
                </section>
                <section class="section">
                    <div class="container">
                        <div class="queue-tabs" role="group" aria-label="Request queue">
                            <button class="queue-tab" type="button" data-queue="needs-action" aria-pressed="true">
                                <span>Needs action</span><strong data-queue-count="needs-action">0</strong>
                            </button>
                            <button class="queue-tab" type="button" data-queue="in-progress" aria-pressed="false">
                                <span>In progress</span><strong data-queue-count="in-progress">0</strong>
                            </button>
                            <button class="queue-tab" type="button" data-queue="completed" aria-pressed="false">
                                <span>Completed</span><strong data-queue-count="completed">0</strong>
                            </button>
                            <button class="queue-tab" type="button" data-queue="rejected" aria-pressed="false">
                                <span>Rejected</span><strong data-queue-count="rejected">0</strong>
                            </button>
                            <button class="queue-tab" type="button" data-queue="all" aria-pressed="false">
                                <span>All requests</span><strong data-queue-count="all">0</strong>
                            </button>
                        </div>
                        <article class="card">
                            <div class="filter-bar">
                                <label>Search this view<input id="admin-request-search" type="search" placeholder="Reference, resident or service" />
                                </label>
                            </div>
                            <p id="admin-request-feedback" class="notice notice-info" role="status" hidden></p>
                            <p id="admin-request-count" class="results-count" aria-live="polite"></p>
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Reference</th>
                                            <th>Applicant</th>
                                            <th>Service</th>
                                            <th>Submitted</th>
                                            <th>Status</th>
                                            <th>Update status</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody id="admin-request-list">
                                        <tr><td colspan="7">Loading requests...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <p class="form-help">To reject a request, open it with Manage &mdash; a rejection needs a written reason.</p>
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
        <script src="../../js/pages/admin-requests.js"></script>
</body>
</html>
