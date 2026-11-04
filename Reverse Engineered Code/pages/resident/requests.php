<!doctype html>
<html lang="en">
  <!-- Page metadata -->
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>My Requests | iBacolor</title>
    <link rel="stylesheet" href="../../css/style.css" />
    <link rel="stylesheet" href="../../css/components.css" />
    <link rel="stylesheet" href="../../css/responsive.css" />
  </head>
  <body data-page="resident/requests">
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
              <a href="../../main.php">Home</a><span>/</span>My Requests
            </div>
            <p class="eyebrow">Resident portal</p>
            <h1>My requests</h1>
            <p class="lead">
              Track the status of your submitted municipal service requests.
            </p>
          </div>
        </section>
        <section class="section">
          <div class="container">
            <article class="card">
              <div class="table-wrap">
                <table>
                  <thead>
                    <tr>
                      <th>Reference number</th>
                      <th>Service</th>
                      <th>Submitted</th>
                      <th>Last updated</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><strong>IB-2026-00124</strong></td>
                      <td>Business Permit</td>
                      <td>Sep 20, 2026</td>
                      <td>Sep 22, 2026</td>
                      <td>
                        <span class="status status-under-review"
                          >Under Review</span
                        >
                      </td>
                      <td>
                        <a
                          class="button button-small button-outline"
                          href="request-details.php"
                          >View details</a
                        >
                      </td>
                    </tr>
                    <tr>
                      <td><strong>IB-2026-00118</strong></td>
                      <td>Barangay Clearance</td>
                      <td>Sep 15, 2026</td>
                      <td>Sep 16, 2026</td>
                      <td>
                        <span class="status status-resolved">Resolved</span>
                      </td>
                      <td>
                        <a
                          class="button button-small button-outline"
                          href="request-details.php"
                          >View details</a
                        >
                      </td>
                    </tr>
                    <tr>
                      <td><strong>IB-2026-00105</strong></td>
                      <td>Civil Registry Certificate</td>
                      <td>Sep 08, 2026</td>
                      <td>Sep 09, 2026</td>
                      <td>
                        <span class="status status-submitted">Submitted</span>
                      </td>
                      <td>
                        <a
                          class="button button-small button-outline"
                          href="request-details.php"
                          >View details</a
                        >
                      </td>
                    </tr>
                    <tr>
                      <td><strong>IB-2026-00098</strong></td>
                      <td>Building Permit</td>
                      <td>Sep 01, 2026</td>
                      <td>Sep 03, 2026</td>
                      <td>
                        <span class="status status-in-progress"
                          >In Progress</span
                        >
                      </td>
                      <td>
                        <a
                          class="button button-small button-outline"
                          href="request-details.php"
                          >View details</a
                        >
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </article>
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
