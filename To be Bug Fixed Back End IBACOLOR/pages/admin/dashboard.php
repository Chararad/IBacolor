<?php require_once __DIR__ . '/../../includes/page-auth.php'; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Admin Dashboard | iBacolor</title>
        <link rel="stylesheet" href="../../css/pages/admin-dashboard.css?v=admin-dashboard-links-1" />
    </head>
    <body data-page="admin/dashboard" class="admin-view">
        <div id="app">
            <aside class="admin-sidebar" id="admin-sidebar" aria-label="Admin menu" aria-hidden="true" hidden>
                <div class="admin-sidebar-header">
                    <button class="admin-menu-toggle admin-menu-close" type="button" aria-label="Close admin menu" aria-expanded="false" aria-controls="admin-sidebar"><span class="menu-icon" aria-hidden="true"><span></span><span></span><span></span></span></button>
                    <a class="admin-sidebar-brand" href="../admin/dashboard.php"><img class="brand-logo" src="../../assets/bacolor-seal.webp" alt="" /><span><strong>iBacolor</strong><small>Admin portal</small></span></a>
                </div>
                <p class="admin-label">Workspace</p>
                <a href="../admin/dashboard.php" aria-current="page">▦ Dashboard</a><a href="../admin/requests.php">▤ Service requests</a><a href="../admin/documents.php">▧ Documents</a><a href="../admin/reports.php">! Community reports</a>
                <p class="admin-label">Content</p>
                <a href="../admin/news.php">✦ News</a><a href="../admin/transparency-documents.php">▤ Transparency Documents</a><a href="../admin/services.php">▣ Services</a><a href="../admin/hotlines.php">☎ Hotlines</a><a href="../admin/users.php">◯ Users</a>
                <div class="admin-sidebar-footer">
                    <a href="../logout.php">Sign out</a>
                </div>
            </aside>
            <?php require __DIR__ . '/../../includes/site-header.php'; ?>
            <main>
                <section class="page-header">
                    <div class="container">
                        <div class="breadcrumbs">
                            Administration <span>/</span>Admin Dashboard
                        </div>
                        <p class="eyebrow">Administration</p>
                        <h1>Municipal operations dashboard</h1>
                        <p class="lead">Monitor requests, reports, content, and resident activity.</p>
                    </div>
                </section>
                <section class="section">
                    <div class="container">
                        <div class="stats-grid">
                            <a class="card stat-card" href="requests.php">
                                <p class="stat-label">Service requests</p>
                                <strong class="stat-value">4</strong>
                                <p class="stat-note">All requests</p>
                            </a>
                            <a class="card stat-card" href="requests.php?status=In%20Progress">
                                <p class="stat-label">In progress</p>
                                <strong class="stat-value">2</strong>
                                <p class="stat-note">Current requests</p>
                            </a>
                            <a class="card stat-card" href="reports.php">
                                <p class="stat-label">Open reports</p>
                                <strong class="stat-value">2</strong>
                                <p class="stat-note">Needs attention</p>
                            </a>
                            <a class="card stat-card" href="users.php">
                                <p class="stat-label">Residents</p>
                                <strong class="stat-value">3</strong>
                                <p class="stat-note">Registered residents</p>
                            </a>
                        </div>
                        <div class="content-grid">
                            <div>
                                <article class="card">
                                    <div class="section-heading compact">
                                        <div>
                                            <p class="eyebrow">Latest activity</p>
                                            <h2>Recent requests</h2>
                                        </div>
                                        <a class="button button-outline button-small" href="requests.php">Manage requests</a>
                                    </div>
                                    <div class="table-wrap">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Reference</th>
                                                    <th>Applicant</th>
                                                    <th>Service</th>
                                                    <th>Status</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><strong>IB-2026-00124</strong></td>
                                                    <td>Maria Santos</td>
                                                    <td>Business Permit</td>
                                                    <td>
                                                        <span class="status status-under-review">Under Review</span>
                                                    </td>
                                                    <td>
                                                        <a class="text-link" href="request-details.php">View</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>IB-2026-00118</strong></td>
                                                    <td>Maria Santos</td>
                                                    <td>Barangay Clearance</td>
                                                    <td>
                                                        <span class="status status-resolved">Resolved</span>
                                                    </td>
                                                    <td>
                                                        <a class="text-link" href="request-details.php">View</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>IB-2026-00105</strong></td>
                                                    <td>Jose Dela Cruz</td>
                                                    <td>Civil Registry Certificate</td>
                                                    <td>
                                                        <span class="status status-submitted">Submitted</span>
                                                    </td>
                                                    <td>
                                                        <a class="text-link" href="request-details.php">View</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>IB-2026-00098</strong></td>
                                                    <td>Ana Reyes</td>
                                                    <td>Building Permit</td>
                                                    <td>
                                                        <span class="status status-in-progress">In Progress</span>
                                                    </td>
                                                    <td>
                                                        <a class="text-link" href="request-details.php">View</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </article>
                            </div>
                            <aside class="stack">
                                <article class="card">
                                    <p class="eyebrow">Content</p>
                                    <h3>Keep public information current.</h3>
                                    <div class="action-list">
                                        <a href="news.php"><span class="icon" aria-hidden="true">+</span> Manage
                                            announcements</a><a href="services.php"><span class="icon" aria-hidden="true">+</span> Update
                                            services</a><a href="hotlines.php"><span class="icon" aria-hidden="true">+</span> Manage
                                            contacts</a>
                                    </div>
                                </article>
                            </aside>
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
                        <h3>Administration</h3>
                        <p>Manage resident services and municipal content.</p>
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
        <script src="../../js/pages/admin-dashboard.js"></script>
</body>
</html>
