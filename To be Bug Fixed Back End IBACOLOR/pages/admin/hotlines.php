<?php require_once __DIR__ . '/../../includes/page-auth.php'; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Manage Hotlines | iBacolor</title>
        <link rel="stylesheet" href="../../css/pages/admin-hotlines.css?v=hotline-toolbar-layout-2" />
    </head>
    <body data-page="admin/hotlines" class="admin-view">
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
                            Administration <span>/</span>Hotlines
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
                            <div class="hotline-management-heading">
                                <h2>Published hotlines</h2>
                            </div>
                            <div class="hotline-toolbar-actions">
                                <label class="hotline-search">Search
                                    <input id="hotline-search" type="search" placeholder="Office, contact, or details" />
                                </label>
                                <button class="button" id="add-hotline-button" type="button">+ Add hotline</button>
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
                                                <button class="button button-small button-outline hotline-edit-button" type="button">
                                                    Edit</button><button class="button button-small button-outline hotline-delete-button" type="button">
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
                                                <button class="button button-small button-outline hotline-edit-button" type="button">
                                                    Edit</button><button class="button button-small button-outline hotline-delete-button" type="button">
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
                                                <button class="button button-small button-outline hotline-edit-button" type="button">
                                                    Edit</button><button class="button button-small button-outline hotline-delete-button" type="button">
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
                                                <button class="button button-small button-outline hotline-edit-button" type="button">
                                                    Edit</button><button class="button button-small button-outline hotline-delete-button" type="button">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                        <tr id="hotline-search-empty" hidden><td colspan="4">No hotlines match your search.</td></tr>
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
            <div class="modal" id="hotline-editor-modal" role="dialog" aria-modal="true" aria-labelledby="hotline-editor-title" aria-hidden="true">
                <form class="modal-content hotline-editor-content" id="hotline-editor-form">
                    <button class="modal-close" type="button" aria-label="Close hotline editor" data-close-hotline-editor>×</button>
                    <h2 id="hotline-editor-title">Add hotline</h2>
                    <p class="hotline-editor-intro">Enter the official contact details shown in the public directory.</p>
                    <p class="notice notice-warning hotline-editor-error" id="hotline-editor-error" role="alert" hidden></p>
                    <input type="hidden" name="contactId" />
                    <div class="hotline-editor-fields">
                        <label>Office or hotline name
                            <input name="name" maxlength="255" required />
                        </label>
                        <label>Contact type
                            <select name="type" required>
                                <option value="hotline">Hotline</option>
                                <option value="emergency">Emergency</option>
                                <option value="office">Office</option>
                            </select>
                        </label>
                        <label>Telephone number(s)
                            <input name="telephone" maxlength="100" />
                        </label>
                        <label>Email
                            <input name="email" type="email" maxlength="255" />
                        </label>
                        <label class="hotline-editor-wide">Address or service description
                            <textarea name="address" rows="2" maxlength="255"></textarea>
                        </label>
                        <label>Directory category
                            <input name="category" maxlength="255" />
                        </label>
                        <label>Official contact person
                            <input name="contact_name" maxlength="255" />
                        </label>
                        <label class="hotline-editor-wide">Services or purposes <small>Separate items with semicolons.</small>
                            <textarea name="purpose" rows="3" maxlength="2000"></textarea>
                        </label>
                        <label>Other HTTPS social link
                            <input name="social_link" type="url" pattern="https://.*" maxlength="255" title="Enter a valid HTTPS URL." />
                        </label>
                        <label>Facebook page HTTPS URL
                            <input name="fb_page_link" type="url" pattern="https://.*" maxlength="255" title="Enter a valid HTTPS URL." />
                        </label>
                        <label>Website HTTPS URL
                            <input name="website_link" type="url" pattern="https://.*" maxlength="255" title="Enter a valid HTTPS URL." />
                        </label>
                        <label>Official social media HTTPS URL
                            <input name="official_socmed" type="url" pattern="https://.*" maxlength="255" title="Enter a valid HTTPS URL." />
                        </label>
                    </div>
                    <label class="hotline-verified-field"><input name="is_verified" type="checkbox" /> These contact details have been officially verified</label>
                    <div class="form-actions">
                        <button class="button" type="submit">Save hotline</button>
                        <button class="button button-outline" type="button" data-close-hotline-editor>Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    
        <script src="../../js/portal.js?v=admin-sidebar-6"></script>
        <script src="../../js/pages/admin-hotlines.js?v=hotline-editor-modal-1"></script>
</body>
</html>
