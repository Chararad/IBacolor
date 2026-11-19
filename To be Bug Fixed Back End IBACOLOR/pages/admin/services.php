<?php require_once __DIR__ . '/../../includes/page-auth.php'; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Manage Services | iBacolor</title>
        <link rel="stylesheet" href="../../css/pages/admin-services.css?v=service-availability-filter-3" />
    </head>
    <body data-page="admin/services" class="admin-view">
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
                            Administration <span>/</span>Services
                            management
                        </div>
                        <p class="eyebrow">Administration</p>
                        <h1>Services management</h1>
                        <p class="lead">Maintain the public information displayed in the iBacolor portal.</p>
                    </div>
                </section>
                <section class="section">
                    <div class="container">
                        <article class="card">
                            <div class="section-heading compact services-list-heading">
                                <h2>Published services</h2>
                            </div>
                            <div class="services-list-toolbar">
                                <div class="services-list-filters" role="group" aria-label="Filter services">
                                    <div class="services-category-filter">
                                        <label for="admin-services-category">Category
                                            <select id="admin-services-category">
                                                <option value="">All categories</option>
                                            </select>
                                        </label>
                                    </div>
                                    <div class="services-availability-filter">
                                        <label for="admin-services-availability">Availability
                                            <select id="admin-services-availability">
                                                <option value="">All availability</option>
                                                <option value="Available">Available</option>
                                                <option value="Unavailable">Unavailable</option>
                                                <option value="In review">In review</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <button class="button" id="add-service" type="button">+ Add service</button>
                            </div>
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Service</th>
                                            <th>Category</th>
                                            <th>Availability</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="admin-services-list">
                                        <tr>
                                            <td><strong>Business Permit</strong></td>
                                            <td>Business</td>
                                            <td>
                                                <span class="status status-available">Available</span>
                                            </td>
                                            <td class="table-actions">
                                                <button class="button button-small button-outline" type="button">
                                                    Edit</button><button class="button button-small button-ghost" type="button">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Civil Registry Certificate</strong></td>
                                            <td>Civil Registry</td>
                                            <td>
                                                <span class="status status-available">Available</span>
                                            </td>
                                            <td class="table-actions">
                                                <button class="button button-small button-outline" type="button">
                                                    Edit</button><button class="button button-small button-ghost" type="button">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Barangay Clearance</strong></td>
                                            <td>Community</td>
                                            <td>
                                                <span class="status status-available">Available</span>
                                            </td>
                                            <td class="table-actions">
                                                <button class="button button-small button-outline" type="button">
                                                    Edit</button><button class="button button-small button-ghost" type="button">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Building Permit</strong></td>
                                            <td>Engineering</td>
                                            <td>
                                                <span class="status status-in-review">In review</span>
                                            </td>
                                            <td class="table-actions">
                                                <button class="button button-small button-outline" type="button">
                                                    Edit</button><button class="button button-small button-ghost" type="button">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Community Tax Certificate</strong></td>
                                            <td>Finance</td>
                                            <td>
                                                <span class="status status-available">Available</span>
                                            </td>
                                            <td class="table-actions">
                                                <button class="button button-small button-outline" type="button">
                                                    Edit</button><button class="button button-small button-ghost" type="button">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Health Certificate</strong></td>
                                            <td>Health</td>
                                            <td>
                                                <span class="status status-available">Available</span>
                                            </td>
                                            <td class="table-actions">
                                                <button class="button button-small button-outline" type="button">
                                                    Edit</button><button class="button button-small button-ghost" type="button">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Social Assistance</strong></td>
                                            <td>Social Welfare</td>
                                            <td>
                                                <span class="status status-available">Available</span>
                                            </td>
                                            <td class="table-actions">
                                                <button class="button button-small button-outline" type="button">
                                                    Edit</button><button class="button button-small button-ghost" type="button">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Senior / PWD / Women and Children Assistance</strong>
                                            </td>
                                            <td>Social Welfare</td>
                                            <td>
                                                <span class="status status-available">Available</span>
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
            <div class="modal" id="service-editor-modal" role="dialog" aria-modal="true" aria-labelledby="service-form-heading" aria-hidden="true">
                <form class="modal-content form-card service-editor-modal" id="service-form">
                    <button class="modal-close" type="button" aria-label="Close service editor" data-close-service-editor>×</button>
                    <h2 id="service-form-heading">Add service</h2>
                    <input name="serviceId" type="hidden" />
                    <div class="form-grid service-form-grid">
                        <label>Service name<input name="name" maxlength="255" required /></label>
                        <label>Service code<input name="code" maxlength="50" required /></label>
                        <label>Category
                            <select id="service-category" required>
                                <option value="">Select a category</option>
                                <option value="__add_category__">Add a new category…</option>
                            </select>
                        </label>
                        <label id="new-service-category-field" hidden>New category
                            <input id="new-service-category" maxlength="255" />
                        </label>
                        <label>Responsible office<input name="office" maxlength="255" required /></label>
                        <label>Status
                            <select name="status" required>
                                <option value="Available">Available</option>
                                <option value="Unavailable">Unavailable</option>
                                <option value="In review">In review</option>
                            </select>
                        </label>
                        <label class="service-form-wide">Public description<textarea name="description" rows="4" maxlength="500" required></textarea></label>
                    </div>
                    <details class="service-optional-details">
                        <summary>Additional details (optional)</summary>
                        <div class="form-grid service-form-grid">
                            <label>Fee information<input name="fee_information" maxlength="255" /></label>
                            <label>Processing information<textarea name="processing_info" rows="3" maxlength="255"></textarea></label>
                            <label class="service-form-wide">Instructions<textarea name="instructions" rows="3" maxlength="255"></textarea></label>
                        </div>
                    </details>
                    <fieldset class="service-requirements">
                        <legend>Required documents or prerequisites (optional)</legend>
                        <div id="service-requirement-list" class="service-requirement-list"></div>
                        <button class="button button-outline button-small" id="add-service-requirement" type="button">+ Add requirement</button>
                    </fieldset>
                    <p id="service-form-message" class="notice notice-info" role="alert" hidden></p>
                    <div class="form-actions">
                        <button class="button" type="submit">Save service</button>
                        <button class="button button-outline" type="button" data-close-service-editor>Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    
        <script src="../../js/portal.js?v=admin-sidebar-6"></script>
        <script src="../../js/pages/admin-services.js?v=service-availability-filter-1"></script>
</body>
</html>
