<?php require_once __DIR__ . '/../../includes/page-auth.php'; ?>
<!doctype html>
<html lang="en" class="admin-access-page">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Admin Access | iBacolor</title>
    <link rel="stylesheet" href="../../css/pages/admin-authorization.css?v=admin-authorization-8" />
</head>
<body data-page="admin/authorization" class="admin-view">
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
            <a href="../admin/authorization.php" aria-current="page">⚙ Admin access</a>
            <div class="admin-sidebar-footer">
                <a href="../logout.php">Sign out</a>
            </div>
        </aside>
        <?php require __DIR__ . '/../../includes/site-header.php'; ?>
        <main>
            <section class="page-header">
                <div class="container">
                    <div class="breadcrumbs">Administration <span>/</span>Admin access</div>
                    <p class="eyebrow">Master Admin</p>
                    <h1>Administrator access</h1>
                    <p class="lead">Create admin accounts, assign department roles, and control role permissions.</p>
                </div>
            </section>
            <section class="section">
                <div class="container authorization-layout">
                    <p id="authorization-message" class="authorization-message" role="status" hidden></p>

                    <article class="card authorization-card">
                        <div class="section-heading compact">
                            <div>
                                <p class="eyebrow">Provisioning</p>
                                <h2>Create administrator</h2>
                            </div>
                        </div>
                        <form id="admin-create-form" class="authorization-form">
                            <label>Email address
                                <input name="email" type="email" maxlength="100" autocomplete="off" required />
                            </label>
                            <label>Temporary password
                                <input name="password" type="password" minlength="12" maxlength="1024" autocomplete="new-password" required />
                                <small>Use at least 12 characters. Share it securely and have the admin change it after signing in.</small>
                            </label>
                            <fieldset>
                                <legend>Department roles</legend>
                                <div id="create-role-options" class="role-options"><p>Loading roles…</p></div>
                            </fieldset>
                            <button class="button" type="submit">Create admin account</button>
                        </form>
                    </article>

                    <article class="card authorization-card">
                        <div class="section-heading compact">
                            <div>
                                <p class="eyebrow">Accounts</p>
                                <h2>Current administrators</h2>
                            </div>
                        </div>
                        <div class="table-wrap">
                            <table class="authorization-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Account</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Department roles</th>
                                        <th scope="col">Access controls</th>
                                    </tr>
                                </thead>
                                <tbody id="admin-accounts">
                                    <tr><td colspan="4">Loading admin accounts…</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </article>

                    <article class="card authorization-card">
                        <div class="section-heading compact">
                            <div>
                                <p class="eyebrow">Permission matrix</p>
                                <h2>Role permissions</h2>
                            </div>
                        </div>
                        <label class="role-select-label" for="permission-role">Choose a role to edit
                            <select id="permission-role"></select>
                        </label>
                        <p class="authorization-help">Changes take effect immediately for every administrator assigned to the selected role. Master Admin permissions are fixed.</p>
                        <h3>Global permissions</h3>
                        <div id="global-permissions" class="global-permissions"><p>Loading permissions…</p></div>
                        <h3>Service-scoped permissions</h3>
                        <p class="authorization-help">Grant access only to the selected services. Request updates and document review are controlled separately.</p>
                        <div class="table-wrap">
                            <table class="authorization-table service-permission-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Service</th>
                                        <th scope="col">View requests</th>
                                        <th scope="col">Update requests</th>
                                        <th scope="col">Review documents</th>
                                    </tr>
                                </thead>
                                <tbody id="service-permissions">
                                    <tr><td colspan="4">Loading service permissions…</td></tr>
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
                <div><h3>Administration</h3><p>Manage administrator access and permissions.</p></div>
                <div><h3>Need assistance?</h3><p>Municipal Hall<br />(045) 900-1000<br />Mon–Fri, 8:00 AM–5:00 PM</p></div>
            </div>
            <div class="container footer-bottom"><span>© 2026 Municipality of Bacolor</span></div>
        </footer>
    </div>
    <script src="../../js/portal.js?v=admin-sidebar-6"></script>
    <script src="../../js/pages/admin-authorization.js?v=admin-authorization-2"></script>
</body>
</html>
