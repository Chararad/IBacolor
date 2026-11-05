<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Start a Request | iBacolor</title>
    <link rel="stylesheet" href="../../css/style.css" />
    <link rel="stylesheet" href="../../css/components.css" />
    <link rel="stylesheet" href="../../css/responsive.css" />
  </head>
  <body data-page="resident/request-form">
    <div id="app">
      <header class="site-header">
        <div class="container header-inner">
          <a class="brand" href="../../index.php"
            ><img
              class="brand-logo"
              src="../../assets/bacolor-seal.webp"
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
            <nav class="site-nav" aria-label="resident navigation">
              <div class="nav-links">
                <a href="../resident/dashboard.php">Dashboard</a
                ><a href="../resident/requests.php">My Requests</a
                ><a href="../resident/reports.php">Community Reports</a
                ><a href="../resident/profile.php">Profile</a
                ><a href="../resident/settings.php">Settings</a>
              </div>
              <a
                class="button button-small button-outline"
                href="../../index.php"
                data-sign-out
                >Sign out</a
              >
            </nav>
          </div>
        </div>
      </header>
      <main>
        <section class="page-header">
          <div class="container">
            <div class="breadcrumbs">
              <a href="../../index.php">Home</a><span>/</span>Services / Request
            </div>
            <p class="eyebrow">Resident portal</p>
            <h1>Start a service request</h1>
            <p class="lead">
              Complete the request details and attach the required documents.
            </p>
          </div>
        </section>
        <section class="section">
          <div class="container narrow">
            <form class="form-card request-form" id="request-form">
              <div
                id="request-confirmation"
                class="notice notice-info"
                role="status"
                hidden
              ></div>
              <fieldset>
                <legend>Step 1: Select a service</legend>
                <label
                  >Service<select
                    name="service"
                    data-service-select
                    required=""
                  >
                    <option value="">Select a service</option>
                  </select></label
                >
              </fieldset>
              <fieldset>
                <legend>Step 2: Applicant information</legend>
                <div class="form-grid">
                  <label
                    >First name<input name="firstName" required=""
                  /></label>
                  <label>Last name<input name="lastName" required="" /></label>
                  <label
                    >Mobile number<input
                      name="mobile"
                      type="tel"
                      required=""
                      pattern="(?:\+639|09)[0-9]{9}"
                  /></label>
                  <label
                    >Barangay<select name="barangay" data-barangays required="">
                      <option value="">Select barangay</option>
                    </select></label
                  >
                </div>
              </fieldset>
              <fieldset>
                <legend>Step 3: Request details and documents</legend>
                <label
                  >Purpose or request details<textarea
                    name="details"
                    rows="5"
                    required=""
                  ></textarea>
                </label>
                <label
                  >Upload documents<input
                    name="documents"
                    type="file"
                    multiple=""
                /></label>
                <small class="form-help"
                  >Check the service information page for required documents.
                  Selected files are not uploaded in this prototype.</small
                >
              </fieldset>
              <div class="form-actions">
                <button class="button" type="submit">Submit request</button
                ><a class="button button-outline" href="../services.php"
                  >Exit</a
                >
              </div>
            </form>
          </div>
        </section>
      </main>
      <footer class="site-footer">
        <div class="container footer-grid">
          <div>
            <a class="brand brand-footer" href="../../index.php"
              ><img
                class="brand-logo"
                src="../../assets/bacolor-seal.webp"
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
            <a href="../services.php">Services</a
            ><a href="../news.php">News and announcements</a
            ><a href="../hotlines.php">Emergency hotlines</a
            ><a href="../transparency.php">Transparency</a>
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
    </div>
    <script src="../../js/mock-data.js"></script>
    <script src="../../js/data-store.js"></script>
    <script src="../../js/navigation.js"></script>
    <script src="../../js/main.js"></script>
  </body>
</html>
