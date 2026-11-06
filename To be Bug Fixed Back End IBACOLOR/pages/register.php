<?php
    session_start();
    $isAuthenticated = isset($_SESSION['account_id']) && in_array($_SESSION['role'] ?? '', ['resident', 'admin'], true);
    require_once '../includes/connection.php';

    $getBarangayQuery = "SELECT barangay_id, name FROM barangays ORDER BY name";
    $getBarangay = $iBacolorConnection->prepare($getBarangayQuery);
    $getBarangay->execute();
    $barangays = $getBarangay->fetchAll(PDO::FETCH_ASSOC);

    if (!isset($_SESSION['registration_csrf_token'])) {
        $_SESSION['registration_csrf_token'] = bin2hex(random_bytes(32));
    }

    $errors = [];
    $formValues = [
        'firstName' => '',
        'middleName' => '',
        'lastName' => '',
        'email' => '',
        'mobile' => '',
        'barangay_id' => '',
        'address' => '',
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postString = static function (string $key): string {
            $value = $_POST[$key] ?? '';
            return is_string($value) ? trim($value) : '';
        };

        foreach (array_keys($formValues) as $field) {
            $formValues[$field] = $postString($field);
        }

        $password = $postString('password');
        $confirmPassword = $postString('confirmPassword');
        $submittedToken = $postString('csrf_token');

        if (!hash_equals($_SESSION['registration_csrf_token'], $submittedToken)) {
            $errors[] = 'The form expired. Reload the page and try again.';
        }

        foreach (['firstName', 'lastName', 'email', 'mobile', 'barangay_id', 'address'] as $field) {
            if ($formValues[$field] === '') {
                $errors[] = 'Please complete all required fields.';
                break;
            }
        }

        $formValues['email'] = strtolower($formValues['email']);
        if ($formValues['email'] !== '' && filter_var($formValues['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors[] = 'Enter a valid email address.';
        }
        elseif ($formValues['email'] !== ''){
            $checkEmail = $iBacolorConnection->prepare('SELECT 1 FROM accounts where email= :email LIMIT 1');
            $checkEmail->execute(['email' => $formValues['email']]);
            $emailExists = $checkEmail->fetchColumn() !== false;

            if ($emailExists) {
                $errors[] = 'An account with this email already exists. Please sign in.';
            }
        }

        if ($formValues['mobile'] !== '' && preg_match('/^(?:\+639|09)[0-9]{9}$/', $formValues['mobile']) !== 1) {
            $errors[] = 'Enter a valid Philippine mobile number.';
        }

        if ($password === '' || preg_match('/^(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}$/', $password) !== 1) {
            $errors[] = 'Password must be at least 8 characters and include a number and a symbol.';
        }

        if ($password !== $confirmPassword) {
            $errors[] = 'The passwords do not match.';
        }

        $allowedBarangayIds = array_map('strval', array_column($barangays, 'barangay_id'));
        if ($formValues['barangay_id'] === '' || !ctype_digit($formValues['barangay_id']) || !in_array($formValues['barangay_id'], $allowedBarangayIds, true)) {
            $errors[] = 'Select a valid barangay.';
        }

        if (($_POST['policyConsent'] ?? '') !== 'on') {
            $errors[] = 'You must agree to the Terms of Service and acknowledge the Privacy Policy.';
        }

        if (empty($errors)) {
            try {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                $iBacolorConnection->beginTransaction();
                
                $insertAccountQuery = 'INSERT INTO accounts (email, password_hash, roles, status) VALUES (:email, :password_hash, :roles, :status)';
                $insertAccount = $iBacolorConnection->prepare($insertAccountQuery);
                $insertAccount->execute([
                    'email' => $formValues['email'],
                    'password_hash' => $passwordHash,
                    'roles' => 'resident',
                    'status' => 'active',
                ]);

                $accountId = (int) $iBacolorConnection->lastInsertId();
                $insertProfileQuery = 
                    'INSERT INTO resident_profiles (
                        account_id,
                        first_name,
                        middle_name,
                        last_name,
                        mobile_number,
                        barangay_id,
                        address,
                        verification,
                        privacy_policy_version
                    ) VALUES (
                        :account_id,
                        :first_name,
                        :middle_name,
                        :last_name,
                        :mobile_number,
                        :barangay_id,
                        :address,
                        :verification,
                        :privacy_policy_version
                    )';
                $insertProfile = $iBacolorConnection->prepare($insertProfileQuery);
                $insertProfile->execute([
                    'account_id' => $accountId,
                    'first_name' => $formValues['firstName'],
                    'middle_name' => $formValues['middleName'] !== '' ? $formValues['middleName'] : null,
                    'last_name' => $formValues['lastName'],
                    'mobile_number' => $formValues['mobile'],
                    'barangay_id' => (int) $formValues['barangay_id'],
                    'address' => $formValues['address'],
                    'verification' => 'pending',
                    'privacy_policy_version' => 'v1',
                ]);

                $insertPreferencesQuery = 'INSERT INTO notification_preferences (account_id, request_updates, announcement_updates)
                    VALUES (:account_id, :request_updates, :announcement_updates)';
                $insertPreferences = $iBacolorConnection->prepare($insertPreferencesQuery);
                $insertPreferences->execute([
                    'account_id' => $accountId,
                    'request_updates' => 1,
                    'announcement_updates' => 1,
                ]);

                $iBacolorConnection->commit();
                session_regenerate_id(true);
                $_SESSION['account_id'] = $accountId;
                $_SESSION['role'] = 'resident';
                unset($_SESSION['registration_csrf_token']);
                header('Location: resident/dashboard.php');
                exit;
            } catch (PDOException $exception) {
                if ($iBacolorConnection->inTransaction()) {
                    $iBacolorConnection->rollBack();
                }

                error_log($exception->getMessage());
                if (($exception->errorInfo[1] ?? null) === 1062) {
                    $errors[] = 'An account with this email already exists. Please sign in.';
                } else {
                    $errors[] = 'Registration could not be completed. Please try again.';
                }
            }
        }
    }
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Register | iBacolor</title>
        <link rel="stylesheet" href="../css/pages/register.css" />
    </head>
    <body data-page="register">
        <div id="app">
            <header class="site-header">
                <div class="container header-inner">
                    <a class="brand" href="../main.php">
                        <img class="brand-logo" src="../assets/bacolor-seal.webp" alt="" />
                        <span><strong>iBacolor</strong><small>Municipal Public Service Portal</small></span>
                    </a>
                    <button class="menu-toggle" aria-expanded="false" aria-controls="main-nav">Menu</button>
                    <div id="main-nav">
                        <nav class="site-nav" aria-label="public navigation">
                            <div class="nav-links">
                                <a href="../main.php">Home</a>
                                <a href="services.php">Services</a>
                                <a href="news.php">News</a>
                                <a href="hotlines.php">Emergency Hotlines</a>
                                <a href="transparency.php">Transparency</a>
                            </div>
                            <a id="resident-login-button" class="button button-small" href="login.php" data-authenticated="<?= $isAuthenticated ? 'true' : 'false' ?>" data-logout-href="logout.php">Resident sign in</a>
                        </nav>
                    </div>
                </div>
            </header>
            <main>
                <section class="page-header">
                    <div class="container">
                        <p class="eyebrow">Join the portal</p>
                        <h1>Create a resident account</h1>
                        <p class="lead">Register to access request tracking and community reporting.</p>
                    </div>
                </section>
                <section class="section">
                    <div class="container narrow">
                        <!-- BEGIN REGISTRATION FORM -->
                        <form action="register.php" class="form-card" id="registration-form" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['registration_csrf_token'], ENT_QUOTES, 'UTF-8') ?>" />
                            <div class="form-grid">
                                <label>First name<input name="firstName" value="<?= htmlspecialchars($formValues['firstName'], ENT_QUOTES, 'UTF-8') ?>" required="" autocomplete="given-name" /></label>
                                <label>Middle name (optional)<input name="middleName" value="<?= htmlspecialchars($formValues['middleName'], ENT_QUOTES, 'UTF-8') ?>" autocomplete="additional-name" /></label>
                                <label>Last name<input name="lastName" value="<?= htmlspecialchars($formValues['lastName'], ENT_QUOTES, 'UTF-8') ?>" required="" autocomplete="family-name" /></label>
                                <label>Email address<input name="email" type="email" value="<?= htmlspecialchars($formValues['email'], ENT_QUOTES, 'UTF-8') ?>" required="" autocomplete="email" /></label>
                                <label>Mobile number<input name="mobile" type="tel" value="<?= htmlspecialchars($formValues['mobile'], ENT_QUOTES, 'UTF-8') ?>" required="" autocomplete="tel" inputmode="tel" pattern="(?:\+639|09)[0-9]{9}" /></label>
                                <label>Barangay
                                    <select name="barangay_id" required="">
                                        <option value="">Select barangay</option>
                                        <?php
                                            foreach ($barangays as $barangay):
                                                $barangayId = (string) $barangay['barangay_id'];
                                        ?>
                                            <option value="<?= htmlspecialchars($barangayId, ENT_QUOTES, 'UTF-8') ?>" <?= $formValues['barangay_id'] === $barangayId ? 'selected' : '' ?>>
                                                <?= htmlspecialchars((string) $barangay['name'], ENT_QUOTES, 'UTF-8') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>  
                                </label>
                                <label>Residential address<textarea name="address"                                 rows="3"
                                maxlength="255" required="" autocomplete="street-address" placeholder="House no./street, barangay, municipality, province"><?= htmlspecialchars($formValues['address'], ENT_QUOTES, 'UTF-8') ?></textarea></label>
                                <label>Municipality<input name="municipality" value="Bacolor" readonly="" /></label>
                                <label>Province<input name="province" value="Pampanga" readonly="" /></label>
                                <label>Password<input name="password" type="password" required="" minlength="8" pattern="(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}" autocomplete="new-password" placeholder="change password" /></label>
                                <label>Confirm password<input name="confirmPassword" type="password" required="" minlength="8" autocomplete="new-password" /></label>
                            </div>
                            <div class="location-capture">
                                <button class="button button-outline" id="capture-location" type="button">Pin my current location</button>
                                <p id="location-status" class="form-help" role="status" hidden></p>
                            </div>
                            <label class="check-row"><input name="policyConsent" type="checkbox" required="" /> I agree to the <a href="#terms-of-service">Terms of Service</a> and acknowledge the <a href="#privacy-policy">Privacy Policy</a>.</label>
                            <details id="terms-of-service" class="policy-details" open>
                                <summary>Terms of Service</summary>
                                <p>This prototype provides sample municipal information and browser-based account features for demonstration. Confirm official requirements and service availability with the Municipality of Bacolor.</p>
                            </details>
                            <details id="privacy-policy" class="policy-details" open>
                                <summary>Privacy Policy</summary>
                                <p>Account details are submitted to the portal database. Your precise location is accessed only after you select the location button, displayed temporarily on this page, and not saved with your account. This is a prototype; do not submit real sensitive information.</p>
                            </details>
                            <div id="registration-status" class="notice notice-warning" role="status" <?= $errors ? '' : 'hidden' ?>>
                                <?php if ($errors): ?>
                                    <ul>
                                        <?php foreach ($errors as $error): ?>
                                            <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                            <div class="form-actions">
                                <button class="button" type="submit">Create account</button>
                                <a class="text-link" href="login.php">Already registered? Sign in</a>
                            </div>
                        </form>
                        <!-- END REGISTRATION FORM -->
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
                    <button class="modal-close" type="button" aria-label="Close">×</button>
                    <h2>iBacolor</h2>
                    <p>This dialog is part of the frontend component foundation.</p>
                </div>
            </div>
            <nav class="bottom-nav" aria-label="Quick navigation">
                <a href="../main.php"><span class="icon" aria-hidden="true">⌂</span><span>Home</span></a>
                <a href="services.php"><span class="icon" aria-hidden="true">▦</span><span>Services</span></a>
                <a class="emergency-action" href="hotlines.php"><span class="icon" aria-hidden="true">☎</span><span>Emergency</span></a>
                <a href="news.php"><span class="icon" aria-hidden="true">▤</span><span>Updates</span></a>
                <a href="login.php"><span class="icon" aria-hidden="true">◯</span><span>Profile</span></a>
            </nav>
        </div>
        
        
        <script src="../js/register.js"></script>
        <script src="../js/helper.js"></script>
    </body>
    
</html>
