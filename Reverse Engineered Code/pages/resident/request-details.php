<!doctype html>
<html lang="en">
  <!-- Page metadata -->
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Request Details | iBacolor</title>
    <link rel="stylesheet" href="../../css/style.css" />
    <link rel="stylesheet" href="../../css/components.css" />
    <link rel="stylesheet" href="../../css/responsive.css" />
  </head>
  <body data-page="resident/request-details">
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
              <a href="../../main.php">Home</a><span>/</span>My Requests /
              Details
            </div>
            <p class="eyebrow">Request tracking</p>
            <h1>Business permit request</h1>
            <p class="lead">
              Review request information, requirements, and the latest status
              update.
            </p>
          </div>
        </section>
        <section class="section">
          <div class="container content-grid">
            <div class="stack">
              <article class="card">
                <div class="detail-heading">
                  <div>
                    <p class="eyebrow">Reference number</p>
                    <h2>IB-2026-00124</h2>
                  </div>
                  <span class="status status-under-review">Under Review</span>
                </div>
                <dl class="detail-list">
                  <div>
                    <dt>Service</dt>
                    <dd>Business Permit</dd>
                  </div>
                  <div>
                    <dt>Date submitted</dt>
                    <dd>September 20, 2026</dd>
                  </div>
                  <div>
                    <dt>Applicant</dt>
                    <dd>Maria Santos</dd>
                  </div>
                </dl>
              </article>
              <article class="card">
                <h2>Request timeline</h2>
                <ol class="timeline">
                  <li class="done">
                    <strong>Request submitted</strong
                    ><span>September 20, 2026 · 10:24 AM</span>
                  </li>
                  <li class="current">
                    <strong>Under review</strong
                    ><span>September 22, 2026 · Municipal permits office</span>
                  </li>
                  <li>
                    <strong>For release</strong><span>Awaiting completion</span>
                  </li>
                </ol>
              </article>
            </div>
            <aside class="stack">
              <article class="card">
                <h3>Uploaded documents</h3>
                <div class="file-row">
                  <span
                    ><span class="icon" aria-hidden="true">▧</span> Valid
                    Government ID</span
                  ><span class="status status-verified">Verified</span>
                </div>
                <div class="file-row">
                  <span
                    ><span class="icon" aria-hidden="true">▧</span> Business
                    application form</span
                  ><span class="status status-for-review">For Review</span>
                </div>
                <button
                  class="button button-outline button-block"
                  type="button"
                >
                  Upload another document
                </button>
              </article>
              <article class="card">
                <h3>Need help?</h3>
                <p>
                  Contact the Municipal Hall if you have questions about this
                  request.
                </p>
                <a class="text-link" href="../hotlines.php"
                  >View contact details
                  <span class="icon" aria-hidden="true">→</span></a
                >
              </article>
            </aside>
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
