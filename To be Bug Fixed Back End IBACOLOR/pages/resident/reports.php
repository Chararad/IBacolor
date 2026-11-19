<?php require_once __DIR__ . '/../../includes/page-auth.php'; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Community Reports | iBacolor</title>
        <link rel="stylesheet" href="../../css/pages/resident-reports.css" />
    </head>
    <body data-page="resident/reports">
        <div id="app">
            <?php require __DIR__ . '/../../includes/site-header.php'; ?>
            <main>
                <section class="page-header">
                    <div class="container">
                        <p class="eyebrow">Resident portal</p>
                        <h1>Community reports</h1>
                        <p class="lead">Report a local issue and follow up on concerns submitted to the municipality.</p>
                    </div>
                </section>
                <section class="section">
                    <div class="container content-grid">
                        <form class="form-card" id="community-report-form">
                            <p class="eyebrow">New report</p>
                            <h2>Report an issue</h2>
                            <label>Category<span class="category-select-row"><span class="category-icon" id="report-category-icon" aria-hidden="true"></span><select id="report-category" name="category" required="">
                                    <option value="">Select a category</option>
                                </select></span>
                            </label>
                            <label id="custom-report-category-label" hidden>Enter a category<input id="custom-report-category" name="categoryOther" disabled /></label>
                            <label>Barangay<select name="barangay" data-barangays data-placeholder="Select barangay" required="">
                                    <option value="">Select barangay</option>
                                </select>
                            </label>
                            <label>Specific location<input name="location" required="" placeholder="Street, landmark, or nearby building" />
                            </label>
                            <label>Description<textarea name="description" required="" rows="5" placeholder="Describe the issue, when it happens, and how it affects the community."></textarea>
                            </label>
                            <div class="form-grid">
                                <label>Date and time observed<input name="observedAt" type="datetime-local" />
                                </label>
                                <label>Frequency of occurrence<select name="frequency">
                                        <option value="">Select frequency</option>
                                        <option>One-time</option>
                                        <option>Daily</option>
                                        <option>Weekly</option>
                                        <option>Monthly</option>
                                        <option>Recurring</option>
                                        <option>During heavy rain</option>
                                        <option>Other</option>
                                    </select>
                                    <small class="form-help">Choose how often you notice this issue.</small>
                                </label>
                            </div>
                            <label>Photos or documents (optional)<input name="attachments[]" type="file" accept="image/jpeg,image/png,image/webp,.pdf" multiple />
                                <small class="form-help">Choose a photo or document to describe the issue. This
                                    file is stored privately with your report. Up to five files, maximum 10 MB each.</small>
                            </label>
                            <div id="report-confirmation" class="notice notice-info" role="status" hidden></div>
                            <button class="button" type="submit">Submit report</button>
                        </form>
                        <div>
                            <article class="card">
                                <div class="section-heading compact">
                                    <h2>My reports</h2>
                                    <label class="report-filter-label">Status<select id="report-status-filter">
                                            <option>All statuses</option>
                                            <option>Submitted</option>
                                            <option>Under Review</option>
                                            <option>In Progress</option>
                                            <option>Reviewing</option>
                                            <option>On Process</option>
                                            <option>Done</option>
                                            <option>Resolved</option>
                                            <option>Closed</option>
                                        </select></label>
                                </div>
                                <p id="report-count" class="results-count" aria-live="polite"></p>
                                <div id="my-report-list"></div>
                            </article>
                        </div>
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
        <script src="../../js/report-icons.js"></script>
        <script src="../../js/pages/resident-reports.js"></script>
</body>
</html>
