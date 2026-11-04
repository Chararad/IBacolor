<!doctype html>
<html lang="en">
  <!-- Page metadata -->
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Manage Reports | iBacolor</title>
    <link rel="stylesheet" href="../../css/style.css" />
    <link rel="stylesheet" href="../../css/components.css" />
    <link rel="stylesheet" href="../../css/responsive.css" />
  </head>
  <body data-page="admin/reports" class="admin-view">
    <div id="app">
      <aside class="admin-sidebar">
        <a class="admin-sidebar-brand" href="../admin/dashboard.php"
          ><img
            class="brand-logo"
            src="../../assets/bacolor-seal.webp"
            alt=""
          /><span><strong>iBacolor</strong><small>Admin portal</small></span></a
        >
        <p class="admin-label">Workspace</p>
        <a href="../admin/dashboard.php">▦ Dashboard</a
        ><a href="../admin/requests.php">▤ Service requests</a
        ><a href="../admin/documents.php">▧ Documents</a
        ><a href="../admin/reports.php">! Community reports</a>
        <p class="admin-label">Content</p>
        <a href="../admin/news.php">✦ News</a
        ><a href="../admin/services.php">▣ Services</a
        ><a href="../admin/hotlines.php">☎ Hotlines</a
        ><a href="../admin/users.php">◯ Users</a>
        <div class="admin-sidebar-footer">
          <a href="../../main.php">← Public website</a>
        </div>
      </aside>
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
            <nav class="site-nav" aria-label="admin navigation">
              <div class="nav-links">
                <a href="../admin/dashboard.php">Dashboard</a
                ><a href="../admin/requests.php">Requests</a
                ><a href="../admin/reports.php">Reports</a
                ><a href="../admin/documents.php">Documents</a
                ><a href="../admin/users.php">Users</a>
                <div class="nav-group">
                  <span>Content management</span
                  ><a href="../admin/news.php">News</a
                  ><a href="../admin/services.php">Services</a
                  ><a href="../admin/hotlines.php">Hotlines</a>
                </div>
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
              <a href="../../main.php">Home</a><span>/</span>Reports
            </div>
            <p class="eyebrow">Administration</p>
            <h1>Community reports</h1>
            <p class="lead">
              Review and respond to issues submitted by residents.
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
                      <th>Report</th>
                      <th>Category</th>
                      <th>Location</th>
                      <th>Submitted</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <strong>CR-2026-0041</strong><br /><small
                          >Road lighting needs repair near the covered
                          court.</small
                        >
                      </td>
                      <td>Damaged roads</td>
                      <td>Barangay Cabambangan</td>
                      <td>Sep 21, 2026</td>
                      <td>
                        <span class="status status-under-review"
                          >Under Review</span
                        >
                      </td>
                      <td class="table-actions">
                        <button
                          class="button button-small button-outline"
                          type="button"
                        >
                          View</button
                        ><button
                          class="button button-small button-ghost"
                          type="button"
                        >
                          Update
                        </button>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <strong>CR-2026-0038</strong><br /><small
                          >Request for additional collection schedule.</small
                        >
                      </td>
                      <td>Garbage disposal</td>
                      <td>Barangay San Isidro</td>
                      <td>Sep 17, 2026</td>
                      <td>
                        <span class="status status-submitted">Submitted</span>
                      </td>
                      <td class="table-actions">
                        <button
                          class="button button-small button-outline"
                          type="button"
                        >
                          View</button
                        ><button
                          class="button button-small button-ghost"
                          type="button"
                        >
                          Update
                        </button>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <strong>CR-2026-0030</strong><br /><small
                          >Drainage issue cleared after cleanup and
                          desilting.</small
                        >
                      </td>
                      <td>Flooding</td>
                      <td>Barangay Santa Barbara</td>
                      <td>Sep 07, 2026</td>
                      <td>
                        <span class="status status-resolved">Resolved</span>
                      </td>
                      <td class="table-actions">
                        <button
                          class="button button-small button-outline"
                          type="button"
                        >
                          View</button
                        ><button
                          class="button button-small button-ghost"
                          type="button"
                        >
                          Update
                        </button>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <strong>CR-2026-0022</strong><br /><small
                          >Streetlight repaired and maintenance
                          completed.</small
                        >
                      </td>
                      <td>Streetlight concern</td>
                      <td>Barangay Poblacion</td>
                      <td>Aug 30, 2026</td>
                      <td><span class="status status-closed">Closed</span></td>
                      <td class="table-actions">
                        <button
                          class="button button-small button-outline"
                          type="button"
                        >
                          View</button
                        ><button
                          class="button button-small button-ghost"
                          type="button"
                        >
                          Update
                        </button>
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
