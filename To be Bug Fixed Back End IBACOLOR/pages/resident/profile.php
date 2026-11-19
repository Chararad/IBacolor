<?php require_once __DIR__ . '/../../includes/page-auth.php'; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Profile | iBacolor</title>
        <link rel="stylesheet" href="../../css/pages/resident-profile.css" />
    </head>
    <body data-page="resident/profile">
        <div id="app">
            <?php require __DIR__ . '/../../includes/site-header.php'; ?>
            <main>
                <section class="page-header">
                    <div class="container">
                        <p class="eyebrow">Resident portal</p>
                        <h1>My profile</h1>
                        <p class="lead">Review your resident information and notification preferences.</p>
                    </div>
                </section>
                <section class="section">
                    <div class="container narrow">
                        <form class="form-card" id="profile-form">
                            <div id="resident-verification-status" class="notice notice-info" role="status"></div>
                            <div class="profile-banner">
                                <span class="avatar-wrap">
                                    <span class="avatar" id="profile-avatar" data-profile-avatar>iB</span>
                                    <input type="file" id="avatar-input" accept="image/jpeg,image/png,image/webp" hidden />
                                    <button class="button button-outline avatar-change" id="avatar-change" type="button">Change photo</button>
                                </span>
                                <div>
                                    <h2 data-profile-name>Resident account</h2>
                                    <p>Resident account · Member since September 2026</p>
                                    <small id="avatar-status" class="form-help" role="status" hidden></small>
                                </div>
                            </div>
                            <div class="form-grid">
                                <label>First name<input name="firstName" data-profile-field="firstName" maxlength="255" required />
                                </label>
                                <label>Last name<input name="lastName" data-profile-field="lastName" maxlength="255" required />
                                </label>
                                <label>Email address<input name="email" type="email" data-profile-field="email" readonly />
                                </label>
                                <label>Mobile number<input name="mobile" type="tel" data-profile-field="mobile" pattern="(?:\+639|09)[0-9]{9}" required />
                                </label>
                                <label>Barangay<select name="barangay" data-profile-field="barangay" data-barangays required>
                                        <option value="">Select barangay</option>
                                    </select>
                                </label>
                                <label>Residential address<textarea name="address" data-profile-field="address" rows="3" maxlength="255" required></textarea>
                                </label>
                            </div>
                            <fieldset>
                                <legend>Notifications</legend>
                                <label class="check-row"><input type="checkbox" checked="" /> Email me when request
                                    status changes
                                </label>
                                <label class="check-row"><input type="checkbox" checked="" /> Send important municipal
                                    announcements
                                </label>
                            </fieldset>
                            <button class="button" type="submit">Save changes</button>
                            <div id="profile-status" class="notice notice-info" role="status" hidden></div>
                            <a class="button button-outline" href="settings.php">Settings</a>
                        </form>
                        <article class="card" id="verification-documents-card">
                            <h2>Resident verification documents</h2>
                            <p>Submit supporting documents for administrator review. Files are stored privately.</p>
                            <div id="verification-documents-list"></div>
                            <?php if (($_SESSION['role'] ?? '') === 'resident'): ?>
                                <form id="verification-documents-form" class="document-replacement-form">
                                    <label>Choose documents<input name="documents[]" type="file" accept=".pdf,image/jpeg,image/png,application/pdf" multiple required></label>
                                    <small class="form-help">PDF, JPG, or PNG; up to five files, maximum 10 MB each.</small>
                                    <button class="button button-outline" type="submit">Submit verification documents</button>
                                    <p id="verification-documents-status" class="notice notice-info" role="status" hidden></p>
                                </form>
                            <?php endif; ?>
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
        <script src="../../js/pages/resident-profile.js"></script>
</body>
</html>
