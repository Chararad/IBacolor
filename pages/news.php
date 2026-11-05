<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>News | iBacolor</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/components.css" />
    <link rel="stylesheet" href="../css/responsive.css" />
  </head>
  <body data-page="news">
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
        <section class="page-header">
          <div class="container">
            <div class="breadcrumbs">
              <a href="../index.php">Home</a><span>/</span>News
            </div>
            <p class="eyebrow">Stay informed</p>
            <h1>News and announcements</h1>
            <p class="lead">
              Read municipal advisories, announcements, and community updates
              from Bacolor.
            </p>
          </div>
        </section>
        <section class="section">
          <div class="container">
            <div class="filter-bar news-filter-bar">
              <label
                >Announcement type<select id="news-category-filter">
                  <option>All announcements</option>
                </select></label
              >
              <label>From date<input id="news-date-from" type="date" /></label>
              <label>To date<input id="news-date-to" type="date" /></label>
            </div>
            <p
              id="news-result-count"
              class="results-count"
              aria-live="polite"
            ></p>
            <div class="section-heading news-heading">
              <div>
                <p class="eyebrow">More updates</p>
                <h2>From the municipality</h2>
              </div>
            </div>
            <div class="card-grid card-grid-news">
              <article
                class="card news-card news-card-1"
                data-news-card
                data-category="Announcement"
                data-date="2026-09-18"
              >
                <span class="tag">Announcement</span>
                <time class="meta" datetime="2026-09-18"
                  >September 18, 2026</time
                >
                <h3 id="news-1">
                  Bacolor launches iBacolor public service portal
                </h3>
                <p>
                  Residents can now find municipal services, contact details,
                  and request guidance in one place.
                </p>
                <details class="news-details">
                  <summary>Read full announcement</summary>
                  <img
                    class="announcement-image"
                    src="../assets/bacolor-church.png"
                    alt="Bacolor church shown with the public service portal announcement"
                  />
                  <p>
                    The Municipality of Bacolor has launched a new public-facing
                    portal to help residents understand available access points
                    for government services, municipal offices, and emergency
                    contact information. The portal is intended to make common
                    transactions easier to locate and understand.
                  </p>
                </details>
              </article>
              <article
                class="card news-card news-card-2"
                data-news-card
                data-category="Advisory"
                data-date="2026-09-12"
              >
                <span class="tag">Advisory</span>
                <time class="meta" datetime="2026-09-12"
                  >September 12, 2026</time
                >
                <h3 id="news-2">Scheduled maintenance of municipal offices</h3>
                <p>
                  Selected offices will observe adjusted hours for internal
                  systems maintenance and office servicing.
                </p>
                <details class="news-details">
                  <summary>Read full announcement</summary>
                  <img
                    class="announcement-image"
                    src="../assets/bacolor-church.png"
                    alt="Bacolor church shown with the municipal office maintenance advisory"
                  />
                  <p>
                    Selected municipal offices will adjust service hours during
                    the scheduled maintenance period. Residents are encouraged
                    to plan their transactions and contact the office in advance
                    for urgent concerns.
                  </p>
                </details>
              </article>
              <article
                class="card news-card news-card-3"
                data-news-card
                data-category="Community"
                data-date="2026-09-05"
              >
                <span class="tag">Community</span>
                <time class="meta" datetime="2026-09-05"
                  >September 05, 2026</time
                >
                <h3 id="news-3">Community clean-up drive this October</h3>
                <p>
                  Join the municipal clean-up drive across participating
                  barangays to keep public spaces safe and healthy.
                </p>
                <details class="news-details">
                  <summary>Read full announcement</summary>
                  <img
                    class="announcement-image"
                    src="../assets/bacolor-church.png"
                    alt="Bacolor church shown with the community clean-up announcement"
                  />
                  <p>
                    The municipality is organizing a clean-up drive as part of
                    its environmental and community safety programs. Barangays
                    and residents are encouraged to participate and support
                    public cleanliness initiatives.
                  </p>
                </details>
              </article>
              <article
                class="card news-card news-card-4"
                data-news-card
                data-category="Health"
                data-date="2026-08-28"
              >
                <span class="tag">Health</span>
                <time class="meta" datetime="2026-08-28">August 28, 2026</time>
                <h3 id="news-4">Public health and safety reminders</h3>
                <p>
                  Residents are reminded to coordinate with the Rural Health
                  Unit for preventive services and health records.
                </p>
                <details class="news-details">
                  <summary>Read full announcement</summary>
                  <img
                    class="announcement-image"
                    src="../assets/bacolor-church.png"
                    alt="Bacolor church shown with the public health announcement"
                  />
                  <p>
                    The Municipal Health Office encourages residents to keep
                    updated with health services, preventive care, and public
                    health advisories available through local facilities and
                    barangay coordination.
                  </p>
                </details>
              </article>
            </div>
            <div id="news-empty" class="empty-state" hidden>
              <strong>No announcements match these filters.</strong>
              <p>Change the date range or announcement type.</p>
            </div>
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
    <script src="../js/main.js"></script>
  </body>
</html>
