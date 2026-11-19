<?php require_once __DIR__ . '/../../includes/page-auth.php'; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Start a Request | iBacolor</title>
        <link rel="stylesheet" href="../../css/pages/resident-request-form.css" />
    </head>
    <body data-page="resident/request-form">
        <div id="app">
            <?php require __DIR__ . '/../../includes/site-header.php'; ?>
            <main>
                <section class="page-header">
                    <div class="container">
                        <p class="eyebrow">Resident portal</p>
                        <h1>Start a service request</h1>
                        <p class="lead">Complete the request details and attach the required documents.</p>
                    </div>
                </section>
                <section class="section">
                    <div class="container narrow">
                        <form class="form-card request-form" id="request-form">
                            <div id="request-confirmation" class="notice notice-info" role="status" hidden></div>
                            <fieldset>
                                <legend>Step 1: Select a service</legend>
                                <label>Service<select name="service" data-service-select required="">
                                        <option value="">Select a service</option>
                                    </select></label>
                            </fieldset>
                            <fieldset>
                                <legend>Step 2: Applicant information</legend>
                                <div class="form-grid">
                                    <label>First name<input name="firstName" required="" /></label>
                                    <label>Last name<input name="lastName" required="" /></label>
                                    <label>Mobile number<input name="mobile" type="tel" required="" pattern="(?:\+639|09)[0-9]{9}" /></label>
                                    <label>Barangay<select name="barangay" data-barangays required="">
                                            <option value="">Select barangay</option>
                                        </select></label>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Step 3: Request details and documents</legend>
                                <label>Purpose or request details<textarea name="details" rows="5" maxlength="255" required=""></textarea>
                                </label>
                                <div id="document-requirements">
                                    <p class="form-help">Select a service to see its required documents.</p>
                                </div>
                            </fieldset>
                            <div class="form-actions">
                                <button class="button" type="submit">Submit request</button><a class="button button-outline" href="../services.php">Exit</a>
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
        <script src="../../js/pages/resident-request-form.js"></script>
</body>
</html>
