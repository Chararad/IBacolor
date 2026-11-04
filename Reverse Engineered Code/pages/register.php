<!doctype html>
<html lang="en">
  <!-- Page metadata -->
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Register | iBacolor</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/components.css" />
    <link rel="stylesheet" href="../css/responsive.css" />
  </head>
  <body data-page="register">
    <div id="app">
      <!-- Site header and navigation -->
      <header class="site-header">
        <div class="container header-inner">
          <a class="brand" href="../main.php"
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
                <a href="../main.php">Home</a><a href="services.php">Services</a
                ><a href="news.php">News</a
                ><a href="hotlines.php">Emergency Hotlines</a>
              </div>
              <a class="button button-small" href="login.php"
                >Resident sign in</a
              >
            </nav>
          </div>
        </div>
      </header>
      <!-- Main page content -->
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
            <form class="form-card" data-feedback-form="">
              <div class="form-grid">
                <label
                  >First name<input required="" placeholder="Maria" />
                </label>
                <label
                  >Middle name (optional)<input placeholder="Dela Cruz" />
                </label>
                <label
                  >Last name<input required="" placeholder="Santos" />
                </label>
                <label
                  >Email address<input
                    type="email"
                    required=""
                    placeholder="you@example.com"
                  />
                </label>
                <label
                  >Mobile number<input
                    type="tel"
                    required=""
                    placeholder="09xx xxx xxxx"
                  />
                </label>
                <label
                  >Barangay<select required="">
                    <option value="">Select barangay</option>
                    <option>Anunas</option>
                    <option>Balas</option>
                    <option>Cabambangan</option>
                    <option>Cabetican</option>
                    <option>Calibutbut</option>
                    <option>Concepcion</option>
                    <option>Cuayan</option>
                    <option>Dela Paz</option>
                    <option>Dolores</option>
                    <option>Lourdes</option>
                    <option>Magliman</option>
                    <option>Maliwalu</option>
                    <option>Mambog</option>
                    <option>Mañebeng</option>
                    <option>Poblacion</option>
                    <option>San Antonio</option>
                    <option>San Isidro</option>
                    <option>Santa Barbara</option>
                    <option>Santa Ines</option>
                    <option>Tibag</option>
                    <option>Zaragosa</option>
                  </select>
                </label>
                <label
                  >Residential address<textarea
                    rows="3"
                    required=""
                    placeholder="House no./street, barangay, municipality, province"
                  ></textarea>
                </label>
                <label
                  >Municipality<input value="Bacolor" readonly="" />
                </label>
                <label>Province<input value="Pampanga" readonly="" /> </label>
                <label
                  >Password<input
                    type="password"
                    required=""
                    placeholder="Create a password"
                  />
                </label>
                <label
                  >Confirm password<input
                    type="password"
                    required=""
                    placeholder="Repeat password"
                  />
                </label>
              </div>
              <label class="check-row"
                ><input type="checkbox" required="" /> I acknowledge the terms
                of service and privacy notice.
              </label>
              <div class="form-actions">
                <button class="button" type="submit">Create account</button
                ><a class="text-link" href="login.php"
                  >Already registered? Sign in</a
                >
              </div>
            </form>
          </div>
        </section>
      </main>
      <!-- Site footer -->
      <footer class="site-footer">
        <div class="container footer-grid">
          <div>
            <a class="brand brand-footer" href="../main.php"
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
            ><a href="hotlines.php">Emergency hotlines</a>
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
        <a href="../main.php"
          ><span class="icon" aria-hidden="true">⌂</span><span>Home</span></a
        ><a href="../pages/services.php"
          ><span class="icon" aria-hidden="true">▦</span
          ><span>Services</span></a
        ><a class="emergency-action" href="../pages/hotlines.php"
          ><span class="icon" aria-hidden="true">☎</span
          ><span>Emergency</span></a
        ><a href="../pages/news.php"
          ><span class="icon" aria-hidden="true">▤</span><span>Updates</span></a
        ><a href="../pages/login.php"
          ><span class="icon" aria-hidden="true">◯</span><span>Profile</span></a
        >
      </nav>
    </div>
    <!-- Page scripts -->
    <script src="../js/mock-data.js"></script>
    <script src="../js/data-store.js"></script>
    <script src="../js/navigation.js"></script>
    <script src="../js/main.js"></script>
  </body>
</html>
