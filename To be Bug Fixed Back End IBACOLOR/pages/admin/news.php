<?php require_once __DIR__ . '/../../includes/page-auth.php'; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Manage News | iBacolor</title>
        <link rel="stylesheet" href="../../css/pages/admin-news.css" />
    </head>
    <body data-page="admin/news" class="admin-view">
        <div id="app">
            <aside class="admin-sidebar">
                <a class="admin-sidebar-brand" href="../admin/dashboard.php"><img class="brand-logo" src="../../assets/bacolor-seal.webp" alt="" /><span><strong>iBacolor</strong><small>Admin portal</small></span></a>
                <p class="admin-label">Workspace</p>
                <a href="../admin/dashboard.php">▦ Dashboard</a><a href="../admin/requests.php">▤ Service requests</a><a href="../admin/documents.php">▧ Documents</a><a href="../admin/reports.php">! Community reports</a>
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
                            <a href="../../main.php">Home</a><span>/</span>News management
                        </div>
                        <p class="eyebrow">Administration</p>
                        <h1>News management</h1>
                        <p class="lead">Maintain the public information displayed in the iBacolor portal.</p>
                    </div>
                </section>
                <section class="section">
                    <div class="container">
                        <article class="card">
                            <div class="section-heading compact">
                                <h2>Published news</h2>
                                <button class="button" id="add-news-article" type="button">+ Add new</button>
                            </div>
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="admin-news-list">
                                        <tr><td colspan="4">Loading news articles...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </article>
                        <form class="form-card" id="admin-news-form" hidden>
                            <h2 id="news-form-heading">Add news article</h2>
                            <input name="articleId" type="hidden" />
                            <label>Title<input name="title" maxlength="255" required /></label>
                            <label>Announcement type<select name="category" required><option value="">Select announcement type</option><option value="Legal and Regulatory Notices">Legal and Regulatory Notices</option><option value="Government and Administrative Announcements">Government and Administrative Announcements</option><option value="Public Safety and Emergency Advisories">Public Safety and Emergency Advisories</option><option value="Community and Cultural Events">Community and Cultural Events</option></select></label>
                            <label>Article content<textarea name="content" rows="8" required></textarea></label>
                            <label>Publication status<select name="status"><option value="draft">Draft</option><option value="published">Published</option></select></label>
                            <label>Publication date and time<input name="publishedAt" type="datetime-local" /></label>
                            <label>Featured image<input name="image" type="file" accept="image/jpeg,image/png,image/webp" /></label>
                            <img id="news-image-preview" alt="Featured image preview" hidden />
                            <p id="news-form-message" class="notice notice-info" role="status" hidden></p>
                            <div class="form-actions">
                                <button class="button" type="submit">Save article</button>
                                <button class="button button-outline" id="cancel-news-edit" type="button">Cancel</button>
                            </div>
                        </form>
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
        <script src="../../js/pages/admin-news.js"></script>
</body>
</html>
