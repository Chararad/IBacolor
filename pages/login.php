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
        <section class="login-landing">
          <div class="container login-landing-grid">
            <div class="login-welcome">
              <p class="eyebrow">Municipality of Bacolor · Pampanga</p>
              <h1>Welcome to <span>iBacolor</span></h1>
              <p class="login-intro-copy">
                Your local services, municipal updates, and resident
                support—connected in one place.
              </p>
              <div class="login-landing-links">
                <a class="button" href="services.php"
                  >Explore municipal services</a
                ><a class="text-link" href="hotlines.php"
                  >Emergency hotlines
                  <span class="icon" aria-hidden="true">→</span></a
                >
              </div>
              <div class="login-trust-mark">
                <img src="../assets/bacolor-seal.webp" alt="" /><span
                  >Municipal Public Service Portal<strong
                    >For residents and visitors</strong
                  ></span
                >
              </div>
            </div>
            <form class="form-card login-form" id="login-form">
              <p class="eyebrow">Resident portal</p>
              <h2>Sign in to your account</h2>
              <p class="login-form-intro">
                Track service requests and community reports.
              </p>
              <label
                >Email address<input
                  name="email"
                  type="email"
                  required=""
                  autocomplete="email"
                />
              </label>
              <label
                >Password<input
                  name="password"
                  type="password"
                  required=""
                  autocomplete="current-password"
                />
              </label>
              <div
                id="login-status"
                class="notice notice-warning"
                role="status"
                hidden
              ></div>
              <div class="form-actions">
                <button class="button" type="submit">Sign in</button
                ><a class="text-link" href="register.php">Create an account</a>
              </div>
              <a class="login-visitor-link" href="../index.php"
                >Continue as a visitor
                <span class="icon" aria-hidden="true">→</span></a
              >
            </form>
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
        ><a href="login.php?next=resident/profile.php"
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
    <script src="../js/pages/login.js"></script>
  </body>
</html>
