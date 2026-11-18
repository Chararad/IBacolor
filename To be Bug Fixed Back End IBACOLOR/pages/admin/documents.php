<?php require_once __DIR__ . '/../../includes/page-auth.php'; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Documents | iBacolor</title>
        <link rel="stylesheet" href="../../css/pages/admin-documents.css?v=document-review-dropdown-2" />
    </head>
    <body data-page="admin/documents" class="admin-view">
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
                            Administration <span>/</span>Documents
                        </div>
                        <p class="eyebrow">Administration</p>
                        <h1>Document review</h1>
                        <p class="lead">Review documents attached to resident service requests.</p>
                    </div>
                </section>
                <section class="section">
                    <div class="container">
                        <article class="card">
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Document</th>
                                            <th>Owner</th>
                                            <th>Type</th>
                                            <th>Uploaded</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="admin-documents-list">
                                        <tr><td colspan="6">Loading documents...</td></tr>
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
            <div class="modal" id="document-review-modal" role="dialog" aria-modal="true" aria-labelledby="document-review-title" aria-hidden="true">
                <form class="modal-content document-review-content" id="document-review-form">
                    <button class="modal-close" type="button" aria-label="Close document review" data-close-document-review>×</button>
                    <h2 id="document-review-title">Review document</h2>
                    <p id="document-review-name"></p>
                    <input type="hidden" name="documentId" />
                    <label>Review outcome
                        <select name="status" id="document-review-status" required>
                            <option value="submitted">Submitted</option>
                            <option value="accepted">Accepted</option>
                            <option value="needs correction">Needs correction</option>
                        </select>
                    </label>
                    <label>Review note
                        <textarea name="note" id="document-review-note" rows="4" maxlength="2000"></textarea>
                    </label>
                    <p class="form-help" id="document-review-note-help">A note is required if the document needs correction.</p>
                    <p class="notice notice-info" id="document-review-message" role="alert" hidden></p>
                    <div class="form-actions">
                        <button class="button" type="submit">Save review</button>
                        <button class="button button-outline" type="button" data-close-document-review>Cancel</button>
                    </div>
                </form>
            </div>
            <div class="modal" id="document-preview-modal" role="dialog" aria-modal="true" aria-labelledby="document-preview-title" aria-hidden="true">
                <section class="modal-content document-preview-content">
                    <header class="document-preview-header">
                        <div class="document-preview-heading">
                            <h2 id="document-preview-title">Document preview</h2>
                            <p id="document-preview-details"></p>
                        </div>
                        <div class="document-preview-actions">
                            <a class="button button-outline" id="document-preview-download" href="#">Download</a>
                            <button class="button button-outline" type="button" data-close-document-preview>Close</button>
                        </div>
                    </header>
                    <p class="notice notice-info" id="document-preview-unavailable" role="status" hidden>
                        Preview is not available for this file type. Download the file to open it.
                    </p>
                    <iframe id="document-preview-frame" title="Document preview" hidden></iframe>
                    <img id="document-preview-image" alt="" hidden />
                </section>
            </div>
        </div>
    
        <script src="../../js/portal.js?v=admin-sidebar-6"></script>
        <script src="../../js/pages/admin-documents.js?v=document-review-dropdown-1"></script>
</body>
</html>
