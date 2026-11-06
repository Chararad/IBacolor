<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Resident Dashboard | iBacolor</title>
    <link rel="stylesheet" href="../../css/pages/resident-dashboard.css" />
  </head>
  <body data-page="resident/dashboard">
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
              <a href="../../index.php">Home</a><span>/</span>Resident Dashboard
            </div>
            <p class="eyebrow">Resident portal</p>
            <h1 data-resident-greeting>Welcome back</h1>
            <p class="lead">
              Your municipal requests and community updates, at a glance.
            </p>
          </div>
        </section>
        <section class="resident-action-band">
          <div class="container">
            <div>
              <p class="eyebrow">What would you like to do?</p>
              <h2>Start with what you need.</h2>
            </div>
            <div class="button-row">
              <a class="button" href="../services.php"
                >Start a service request</a
              >
              <a class="button button-outline" href="reports.php"
                >Report a community issue</a
              >
            </div>
          </div>
        </section>
        <section class="section resident-dashboard">
          <div class="container">
            <div class="stats-grid">
              <article class="card stat-card">
                <p class="stat-label">Awaiting review</p>
                <strong class="stat-value" data-request-stat="awaiting"
                  >00</strong
                >
                <p class="stat-note">Submitted</p>
              </article>
              <article class="card stat-card">
                <p class="stat-label">In progress</p>
                <strong class="stat-value" data-request-stat="in-progress"
                  >00</strong
                >
                <p class="stat-note">Being processed</p>
              </article>
              <article class="card stat-card">
                <p class="stat-label">Completed</p>
                <strong class="stat-value" data-request-stat="completed"
                  >00</strong
                >
                <p class="stat-note">Resolved requests</p>
              </article>
            </div>
            <div class="content-grid">
              <div>
                <article class="card">
                  <div class="section-heading compact">
                    <div>
                      <p class="eyebrow">Your activity</p>
                      <h2>Recent requests</h2>
                    </div>
                    <a
                      class="button button-outline button-small"
                      href="requests.php"
                      >View all</a
                    >
                  </div>
                  <div class="table-wrap">
                    <table>
                      <thead>
                        <tr>
                          <th>Reference</th>
                          <th>Service</th>
                          <th>Last update</th>
                          <th>Status</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody id="dashboard-recent-requests"></tbody>
                    </table>
                  </div>
                </article>
              </div>
              <aside class="stack">
                <article class="card">
                  <p class="eyebrow">Account</p>
                  <h3 data-resident-name>Resident account</h3>
                  <p data-resident-barangay>Bacolor</p>
                  <a class="text-link" href="profile.php"
                    >Review your profile
                    <span class="icon" aria-hidden="true">→</span></a
                  >
                </article>
                <article class="card">
                  <p class="eyebrow">Latest update</p>
                  <h3 id="dashboard-latest-service">No recent requests</h3>
                  <p id="dashboard-latest-note">
                    Submit a service request to track its status here.
                  </p>
                  <a
                    class="text-link"
                    id="dashboard-latest-link"
                    href="../services.php"
                    >Start a request
                    <span class="icon" aria-hidden="true">→</span></a
                  >
                </article>
              </aside>
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
    <script src="../../js/modules/resident.js"></script>
    <script src="../../js/pages/resident-dashboard.js"></script>
  </body>
</html>
