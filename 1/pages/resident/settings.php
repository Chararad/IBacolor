<?php require_once __DIR__ . '/../../includes/page-auth.php'; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Settings | iBacolor</title>
        <link rel="stylesheet" href="../../css/pages/resident-settings.css" />
    </head>
    <body data-page="resident/settings">
        <div id="app">
            <header class="site-header">
                <div class="container header-inner">
                    <a class="brand" href="../../main.php"><img class="brand-logo" src="../../assets/bacolor-seal.webp" alt="" /><span><strong>iBacolor</strong><small>Municipal Public Service Portal</small></span></a>
                    <button class="menu-toggle" aria-expanded="false" aria-controls="main-nav">
                        Menu
                    </button>
                    <div id="main-nav">
                        <nav class="site-nav" aria-label="resident navigation">
                            <div class="nav-links">
                                <a href="dashboard.php">Dashboard</a><a href="requests.php">My Requests</a><a href="reports.php">Community Reports</a><a href="profile.php">Profile</a><a href="settings.php">Settings</a>
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
                            <a href="../../main.php">Home</a><span>/</span><a href="profile.php">Profile</a><span>/</span>Settings
                        </div>
                        <p class="eyebrow">Resident portal</p>
                        <h1>Account settings</h1>
                        <p class="lead">Manage your notification preferences and account password.</p>
                    </div>
                </section>
                <section class="section">
                    <div class="container narrow">
                        <form class="form-card" id="settings-form">
                            <fieldset>
                                <legend>Notifications</legend>
                                <label class="check-row"><input type="checkbox" name="requestNotifications" checked />
                                    Email me when request status changes</label>
                                <label class="check-row"><input type="checkbox" name="announcementNotifications" checked />
                                    Send important municipal announcements</label>
                            </fieldset>
                            <h2>Change password</h2>
                            <label>Current password<input name="currentPassword" type="password" autocomplete="current-password" required /></label>
                            <label>New password<input name="newPassword" type="password" autocomplete="new-password" minlength="8" pattern="(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}" placeholder="change password" title="Use at least 8 characters, including a number and a symbol." required /></label>
                            <label>Confirm new password<input name="confirmPassword" type="password" autocomplete="new-password" minlength="8" required /></label>
                            <div id="settings-status" class="notice notice-info" role="status" hidden></div>
                            <div class="form-actions">
                                <button class="button" type="submit">Save settings</button><a class="button button-outline" href="profile.php">Back to profile</a>
                            </div>
                        </form>
                        <p class="form-help">Account and notification changes are saved to the municipal portal database.</p>
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
        </div>
    
        <script src="../../js/portal.js"></script>
        <script src="../../js/pages/resident-settings.js"></script>
</body>
</html>
