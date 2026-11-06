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
          <a class="brand" href="../index.php"
            ><img
              class="brand-logo"
              src="../assets/bacolor-seal.webp"
              alt=""
            /><span
              ><strong>iBacolor</strong
              ><small>Municipal Public Service Portal</small></span
            ></a
          ><button
            class="menu-toggle"
            aria-expanded="false"
            aria-controls="main-nav"
          >
            Menu
          </button>
          <div id="main-nav">
            <nav class="site-nav" aria-label="public navigation">
              <div class="nav-links">
                <a href="../index.php">Home</a
                ><a href="services.php">Services</a><a href="news.php">News</a
                ><a href="hotlines.php">Emergency Hotlines</a
                ><a href="transparency.php">Transparency</a>
              </div>
              <a class="button button-small" href="login.php"
                >Resident sign in</a
              >
            </nav>
          </div>
        </div>
      </header>
      <main>
        <section class="page-header">
          <div class="container">
            <p class="eyebrow">Join the portal</p>
            <h1>Create a resident account</h1>
            <p class="lead">
              Register to access request tracking and community reporting.
            </p>
          </div>
        </section>
        <section class="section">
          <div class="container narrow">
            <!-- BEGIN REGISTRATION FORM -->
            
            <form class="form-card" id="registration-form" method="POST">
              <div class="form-grid">
                <label
                  >First name<input
                    name="firstName"
                    required=""
                    autocomplete="given-name"
                  />
                </label>
                <label
                  >Middle name (optional)<input
                    name="middleName"
                    autocomplete="additional-name"
                  />
                </label>
                <label
                  >Last name<input
                    name="lastName"
                    required=""
                    autocomplete="family-name"
                  />
                </label>
                <label
                  >Email address<input
                    name="email"
                    type="email"
                    required=""
                    autocomplete="email"
                  />
                </label>
                <label
                  >Mobile number<input
                    name="mobile"
                    type="tel"
                    required=""
                    autocomplete="tel"
                    inputmode="tel"
                    pattern="(?:\+639|09)[0-9]{9}"
                  />
                </label>
                <label
                  >Barangay<select name="barangay" data-barangays required="">
                    <option value="">Select barangay</option>
                  </select>
                </label>
                <label
                  >Residential address<textarea
                    name="address"
                    rows="3"
                    required=""
                    autocomplete="street-address"
                    placeholder="House no./street, barangay, municipality, province"
                  ></textarea>
                </label>
                <label>
                  Municipality<input
                    name="municipality"
                    value="Bacolor"
                    readonly=""
                  />
                </label>
                <label>
                  Province<input
                    name="province"
                    value="Pampanga"
                    readonly=""
                  />
                </label>
                <label
                  >Password<input
                    name="password"
                    type="password"
                    required=""
                    minlength="8"
                    pattern="(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}"
                    autocomplete="new-password"
                    placeholder="change password"
                  />
                </label>
                <label
                  >Confirm password<input
                    name="confirmPassword"
                    type="password"
                    required=""
                    minlength="8"
                    autocomplete="new-password"
                  />
                </label>
              </div>
              <div class="location-capture">
                <button
                  class="button button-outline"
                  id="capture-location"
                  type="button"
                >
                  Pin my current location
                </button>
                <p
                  id="location-status"
                  class="form-help"
                  role="status"
                  hidden
                ></p>
              </div>
              <label class="check-row"
                ><input name="policyConsent" type="checkbox" required="" /> I
                agree to the
                <a href="#terms-of-service">Terms of Service</a> and acknowledge
                the <a href="#privacy-policy">Privacy Policy</a>.
              </label>
              <details id="terms-of-service" class="policy-details" open>
                <summary>Terms of Service</summary>
                <p>
                  This prototype provides sample municipal information and
                  browser-based account features for demonstration. Confirm
                  official requirements and service availability with the
                  Municipality of Bacolor.
                </p>
              </details>
              <details id="privacy-policy" class="policy-details" open>
                <summary>Privacy Policy</summary>
                <p>
                  Registration data, including the account email and location
                  coordinates, is stored in this browser for this prototype. Do
                  not use real sensitive information. This frontend is not
                  connected to a secure municipal system.
                </p>
              </details>
              <div
                id="registration-status"
                class="notice notice-warning"
                role="status"
                hidden
              ></div>
              <div class="form-actions">
                <button class="button" type="submit">Create account</button
                ><a class="text-link" href="login.php"
                  >Already registered? Sign in</a
                >
              </div>
            </form>
            <!-- END REGISTRATION FORM -->

            <?php
              require_once 'connection.php';
            ?>
          </div>
        </section>
      </main>
      <footer class="site-footer">
        <div class="container footer-grid">
          <div>
            <a class="brand brand-footer" href="../index.php"
              ><img
                class="brand-logo"
                src="../assets/bacolor-seal.webp"
                alt=""
              /><span
                ><strong>iBacolor</strong
                ><small>Municipal Public Service Portal</small></span
              ></a
            >
            <p>
              A simpler way to access municipal information and public services.
            </p>
          </div>
          <div>
            <h3>Explore</h3>
            <a href="services.php">Services</a
            ><a href="news.php">News and announcements</a
            ><a href="hotlines.php">Emergency hotlines</a
            ><a href="transparency.php">Transparency</a>
          </div>
          <div>
            <h3>Need assistance?</h3>
            <p>
              Municipal Hall<br />(045) 900-1000<br />Mon–Fri, 8:00 AM–5:00 PM
            </p>
          </div>
        </div>
        <div class="container footer-bottom">
          <span>© 2026 Municipality of Bacolor</span>
        </div>
      </footer>
      <div
        class="modal"
        id="demo-modal"
        role="dialog"
        aria-modal="true"
        aria-hidden="true"
      >
        <div class="modal-content">
          <button class="modal-close" type="button" aria-label="Close">
            ×
          </button>
          <h2>iBacolor</h2>
          <p>This dialog is part of the frontend component foundation.</p>
        </div>
      </div>
      <nav class="bottom-nav" aria-label="Quick navigation">
        <a href="../index.php"
          ><span class="icon" aria-hidden="true">⌂</span><span>Home</span></a
        ><a href="services.php"
          ><span class="icon" aria-hidden="true">▦</span
          ><span>Services</span></a
        ><a class="emergency-action" href="hotlines.php"
          ><span class="icon" aria-hidden="true">☎</span
          ><span>Emergency</span></a
        ><a href="news.php"
          ><span class="icon" aria-hidden="true">▤</span><span>Updates</span></a
        ><a href="login.php"
          ><span class="icon" aria-hidden="true">◯</span><span>Profile</span></a
        >
      </nav>
    </div>
    <script src="../js/mock-data.js"></script>
    <script src="../js/data-store.js"></script>
    <script src="../js/navigation.js"></script>
    <script src="../js/modules/shared.js"></script>
    <script src="../js/modules/accounts.js"></script>
    <script src="../js/modules/feedback.js"></script>
    <script src="../js/pages/register.js"></script>
  </body>
</html>
