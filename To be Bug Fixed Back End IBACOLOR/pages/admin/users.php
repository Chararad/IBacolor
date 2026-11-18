<?php require_once __DIR__ . '/../../includes/page-auth.php'; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Users | iBacolor</title>
        <link rel="stylesheet" href="../../css/pages/admin-users.css?v=admin-users-row-profile-5" />
    </head>
    <body data-page="admin/users" class="admin-view">
        <div id="app">
            <aside class="admin-sidebar" id="admin-sidebar" aria-label="Admin menu" aria-hidden="true" hidden>
                <div class="admin-sidebar-header">
                    <button class="admin-menu-toggle admin-menu-close" type="button" aria-label="Close admin menu" aria-expanded="false" aria-controls="admin-sidebar"><span class="menu-icon" aria-hidden="true"><span></span><span></span><span></span></span></button>
                    <a class="admin-sidebar-brand" href="../admin/dashboard.php"><img class="brand-logo" src="../../assets/bacolor-seal.webp" alt="" /><span><strong>iBacolor</strong><small>Admin portal</small></span></a>
                </div>
                <p class="admin-label">Workspace</p>
                <a href="../admin/dashboard.php">▦ Dashboard</a><a href="../admin/requests.php">▤ Service requests</a><a href="../admin/documents.php">▧ Documents</a><a href="../admin/reports.php">! Community reports</a>
                <p class="admin-label">Content</p>
                <a href="../admin/news.php">✦ News</a><a href="../admin/transparency-documents.php">▤ Transparency Documents</a><a href="../admin/services.php">▣ Services</a><a href="../admin/hotlines.php">☎ Hotlines</a><a href="../admin/users.php">◯ Users</a>
                <p class="admin-label">System</p>
                <a href="../admin/history.php">◷ History logs</a>
                <?php if (adminIsMasterAdmin($iBacolorConnection, (int) $accountId)): ?><a href="../admin/authorization.php">⚙ Admin access</a><?php endif; ?>
                <div class="admin-sidebar-footer">
                    <a href="../logout.php">Sign out</a>
                </div>
            </aside>
            <?php require __DIR__ . '/../../includes/site-header.php'; ?>
            <main>
                <section class="page-header">
                    <div class="container">
                        <div class="breadcrumbs">
                            Administration <span>/</span>Users
                        </div>
                        <p class="eyebrow">Administration</p>
                        <h1>Registered users</h1>
                        <p class="lead">Manage resident portal accounts and verification status.</p>
                    </div>
                </section>
                <section class="section">
                    <div class="container">
                        <article class="card">
                            <div class="user-filters">
                                <label>Search residents
                                    <input id="resident-search" type="search" placeholder="Name, email, or barangay" />
                                </label>
                                <label>Account status
                                    <select id="account-status-filter">
                                        <option value="">All account statuses</option>
                                        <option value="active">Active</option>
                                        <option value="pending">Pending</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
                                </label>
                                <label>Verification status
                                    <select id="verification-status-filter">
                                        <option value="">All verification statuses</option>
                                        <option value="pending">Pending</option>
                                        <option value="verified">Verified</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </label>
                            </div>
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Resident</th>
                                            <th>Email</th>
                                            <th>Barangay</th>
                                            <th>Joined</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="6">Loading residents…</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <p id="users-filter-empty" role="status" hidden>No residents match these filters.</p>
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
            <div class="modal" id="resident-review-modal" role="dialog" aria-modal="true" aria-labelledby="resident-review-title" aria-describedby="resident-review-description" aria-hidden="true">
                <div class="modal-content">
                    <button class="modal-close" type="button" aria-label="Close resident review dialog" data-close-resident-review>
                        ×
                    </button>
                    <h2 id="resident-review-title">Review resident</h2>
                    <p id="resident-review-description">Choose a verification status for this resident.</p>
                    <form id="resident-review-form">
                        <label for="resident-review-status">Verification status</label>
                        <select id="resident-review-status" name="verification" required>
                            <option value="pending">Pending</option>
                            <option value="verified">Verified</option>
                            <option value="rejected">Rejected</option>
                        </select>
                        <label for="resident-review-note">Review remarks <span id="resident-review-note-hint">(optional)</span></label>
                        <textarea id="resident-review-note" name="note" rows="4" maxlength="2000"></textarea>
                        <p id="resident-review-error" class="notice notice-warning" role="alert" hidden></p>
                        <div class="form-actions">
                            <button class="button button-outline" type="button" data-close-resident-review>Cancel</button>
                            <button class="button" id="resident-review-save" type="submit">Save review</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal" id="account-status-modal" role="dialog" aria-modal="true" aria-labelledby="account-status-title" aria-describedby="account-status-description" aria-hidden="true">
                <div class="modal-content">
                    <button class="modal-close" type="button" aria-label="Close account status dialog" data-close-account-status>
                        ×
                    </button>
                    <h2 id="account-status-title">Update account status</h2>
                    <p id="account-status-description">Choose the account status for this resident.</p>
                    <form id="account-status-form">
                        <label for="account-status-select">Account status</label>
                        <select id="account-status-select" name="status" required>
                            <option value="active">Active</option>
                            <option value="pending">Pending (for review)</option>
                            <option value="suspended">Suspended (inactive)</option>
                        </select>
                        <p id="account-status-error" class="notice notice-warning" role="alert" hidden></p>
                        <div class="form-actions">
                            <button class="button button-outline" type="button" data-close-account-status>Cancel</button>
                            <button class="button" id="account-status-save" type="submit">Save status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    
        <script src="../../js/portal.js?v=admin-sidebar-6"></script>
        <script src="../../js/pages/admin-users.js?v=resident-row-profile-3"></script>
</body>
</html>
