<?php
    session_start();
    require_once '../includes/connection.php';
    require_once '../includes/audit-history.php';

    if (!isset($_SESSION['login_csrf_token'])) {
        $_SESSION['login_csrf_token'] = bin2hex(random_bytes(32));
    }

    $loginError = '';
    $emailValue = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postedEmail = $_POST['email'] ?? '';
        $postedPassword = $_POST['password'] ?? '';
        $submittedToken = $_POST['csrf_token'] ?? '';

        $emailValue = is_string($postedEmail) ? strtolower(trim($postedEmail)) : '';
        $password = is_string($postedPassword) ? $postedPassword : '';
        $submittedToken = is_string($submittedToken) ? $submittedToken : '';

        if (!hash_equals($_SESSION['login_csrf_token'], $submittedToken)) {
            $loginError = 'The form expired. Reload the page and try again.';
        } elseif (filter_var($emailValue, FILTER_VALIDATE_EMAIL) === false || strlen($emailValue) > 100) {
            $loginError = 'The email or password is incorrect.';
        } else {
            $loginQuery = 'SELECT account_id, password_hash, roles, status FROM accounts WHERE email = :email LIMIT 1';
            $loginStatement = $iBacolorConnection->prepare($loginQuery);
            $loginStatement->execute(['email' => $emailValue]);
            $account = $loginStatement->fetch(PDO::FETCH_ASSOC);

            if (!$account || !password_verify($password, $account['password_hash'])) {
                recordAdminAuditHistory(
                    $iBacolorConnection,
                    $account ? (int) $account['account_id'] : null,
                    $account ? (string) $account['roles'] : 'anonymous',
                    'login_failed',
                    'authentication',
                    $account ? (int) $account['account_id'] : 'unmatched',
                    ['reason' => 'invalid_credentials']
                );
                $loginError = 'The email or password is incorrect.';
            } elseif ($account['status'] !== 'active') {
                recordAdminAuditHistory(
                    $iBacolorConnection,
                    (int) $account['account_id'],
                    (string) $account['roles'],
                    'login_failed',
                    'authentication',
                    (int) $account['account_id'],
                    ['reason' => 'inactive_account']
                );
                $loginError = 'This account is not active. Please contact the administrator.';
            } elseif (!in_array($account['roles'], ['resident', 'admin'], true)) {
                recordAdminAuditHistory(
                    $iBacolorConnection,
                    (int) $account['account_id'],
                    (string) $account['roles'],
                    'login_failed',
                    'authentication',
                    (int) $account['account_id'],
                    ['reason' => 'role_not_allowed']
                );
                $loginError = 'This account cannot sign in. Please contact the administrator.';
            } else {
                recordAdminAuditHistory(
                    $iBacolorConnection,
                    (int) $account['account_id'],
                    (string) $account['roles'],
                    'login_succeeded',
                    'authentication',
                    (int) $account['account_id'],
                    []
                );
                session_regenerate_id(true);
                $_SESSION['account_id'] = (int) $account['account_id'];
                $_SESSION['role'] = $account['roles'];
                unset($_SESSION['login_csrf_token']);

                $destination = $account['roles'] === 'admin'
                    ? 'admin/dashboard.php'
                    : 'resident/dashboard.php';
                header('Location: ' . $destination);
                exit;
            }
        }
    }
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Sign in | iBacolor</title>
        <link rel="stylesheet" href="../css/pages/login.css" />
    </head>
    <body data-page="login">
        <div id="app">
            <main>
                <section class="login-landing">
                    <div class="container login-landing-grid">
                        <div class="login-welcome">
                            <p class="eyebrow">Municipality of Bacolor · Pampanga</p>
                            <h1>Welcome to <span>iBacolor</span></h1>
                            <p class="login-intro-copy">Your local services, municipal updates, and resident support—connected in one place.</p>
                            <div class="login-landing-links">
                                <a class="button" href="services.php">Explore municipal services</a>
                                <a class="text-link" href="hotlines.php">Emergency hotlines <span class="icon" aria-hidden="true">→</span></a>
                            </div>
                            <div class="login-trust-mark">
                                <img src="../assets/bacolor-seal.webp" alt="" />
                                <span>Municipal Public Service Portal<strong>For residents and visitors</strong></span>
                            </div>
                        </div>
                        <form action="login.php" class="form-card login-form" id="login-form" method="POST" autocomplete="off">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['login_csrf_token'], ENT_QUOTES, 'UTF-8') ?>" />
                            <p class="eyebrow">Resident portal</p>
                            <h2>Sign in to your account</h2>
                            <p class="login-form-intro">Track service requests and community reports.</p>
                            <label for="login-email">Email address<input id="login-email" name="email" type="email" value="<?= htmlspecialchars($emailValue, ENT_QUOTES, 'UTF-8') ?>" maxlength="100" required="" autocomplete="email" /></label>
                            <div class="login-password-group">
                                <label for="login-password">Password</label>
                                <div class="login-password-field">
                                    <input id="login-password" name="password" type="password" required="" autocomplete="off" />
                                    <button class="password-visibility-toggle" id="toggle-password-visibility" type="button" aria-label="Show password" aria-controls="login-password" aria-pressed="false" title="Show password">
                                        <svg class="password-visibility-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                                            <path id="password-visibility-icon-path" d="M2 12s3.7-7 10-7 10 7 10 7-3.7 7-10 7-10-7-10-7Z M12 9a3 3 0 1 0 0 6a3 3 0 0 0 0-6Z" />
                                        </svg>
                                    </button>
                                </div>
                                <a class="text-link login-forgot-link" href="forgot_password.php">Forgot Password?</a>
                            </div>
                            <div id="login-status" class="notice notice-warning" role="status" <?= $loginError === '' ? 'hidden' : '' ?>><?= htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8') ?></div>
                            <div class="form-actions">
                                <button class="button" type="submit">Sign in</button>
                                <a class="text-link" href="register.php">Create an account</a>
                            </div>
                            <a class="login-visitor-link" href="../main.php">Continue as a visitor <span class="icon" aria-hidden="true">→</span></a>
                        </form>
                    </div>
                </section>
            </main>
            <footer class="site-footer">
                <div class="container footer-grid">
                    <div>
                        <a class="brand brand-footer" href="../main.php">
                            <img class="brand-logo" src="../assets/bacolor-seal.webp" alt="" />
                            <span><strong>iBacolor</strong><small>Municipal Public Service Portal</small></span>
                        </a>
                        <p>A simpler way to access municipal information and public services.</p>
                    </div>
                    <div>
                        <h3>Explore</h3>
                        <a href="services.php">Services</a>
                        <a href="news.php">News and announcements</a>
                        <a href="hotlines.php">Emergency hotlines</a>
                        <a href="transparency.php">Transparency</a>
                        <a href="resident/reports.php">Community Report</a>
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
                    <button class="modal-close" type="button" aria-label="Close">×</button>
                    <h2>iBacolor</h2>
                    <p>This dialog is part of the frontend component foundation.</p>
                </div>
            </div>
            <nav class="bottom-nav" aria-label="Quick navigation">
                <a href="../main.php"><span class="icon" aria-hidden="true">⌂</span><span>Home</span></a>
                <a href="services.php"><span class="icon" aria-hidden="true">▦</span><span>Services</span></a>
                <a class="emergency-action" href="hotlines.php" aria-label="Emergency hotlines"><span class="icon" aria-hidden="true">☎</span><span>Emergency</span></a>
                <a href="news.php"><span class="icon" aria-hidden="true">▤</span><span>Updates</span></a>
                <a href="login.php?next=resident/profile.php"><span class="icon" aria-hidden="true">◯</span><span>Profile</span></a>
            </nav>
        </div>
        
        <script src="../js/pages/login.js?v=2"></script>
    </body>
</html>
