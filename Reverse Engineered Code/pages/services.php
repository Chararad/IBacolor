<!doctype html>
<html lang="en">
  <!-- Page metadata -->
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Services | iBacolor</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/components.css" />
    <link rel="stylesheet" href="../css/responsive.css" />
  </head>
  <body data-page="services">
    <div id="app">
      <!-- Site header and navigation -->
      <header class="site-header">
        <div class="container header-inner">
          <a class="brand" href="../main.php"
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
                <a href="../main.php">Home</a><a href="services.php">Services</a
                ><a href="news.php">News</a
                ><a href="hotlines.php">Emergency Hotlines</a>
              </div>
              <a class="button button-small" href="login.php"
                >Resident sign in</a
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
              <a href="../main.php">Home</a><span>/</span>Services
            </div>
            <p class="eyebrow">Public services</p>
            <h1>Municipal services</h1>
            <p class="lead">
              Find the right service, review requirements, and start a request
              when you are ready.
            </p>
          </div>
        </section>
        <section class="section service-directory">
          <div class="container">
            <div class="filter-bar">
              <label
                >Search services<input
                  id="service-search"
                  type="search"
                  placeholder="Search by service, office or keyword"
                  aria-label="Search services"
                />
              </label>
              <label
                >Category<select
                  id="service-category"
                  aria-label="Filter by category"
                >
                  <option>All categories</option>
                  <option>Business</option>
                  <option>Civil Registry</option>
                  <option>Community</option>
                  <option>Engineering</option>
                  <option>Finance</option>
                  <option>Health</option>
                  <option>Social Welfare</option>
                </select>
              </label>
            </div>
            <p id="service-count" class="results-count" aria-live="polite">
              8 services available
            </p>
            <div id="service-list" class="card-grid">
              <article class="card service-card service-card-business">
                <div class="service-card-heading">
                  <div class="service-icon" aria-hidden="true">▣</div>
                  <span class="tag">Business</span>
                </div>
                <h3>Business Permit</h3>
                <p>
                  Apply for a new or renewed permit for operating a business or
                  enterprise in Bacolor.
                </p>
                <p class="service-office">
                  <span>Responsible office</span
                  ><strong>Municipal Treasurer's Office</strong>
                </p>
                <h4>Key requirements</h4>
                <ul class="compact-list">
                  <li>Valid government ID</li>
                  <li>Barangay clearance</li>
                </ul>
                <details class="service-details">
                  <summary>View full requirements and details</summary>
                  <ul class="compact-list">
                    <li>Proof of business address</li>
                    <li>Business registration details</li>
                  </ul>
                  <p>
                    <strong>Fees:</strong> Fee varies depending on business type
                    and category
                  </p>
                  <p>
                    <strong>Processing:</strong> Application review and payment
                    processing are handled through the municipal office.
                  </p>
                  <p>
                    <strong>How to apply:</strong> Submit the completed
                    application and required supporting documents at the office
                    counter or designated municipal intake desk.
                  </p>
                </details>
                <a
                  class="button button-outline"
                  href="resident/request-form.php"
                  >Start request</a
                >
              </article>
              <article class="card service-card service-card-civil-registry">
                <div class="service-card-heading">
                  <div class="service-icon" aria-hidden="true">▤</div>
                  <span class="tag">Civil Registry</span>
                </div>
                <h3>Civil Registry Certificate</h3>
                <p>
                  Request certified copies of birth, marriage, or death
                  certificates and related civil registry records.
                </p>
                <p class="service-office">
                  <span>Responsible office</span
                  ><strong>Municipal Civil Registry Office</strong>
                </p>
                <h4>Key requirements</h4>
                <ul class="compact-list">
                  <li>Valid government ID</li>
                  <li>Completed request form</li>
                </ul>
                <details class="service-details">
                  <summary>View full requirements and details</summary>
                  <ul class="compact-list">
                    <li>Supporting record details</li>
                    <li>Barangay certification when required</li>
                  </ul>
                  <p>
                    <strong>Fees:</strong> Processing fee depends on the type of
                    certificate and request
                  </p>
                  <p>
                    <strong>Processing:</strong> Requests are processed after
                    verification of records and required documentation.
                  </p>
                  <p>
                    <strong>How to apply:</strong> Provide the requested record
                    details and submit the application with any required
                    supporting papers.
                  </p>
                </details>
                <a
                  class="button button-outline"
                  href="resident/request-form.php"
                  >Start request</a
                >
              </article>
              <article class="card service-card service-card-community">
                <div class="service-card-heading">
                  <div class="service-icon" aria-hidden="true">⌂</div>
                  <span class="tag">Community</span>
                </div>
                <h3>Barangay Clearance</h3>
                <p>
                  Secure a barangay clearance for employment, business, or local
                  transactions requiring local certification.
                </p>
                <p class="service-office">
                  <span>Responsible office</span
                  ><strong>Barangay Office</strong>
                </p>
                <h4>Key requirements</h4>
                <ul class="compact-list">
                  <li>Valid government ID</li>
                  <li>Proof of residency</li>
                </ul>
                <details class="service-details">
                  <summary>View full requirements and details</summary>
                  <ul class="compact-list">
                    <li>Barangay certification</li>
                    <li>Application form</li>
                  </ul>
                  <p>
                    <strong>Fees:</strong> Fee may vary depending on the purpose
                    of the clearance
                  </p>
                  <p>
                    <strong>Processing:</strong> Barangay review and approval
                    are required before the clearance is released.
                  </p>
                  <p>
                    <strong>How to apply:</strong> Coordinate with the barangay
                    where you are residing and submit the necessary documents to
                    the local clerk or office.
                  </p>
                </details>
                <a
                  class="button button-outline"
                  href="resident/request-form.php"
                  >Start request</a
                >
              </article>
              <article class="card service-card service-card-engineering">
                <div class="service-card-heading">
                  <div class="service-icon" aria-hidden="true">⌂</div>
                  <span class="tag">Engineering</span>
                </div>
                <h3>Building Permit</h3>
                <p>
                  Secure approval for construction, renovation, or structural
                  works that require municipal engineering review.
                </p>
                <p class="service-office">
                  <span>Responsible office</span
                  ><strong>Municipal Engineering Office</strong>
                </p>
                <h4>Key requirements</h4>
                <ul class="compact-list">
                  <li>Building plans</li>
                  <li>Lot title or authorization</li>
                </ul>
                <details class="service-details">
                  <summary>View full requirements and details</summary>
                  <ul class="compact-list">
                    <li>Valid government ID</li>
                    <li>Scope of work details</li>
                  </ul>
                  <p>
                    <strong>Fees:</strong> Fee varies based on project scope and
                    project cost estimate
                  </p>
                  <p>
                    <strong>Processing:</strong> Project documents are assessed
                    by the engineering office before approval is issued.
                  </p>
                  <p>
                    <strong>How to apply:</strong> Submit the plans and
                    requirements to the engineering office for evaluation and
                    follow-up review.
                  </p>
                </details>
                <a
                  class="button button-outline"
                  href="resident/request-form.php"
                  >Start request</a
                >
              </article>
              <article class="card service-card service-card-finance">
                <div class="service-card-heading">
                  <div class="service-icon" aria-hidden="true">▦</div>
                  <span class="tag">Finance</span>
                </div>
                <h3>Community Tax Certificate</h3>
                <p>
                  Request a cedula or community tax certificate for personal or
                  local compliance purposes.
                </p>
                <p class="service-office">
                  <span>Responsible office</span
                  ><strong>Municipal Treasurer's Office</strong>
                </p>
                <h4>Key requirements</h4>
                <ul class="compact-list">
                  <li>Valid ID</li>
                  <li>Tax declaration or proof of residence</li>
                </ul>
                <details class="service-details">
                  <summary>View full requirements and details</summary>
                  <ul class="compact-list">
                    <li>Application form</li>
                  </ul>
                  <p>
                    <strong>Fees:</strong> Community tax fees apply according to
                    municipal schedule
                  </p>
                  <p>
                    <strong>Processing:</strong> Payment and verification are
                    done at the treasurer's office.
                  </p>
                  <p>
                    <strong>How to apply:</strong> Present the required
                    documents to the municipal treasurer and complete the
                    payment process.
                  </p>
                </details>
                <a
                  class="button button-outline"
                  href="resident/request-form.php"
                  >Start request</a
                >
              </article>
              <article class="card service-card service-card-health">
                <div class="service-card-heading">
                  <div class="service-icon" aria-hidden="true">▦</div>
                  <span class="tag">Health</span>
                </div>
                <h3>Health Certificate</h3>
                <p>
                  Request a health certificate or related public health service
                  required for employment, transactions, or medical clearance.
                </p>
                <p class="service-office">
                  <span>Responsible office</span
                  ><strong>Rural Health Unit</strong>
                </p>
                <h4>Key requirements</h4>
                <ul class="compact-list">
                  <li>Medical request form</li>
                  <li>Medical exam results</li>
                </ul>
                <details class="service-details">
                  <summary>View full requirements and details</summary>
                  <ul class="compact-list">
                    <li>Barangay clearance</li>
                    <li>Official receipt</li>
                  </ul>
                  <p>
                    <strong>Fees:</strong> ₱100–₱200 for health certificates;
                    ₱150–₱500 for laboratory tests depending on service
                  </p>
                  <p>
                    <strong>Processing:</strong> Assessment and medical service
                    processing are handled by the local health unit.
                  </p>
                  <p>
                    <strong>How to apply:</strong> Submit the doctor’s request
                    or medical document and any supporting papers to the health
                    office.
                  </p>
                </details>
                <a
                  class="button button-outline"
                  href="resident/request-form.php"
                  >Start request</a
                >
              </article>
              <article class="card service-card service-card-social-welfare">
                <div class="service-card-heading">
                  <div class="service-icon" aria-hidden="true">▦</div>
                  <span class="tag">Social Welfare</span>
                </div>
                <h3>Social Assistance</h3>
                <p>
                  Request assistance for medical, food, emergency, disaster, and
                  family support programs administered by the municipality.
                </p>
                <p class="service-office">
                  <span>Responsible office</span
                  ><strong
                    >Municipal Social Welfare and Development Office</strong
                  >
                </p>
                <h4>Key requirements</h4>
                <ul class="compact-list">
                  <li>Valid ID</li>
                  <li>Barangay certification</li>
                </ul>
                <details class="service-details">
                  <summary>View full requirements and details</summary>
                  <ul class="compact-list">
                    <li>Medical abstract or prescription where applicable</li>
                    <li>Incident or damage report if needed</li>
                  </ul>
                  <p>
                    <strong>Fees:</strong> No direct fee for government-funded
                    support programs; requirements vary by program
                  </p>
                  <p>
                    <strong>Processing:</strong> Applications are assessed
                    through social work intervention and referral services.
                  </p>
                  <p>
                    <strong>How to apply:</strong> Visit the MSWDO or barangay
                    assistance desk and provide the required documents for
                    evaluation.
                  </p>
                </details>
                <a
                  class="button button-outline"
                  href="resident/request-form.php"
                  >Start request</a
                >
              </article>
              <article class="card service-card service-card-social-welfare">
                <div class="service-card-heading">
                  <div class="service-icon" aria-hidden="true">▦</div>
                  <span class="tag">Social Welfare</span>
                </div>
                <h3>Senior / PWD / Women and Children Assistance</h3>
                <p>
                  Apply for support programs, registration, and inclusion-based
                  assistance for eligible residents.
                </p>
                <p class="service-office">
                  <span>Responsible office</span
                  ><strong
                    >Municipal Social Welfare and Development Office</strong
                  >
                </p>
                <h4>Key requirements</h4>
                <ul class="compact-list">
                  <li>Registration form</li>
                  <li>Valid ID</li>
                </ul>
                <details class="service-details">
                  <summary>View full requirements and details</summary>
                  <ul class="compact-list">
                    <li>Barangay certification</li>
                    <li>Supporting documents if applicable</li>
                  </ul>
                  <p>
                    <strong>Fees:</strong> Free for government-supported
                    programs where applicable
                  </p>
                  <p>
                    <strong>Processing:</strong> Program eligibility is
                    determined by the office after document review and
                    assessment.
                  </p>
                  <p>
                    <strong>How to apply:</strong> Submit the application and
                    required supporting documents for review and case
                    assessment.
                  </p>
                </details>
                <a
                  class="button button-outline"
                  href="resident/request-form.php"
                  >Start request</a
                >
              </article>
            </div>
            <div id="service-empty" class="empty-state" hidden="">
              <strong>No services found.</strong>
              <p>Try a different keyword or category.</p>
            </div>
          </div>
        </section>
      </main>
      <!-- Site footer -->
      <footer class="site-footer">
        <div class="container footer-grid">
          <div>
            <a class="brand brand-footer" href="../main.php"
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
            ><a href="hotlines.php">Emergency hotlines</a>
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
        <a href="../main.php"
          ><span class="icon" aria-hidden="true">⌂</span><span>Home</span></a
        ><a href="../pages/services.php"
          ><span class="icon" aria-hidden="true">▦</span
          ><span>Services</span></a
        ><a class="emergency-action" href="../pages/hotlines.php"
          ><span class="icon" aria-hidden="true">☎</span
          ><span>Emergency</span></a
        ><a href="../pages/news.php"
          ><span class="icon" aria-hidden="true">▤</span><span>Updates</span></a
        ><a href="../pages/login.php"
          ><span class="icon" aria-hidden="true">◯</span><span>Profile</span></a
        >
      </nav>
    </div>
    <!-- Page scripts -->
    <script src="../js/mock-data.js"></script>
    <script src="../js/data-store.js"></script>
    <script src="../js/navigation.js"></script>
    <script src="../js/main.js"></script>
  </body>
</html>
