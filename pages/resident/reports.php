<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Community Reports | iBacolor</title>
    <link rel="stylesheet" href="../../css/pages/resident-reports.css" />
  </head>
  <body data-page="resident/reports">
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
              <a href="../../index.php">Home</a><span>/</span>Community Reports
            </div>
            <p class="eyebrow">Resident portal</p>
            <h1>Community reports</h1>
            <p class="lead">
              Report a local issue and follow up on concerns submitted to the
              municipality.
            </p>
          </div>
        </section>
        <section class="section">
          <div class="container content-grid">
            <form class="form-card" id="community-report-form">
              <p class="eyebrow">New report</p>
              <h2>Report an issue</h2>
              <label
                >Category<select
                  id="report-category"
                  name="category"
                  required=""
                >
                  <option value="">Select a category</option>
                </select>
              </label>
              <label id="custom-report-category-label" hidden
                >Enter a category<input
                  id="custom-report-category"
                  name="categoryOther"
                  disabled
              /></label>
              <label
                >Barangay<select
                  name="barangay"
                  data-barangays
                  data-placeholder="Select barangay"
                  required=""
                >
                  <option value="">Select barangay</option>
                </select>
              </label>
              <label
                >Specific location<input
                  name="location"
                  required=""
                  placeholder="Street, landmark, or nearby building"
                />
              </label>
              <label
                >Description<textarea
                  name="description"
                  required=""
                  rows="5"
                  placeholder="Describe the issue, when it happens, and how it affects the community."
                ></textarea>
              </label>
              <div class="form-grid">
                <label
                  >Date and time observed<input
                    name="observedAt"
                    type="datetime-local"
                    required=""
                  />
                </label>
                <label
                  >Frequency of occurrence<select name="frequency" required="">
                    <option value="">Select frequency</option>
                    <option>One-time</option>
                    <option>Daily</option>
                    <option>Weekly</option>
                    <option>Monthly</option>
                    <option>Recurring</option>
                    <option>During heavy rain</option>
                    <option>Other</option>
                  </select>
                  <small class="form-help"
                    >Choose how often you notice this issue.</small
                  >
                </label>
              </div>
              <label
                >Photo or document (optional)<input
                  name="attachment"
                  type="file"
                  accept="image/*,.pdf"
                />
                <small class="form-help"
                  >Choose a photo or document to describe the issue. This
                  prototype does not upload or save attachments.</small
                >
              </label>
              <div
                id="report-confirmation"
                class="notice notice-info"
                role="status"
                hidden
              ></div>
              <button class="button" type="submit">Submit report</button>
            </form>
            <div>
              <article class="card">
                <div class="section-heading compact">
                  <h2>My reports</h2>
                  <label class="report-filter-label"
                    >Status<select id="report-status-filter">
                      <option>All statuses</option>
                      <option>Submitted</option>
                      <option>Under Review</option>
                      <option>Reviewing</option>
                      <option>On Process</option>
                      <option>Done</option>
                      <option>Resolved</option>
                      <option>Closed</option>
                    </select></label
                  >
                </div>
                <p
                  id="report-count"
                  class="results-count"
                  aria-live="polite"
                ></p>
                <div id="my-report-list"></div>
              </article>
            </div>
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
    <script src="../../js/modules/shared.js"></script>
    <script src="../../js/modules/accounts.js"></script>
    <script src="../../js/modules/feedback.js"></script>
    <script src="../../js/modules/reports.js"></script>
    <script src="../../js/pages/resident-reports.js"></script>
  </body>
</html>
