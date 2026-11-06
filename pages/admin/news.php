<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Manage News | iBacolor</title>
    <link rel="stylesheet" href="../../css/pages/admin-news.css" />
  </head>
  <body data-page="admin/news" class="admin-view">
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
          <a href="../../index.php">← Public website</a>
        </div>
      </aside>
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
              <a href="../../index.php">Home</a><span>/</span>News management
            </div>
            <p class="eyebrow">Administration</p>
            <h1>News management</h1>
            <p class="lead">
              Maintain the public information displayed in the iBacolor portal.
            </p>
          </div>
        </section>
        <section class="section">
          <div class="container">
            <article class="card">
              <div class="section-heading compact">
                <h2>Published news</h2>
                <button class="button" type="button">+ Add new</button>
              </div>
              <div class="table-wrap">
                <table>
                  <thead>
                    <tr>
                      <th>Title</th>
                      <th>Date</th>
                      <th>Type</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <strong
                          >Bacolor launches iBacolor public service
                          portal</strong
                        >
                      </td>
                      <td>September 18, 2026</td>
                      <td>Announcement</td>
                      <td class="table-actions">
                        <button
                          class="button button-small button-outline"
                          type="button"
                        >
                          Edit</button
                        ><button
                          class="button button-small button-ghost"
                          type="button"
                        >
                          Delete
                        </button>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <strong
                          >Scheduled maintenance of municipal offices</strong
                        >
                      </td>
                      <td>September 12, 2026</td>
                      <td>Advisory</td>
                      <td class="table-actions">
                        <button
                          class="button button-small button-outline"
                          type="button"
                        >
                          Edit</button
                        ><button
                          class="button button-small button-ghost"
                          type="button"
                        >
                          Delete
                        </button>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <strong>Community clean-up drive this October</strong>
                      </td>
                      <td>September 05, 2026</td>
                      <td>Community</td>
                      <td class="table-actions">
                        <button
                          class="button button-small button-outline"
                          type="button"
                        >
                          Edit</button
                        ><button
                          class="button button-small button-ghost"
                          type="button"
                        >
                          Delete
                        </button>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <strong>Public health and safety reminders</strong>
                      </td>
                      <td>August 28, 2026</td>
                      <td>Health</td>
                      <td class="table-actions">
                        <button
                          class="button button-small button-outline"
                          type="button"
                        >
                          Edit</button
                        ><button
                          class="button button-small button-ghost"
                          type="button"
                        >
                          Delete
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
    <script src="../../js/mock-data.js"></script>
    <script src="../../js/data-store.js"></script>
    <script src="../../js/navigation.js"></script>
    <script src="../../js/modules/shared.js"></script>
    <script src="../../js/modules/accounts.js"></script>
    <script src="../../js/modules/feedback.js"></script>
    <script src="../../js/pages/admin-news.js"></script>
  </body>
</html>
