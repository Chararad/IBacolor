<?php require_once __DIR__ . '/../../includes/page-auth.php'; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>My Requests | iBacolor</title>
        <link rel="stylesheet" href="../../css/pages/resident-requests.css" />
    </head>
    <body data-page="resident/requests">
        <div id="app">
            <?php require __DIR__ . '/../../includes/site-header.php'; ?>
            <main>
                <section class="page-header">
                    <div class="container">
                        <p class="eyebrow">Resident portal</p>
                        <h1>My requests</h1>
                        <p class="lead">Track the status of your submitted municipal service requests.</p>
                    </div>
                </section>
                <section class="section">
                    <div class="container">
                        <article class="card">
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Reference number</th>
                                            <th>Service</th>
                                            <th>Submitted</th>
                                            <th>Last updated</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="resident-request-list"></tbody>
                                </table>
                            </div>
                            <p id="resident-request-empty" class="empty-state" hidden>You have not submitted any service requests yet.</p>
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
                        <a href="../services.php">Services</a>
                        <a href="../news.php">News and announcements</a>
                        <a href="../hotlines.php">Emergency hotlines</a>
                        <a href="../transparency.php">Transparency</a>
                        <a href="reports.php">Community Report</a>
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
        <script src="../../js/pages/resident-requests.js"></script>
</body>
</html>
