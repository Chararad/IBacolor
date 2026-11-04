<!doctype html>
<html lang="en">
  <!-- Page metadata -->
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Resident Dashboard | iBacolor</title>
    <link rel="stylesheet" href="../../css/style.css" />
    <link rel="stylesheet" href="../../css/components.css" />
    <link rel="stylesheet" href="../../css/responsive.css" />
  </head>
  <body data-page="resident/dashboard">
    <div id="app">
      <!-- Site header and navigation -->
      <header class="site-header">
        <div class="container header-inner">
          <a class="brand" href="../../main.php"
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
                ><a href="../resident/profile.php">Profile</a>
              </div>
              <a
                class="button button-small button-outline"
                href="../../main.php"
                >Sign out</a
              >
            </nav>
          </div>
        </div>
      </header>
      <!-- Main page content -->
      <main>
        <section class="page-header">
          <div class="container">
            <div class="breadcrumbs">
              <a href="../../main.php">Home</a><span>/</span>Resident Dashboard
            </div>
            <p class="eyebrow">Resident portal</p>
            <h1>Welcome back, Maria</h1>
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
                <strong class="stat-value">00</strong>
                <p class="stat-note">Submitted</p>
              </article>
              <article class="card stat-card">
                <p class="stat-label">In progress</p>
                <strong class="stat-value">01</strong>
                <p class="stat-note">Being processed</p>
              </article>
              <article class="card stat-card">
                <p class="stat-label">Completed</p>
                <strong class="stat-value">01</strong>
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
                      <tbody>
                        <tr>
                          <td><strong>IB-2026-00124</strong></td>
                          <td>Business Permit</td>
                          <td>Sep 22, 2026</td>
                          <td>
                            <span class="status status-under-review"
                              >Under Review</span
                            >
                          </td>
                          <td>
                            <a class="text-link" href="request-details.php"
                              >View</a
                            >
                          </td>
                        </tr>
                        <tr>
                          <td><strong>IB-2026-00118</strong></td>
                          <td>Barangay Clearance</td>
                          <td>Sep 16, 2026</td>
                          <td>
                            <span class="status status-resolved">Resolved</span>
                          </td>
                          <td>
                            <a class="text-link" href="request-details.php"
                              >View</a
                            >
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </article>
              </div>
              <aside class="stack">
                <article class="card">
                  <p class="eyebrow">Account</p>
                  <h3>Maria Santos</h3>
                  <p>Cabambangan, Bacolor</p>
                  <a class="text-link" href="profile.php"
                    >Review your profile
                    <span class="icon" aria-hidden="true">→</span></a
                  >
                </article>
                <article class="card">
                  <p class="eyebrow">Latest update</p>
                  <h3>Business Permit</h3>
                  <p>
                    Your request <strong>IB-2026-00124</strong> is currently
                    under review.
                  </p>
                  <a class="text-link" href="request-details.php"
                    >View request details
                    <span class="icon" aria-hidden="true">→</span></a
                  >
                </article>
              </aside>
            </div>
          </div>
        </section>
      </main>
      <!-- Site footer -->
      <footer class="site-footer">
        <div class="container footer-grid">
          <div>
            <a class="brand brand-footer" href="../../main.php"
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
            ><a href="../hotlines.php">Emergency hotlines</a>
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
    <!-- Page scripts -->
    <script src="../../js/mock-data.js"></script>
    <script src="../../js/data-store.js"></script>
    <script src="../../js/navigation.js"></script>
    <script src="../../js/main.js"></script>
  </body>
</html>
