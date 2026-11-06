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
            <header class="site-header">
                <div class="container header-inner">
                    <a class="brand" href="../../main.php"><img class="brand-logo" src="../../assets/bacolor-seal.webp" alt="" /><span><strong>iBacolor</strong><small>Municipal Public Service Portal</small></span></a><button class="menu-toggle" aria-expanded="false" aria-controls="main-nav">
                        Menu
                    </button>
                    <div id="main-nav">
                        <nav class="site-nav" aria-label="resident navigation">
                            <div class="nav-links">
                                <a href="../resident/dashboard.php">Dashboard</a><a href="../resident/requests.php">My Requests</a><a href="../resident/reports.php">Community Reports</a><a href="../resident/profile.php">Profile</a><a href="../resident/settings.php">Settings</a>
                            </div>
                            <a class="button button-small button-outline" href="../../main.php" data-sign-out>Sign out</a>
                        </nav>
                    </div>
                </div>
            </header>
            <main>
                <section class="page-header">
                    <div class="container">
                        <div class="breadcrumbs">
                            <a href="../../main.php">Home</a><span>/</span>Profile
                        </div>
                        <p class="eyebrow">Resident portal</p>
                        <h1>My profile</h1>
                        <p class="lead">Review your resident information and notification preferences.</p>
                    </div>
                </section>
                <section class="section">
                    <div class="container narrow">
                        <form class="form-card" id="profile-form">
                            <div class="profile-banner">
                                <span class="avatar">iB</span>
                                <div>
                                    <h2 data-profile-name>Resident account</h2>
                                    <p>Resident account · Member since September 2026</p>
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
                        <a href="../services.php">Services</a><a href="../news.php">News and announcements</a><a href="../hotlines.php">Emergency hotlines</a><a href="../transparency.php">Transparency</a>
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
        <script src="../../js/pages/resident-profile.js"></script>
</body>
</html>
