"use strict";

const SESSION_KEY = "ibacolor.mock-session.v1";
const ACCOUNTS_KEY = "ibacolor.mock-accounts.v1";

/* BEGIN OPTIONAL BLOCK: Mobile navigation */
// Mobile navigation: opens and closes the menu with keyboard support.
function initializeMobileNavigation() {
  const toggle = document.querySelector(".menu-toggle");
  const navigation = document.getElementById("main-nav");
  if (!toggle || !navigation) return;

  toggle.addEventListener("click", () => {
    const isOpen = document.body.classList.toggle("nav-open");
    toggle.setAttribute("aria-expanded", String(isOpen));
  });

  navigation.addEventListener("click", (event) => {
    if (!event.target.closest("a")) return;
    document.body.classList.remove("nav-open");
    toggle.setAttribute("aria-expanded", "false");
  });

  document.addEventListener("keydown", (event) => {
    if (event.key !== "Escape" || !document.body.classList.contains("nav-open"))
      return;
    document.body.classList.remove("nav-open");
    toggle.setAttribute("aria-expanded", "false");
    toggle.focus();
  });
}
/* END OPTIONAL BLOCK: Mobile navigation */

/* BEGIN OPTIONAL BLOCK: Barangay dropdowns */
function populateBarangaySelects() {
  document.querySelectorAll("[data-barangays]").forEach((select) => {
    const currentValue = select.value;
    const placeholder = select.dataset.placeholder || "Select barangay";
    select.replaceChildren(new Option(placeholder, ""));
    window.MOCK_DATA.barangays.forEach((barangay) => {
      select.add(new Option(barangay, barangay));
    });
    if (window.MOCK_DATA.barangays.includes(currentValue))
      select.value = currentValue;
  });
}
/* END OPTIONAL BLOCK: Barangay dropdowns */

/* BEGIN OPTIONAL BLOCK: Service directory */
// Service directory: builds service cards and filters them by search and category.
function initializeServiceDirectory() {
  const search = document.getElementById("service-search");
  const category = document.getElementById("service-category");
  const list = document.getElementById("service-list");
  const empty = document.getElementById("service-empty");
  const count = document.getElementById("service-count");
  if (!search || !category || !list || !empty || !count) return;

  const categories = [
    ...new Set(window.MOCK_DATA.services.map((service) => service.category)),
  ].sort();
  category.replaceChildren(new Option("All categories", "All categories"));
  categories.forEach((name) => category.add(new Option(name, name)));

  list.replaceChildren(
    ...window.MOCK_DATA.services.map((service) => {
      const card = document.createElement("article");
      card.className = "card service-card";
      card.dataset.serviceId = service.id;

      const tag = document.createElement("span");
      tag.className = "tag";
      tag.textContent = service.category;
      const title = document.createElement("h3");
      title.textContent = service.name;
      const description = document.createElement("p");
      description.textContent = service.description;
      const office = document.createElement("p");
      office.className = "service-office";
      const officeLabel = document.createElement("span");
      officeLabel.textContent = "Responsible office";
      const officeName = document.createElement("strong");
      officeName.textContent = service.office;
      office.append(officeLabel, officeName);

      const details = document.createElement("details");
      details.className = "service-details";
      const summary = document.createElement("summary");
      summary.textContent = "View requirements and how to apply";
      const requirements = document.createElement("ul");
      requirements.className = "compact-list";
      service.requirements.forEach((requirement) => {
        const item = document.createElement("li");
        item.textContent = requirement;
        requirements.append(item);
      });
      const fees = document.createElement("p");
      fees.textContent = `Fees: ${service.fees}`;
      const processing = document.createElement("p");
      processing.textContent = `Processing: ${service.processing}`;
      const instructions = document.createElement("p");
      instructions.textContent = `How to apply: ${service.instructions}`;
      details.append(summary, requirements, fees, processing, instructions);

      const start = document.createElement("a");
      start.className = "button button-outline";
      start.href = `resident/request-form.php?service=${encodeURIComponent(service.id)}`;
      start.textContent = "Start request";
      card.append(tag, title, description, office, details, start);
      return card;
    }),
  );

  const filterServices = () => {
    const keyword = search.value.trim().toLowerCase();
    const selectedCategory = category.value;
    let visibleCount = 0;

    list.querySelectorAll(".service-card").forEach((card, index) => {
      const service = window.MOCK_DATA.services[index];
      const searchText = [
        service.name,
        service.description,
        service.category,
        service.office,
        ...service.requirements,
      ]
        .join(" ")
        .toLowerCase();
      const matches =
        (!keyword || searchText.includes(keyword)) &&
        (selectedCategory === "All categories" ||
          service.category === selectedCategory);
      card.hidden = !matches;
      if (matches) visibleCount += 1;
    });

    count.textContent = `${visibleCount} service${visibleCount === 1 ? "" : "s"} available`;
    empty.hidden = visibleCount !== 0;
  };

  search.addEventListener("input", filterServices);
  category.addEventListener("change", filterServices);
  const parameters = new URLSearchParams(window.location.search);
  const query = parameters.get("q");
  const selectedService = parameters.get("service");
  if (query) search.value = query;
  if (selectedService) {
    const serviceIndex = window.MOCK_DATA.services.findIndex(
      (service) => service.id === selectedService,
    );
    if (serviceIndex >= 0)
      search.value = window.MOCK_DATA.services[serviceIndex].name;
  }
  filterServices();
}
/* END OPTIONAL BLOCK: Service directory */

/* BEGIN OPTIONAL BLOCK: Admin request filters */
// Admin request filters: narrows the request table by keyword and status.
function initializeRequestFilters() {
  const search = document.getElementById("admin-request-search");
  const status = document.getElementById("admin-request-status");
  const count = document.getElementById("admin-request-count");
  const rows = [...document.querySelectorAll(".table-wrap tbody tr")];
  if (!search || !status || !count) return;

  const filterRequests = () => {
    const keyword = search.value.trim().toLowerCase();
    let visibleCount = 0;
    rows.forEach((row) => {
      const rowStatus = row.querySelector(".status")?.textContent.trim();
      const matches =
        row.textContent.toLowerCase().includes(keyword) &&
        (status.value === "All statuses" || rowStatus === status.value);
      row.hidden = !matches;
      if (matches) visibleCount += 1;
    });
    count.textContent = `${visibleCount} of ${rows.length} requests shown`;
  };

  search.addEventListener("input", filterRequests);
  status.addEventListener("change", filterRequests);
  filterRequests();
}
/* END OPTIONAL BLOCK: Admin request filters */

/* BEGIN SHARED SUPPORT BLOCK: Date and time formatting */
function formatDateTime(value) {
  const date = new Date(value);
  return Number.isNaN(date.getTime())
    ? value
    : date.toLocaleString("en-PH", {
        dateStyle: "medium",
        timeStyle: "short",
      });
}
/* END SHARED SUPPORT BLOCK: Date and time formatting */

/* BEGIN OPTIONAL BLOCK: Community reports */
// Community reports: submits browser-stored reports and displays the resident's history.
function initializeReports() {
  const form = document.getElementById("community-report-form");
  const list = document.getElementById("my-report-list");
  const filter = document.getElementById("report-status-filter");
  const count = document.getElementById("report-count");
  if (!form || !list || !filter || !count) return;

  const category = form.querySelector("#report-category");
  category.replaceChildren(new Option("Select a category", ""));
  window.MOCK_DATA.reportCategories.forEach((name) =>
    category.add(new Option(name, name)),
  );
  category.add(new Option("Other — enter your own category", "Other"));
  const customCategoryLabel = document.getElementById(
    "custom-report-category-label",
  );
  const customCategory = document.getElementById("custom-report-category");
  category.addEventListener("change", () => {
    const isCustom = category.value === "Other";
    customCategoryLabel.hidden = !isCustom;
    customCategory.disabled = !isCustom;
    customCategory.required = isCustom;
  });
  const observedAt = form.elements.observedAt;
  if (!observedAt.value) {
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    observedAt.value = now.toISOString().slice(0, 16);
  }

  const render = () => {
    const selectedStatus = filter.value;
    const currentEmail = sessionStorage.getItem(SESSION_KEY);
    const reports = window.IBacolorStore.get("reports").filter(
      (report) => report.ownerEmail === currentEmail,
    );
    const visibleReports = reports.filter(
      (report) =>
        selectedStatus === "All statuses" || report.status === selectedStatus,
    );
    count.textContent = `${visibleReports.length} report${visibleReports.length === 1 ? "" : "s"}`;
    list.replaceChildren();

    visibleReports.forEach((report) => {
      const item = document.createElement("article");
      item.className = "report-item";
      const heading = document.createElement("div");
      heading.className = "report-heading";
      const reference = document.createElement("strong");
      reference.textContent = report.id;
      const status = document.createElement("span");
      status.className = `status status-${report.status.toLowerCase().replaceAll(" ", "-")}`;
      status.textContent = report.status;
      heading.append(reference, status);
      const metadata = document.createElement("span");
      metadata.textContent = `${report.category} · Submitted ${formatDateTime(report.submitted)}${report.observedAt ? ` · Observed ${formatDateTime(report.observedAt)}` : ""}`;
      const description = document.createElement("p");
      description.textContent = report.summary;
      const location = document.createElement("span");
      location.textContent = `${report.location} · Frequency: ${report.frequency || "Not specified"}`;
      item.append(heading, metadata, description, location);
      list.append(item);
    });
  };

  filter.addEventListener("change", render);
  form.addEventListener("submit", (event) => {
    event.preventDefault();
    if (!form.reportValidity()) return;
    const data = new FormData(form);
    const reports = window.IBacolorStore.get("reports");
    const nextNumber =
      reports.reduce((highest, report) => {
        const match = report.id.match(/(\d+)$/);
        return match ? Math.max(highest, Number(match[1])) : highest;
      }, 0) + 1;
    window.IBacolorStore.add("reports", {
      id: `CR-${new Date().getFullYear()}-${String(nextNumber).padStart(4, "0")}`,
      category:
        data.get("category") === "Other"
          ? data.get("categoryOther")
          : data.get("category"),
      barangay: data.get("barangay"),
      location: `Barangay ${data.get("barangay")}${data.get("location") ? `, ${data.get("location")}` : ""}`,
      submitted: new Date().toISOString(),
      observedAt: data.get("observedAt"),
      status: "Submitted",
      summary: data.get("description"),
      frequency: data.get("frequency"),
      ownerEmail: sessionStorage.getItem(SESSION_KEY),
      remarks: "Received and awaiting review.",
    });
    form.reset();
    category.dispatchEvent(new Event("change"));
    render();
    const confirmation = document.getElementById("report-confirmation");
    confirmation.textContent =
      "Your report was saved in this browser and is awaiting review.";
    confirmation.hidden = false;
  });
  render();
}
/* END OPTIONAL BLOCK: Community reports */

/* BEGIN OPTIONAL BLOCK: News filters */
// News filters: filters announcements by category and date range.
function initializeNewsFilters() {
  const cards = [...document.querySelectorAll("[data-news-card]")];
  const category = document.getElementById("news-category-filter");
  const from = document.getElementById("news-date-from");
  const to = document.getElementById("news-date-to");
  const count = document.getElementById("news-result-count");
  const empty = document.getElementById("news-empty");
  if (!cards.length || !category || !from || !to || !count || !empty) return;

  const categories = [
    ...new Set(cards.map((card) => card.dataset.category)),
  ].sort();
  categories.forEach((name) => category.add(new Option(name, name)));
  const applyFilters = () => {
    let shown = 0;
    cards.forEach((card) => {
      const date = card.dataset.date;
      const matches =
        (category.value === "All announcements" ||
          category.value === card.dataset.category) &&
        (!from.value || date >= from.value) &&
        (!to.value || date <= to.value);
      card.hidden = !matches;
      if (matches) shown += 1;
    });
    count.textContent = `${shown} announcement${shown === 1 ? "" : "s"} shown`;
    empty.hidden = shown !== 0;
  };
  [category, from, to].forEach((control) =>
    control.addEventListener("change", applyFilters),
  );
  applyFilters();
}
/* END OPTIONAL BLOCK: News filters */

/* BEGIN OPTIONAL BLOCK: Contact directory */
// Contact directory: renders verified office contacts and barangay contact cards.
function renderDirectory() {
  const offices = document.getElementById("office-directory");
  if (offices) {
    offices.replaceChildren(
      ...window.MOCK_DATA.officeDirectory.map((entry) => {
        const card = document.createElement("article");
        card.className = "card office-card";
        const title = document.createElement("h3");
        title.textContent = entry.office;
        const category = document.createElement("p");
        category.className = "meta";
        category.textContent = entry.category;
        card.append(title, category);
        [
          ["Office contact", entry.official],
          ["Telephone", entry.telephone],
          ["Email", entry.email],
          ["Location", entry.location],
          [
            "Office-specific social media",
            entry.social?.startsWith("https://")
              ? entry.social
              : "No verified office-specific social media page",
          ],
          ["Municipal Facebook", window.MOCK_DATA.municipality.facebook],
          [
            "Municipal website",
            entry.website?.startsWith("https://")
              ? entry.website
              : window.MOCK_DATA.municipality.website,
          ],
        ].forEach(([label, value]) => {
          const paragraph = document.createElement("p");
          const strong = document.createElement("strong");
          strong.textContent = `${label}: `;
          const normalized = value || "Contact details to be confirmed";
          const link = document.createElement("a");
          if (label === "Email" && normalized.includes("@")) {
            link.href = `mailto:${normalized}`;
            link.textContent = normalized;
            paragraph.append(strong, link);
          } else if (
            label === "Telephone" &&
            /^[+()\d\s-]+$/.test(normalized)
          ) {
            link.href = `tel:${normalized.replace(/[^\d+]/g, "")}`;
            link.textContent = normalized;
            paragraph.append(strong, link);
          } else if (
            [
              "Office-specific social media",
              "Municipal Facebook",
              "Municipal website",
            ].includes(label) &&
            normalized.startsWith("https://")
          ) {
            link.href = normalized;
            link.target = "_blank";
            link.rel = "noreferrer";
            link.textContent = "Open official page";
            paragraph.append(strong, link);
          } else {
            paragraph.append(strong, document.createTextNode(normalized));
          }
          card.append(paragraph);
        });
        const services = document.createElement("p");
        services.textContent = `Services: ${entry.services.join(", ")}`;
        card.append(services);
        return card;
      }),
    );
  }

  const barangayList = document.getElementById("barangay-contacts");
  if (barangayList) {
    barangayList.replaceChildren(
      ...window.MOCK_DATA.barangays.map((name) => {
        const card = document.createElement("article");
        card.className = "card barangay-contact-card";
        const title = document.createElement("h3");
        title.textContent = `Barangay ${name}`;
        const contact = document.createElement("p");
        contact.textContent =
          "Contact the barangay hall for local assistance. Phone and social media details are awaiting official confirmation.";
        card.append(title, contact);
        return card;
      }),
    );
  }
}
/* END OPTIONAL BLOCK: Contact directory */

/* BEGIN OPTIONAL BLOCK: Registration, sign-in, and resident account settings */
function redirectToLogin() {
  const loginPath = window.location.pathname.includes("/resident/")
    ? "../login.php"
    : "login.php";
  const login = new URL(loginPath, window.location.href);
  login.searchParams.set("next", window.location.href);
  window.location.replace(login.href);
}

// Account access: fills resident details, handles sign-in state, and processes registration.
function initializeAccountAccess() {
  const page = document.body.dataset.page;
  const protectedPages = new Set([
    "services",
    "resident/dashboard",
    "resident/profile",
    "resident/settings",
    "resident/reports",
    "resident/requests",
    "resident/request-form",
    "resident/request-details",
  ]);
  const activeEmail = sessionStorage.getItem(SESSION_KEY);
  const storedAccounts = JSON.parse(localStorage.getItem(ACCOUNTS_KEY) || "[]");
  const activeAccount = storedAccounts.find(
    (account) => account.email === activeEmail,
  );
  if (protectedPages.has(page) && !activeAccount) {
    redirectToLogin();
    return;
  }

  if (activeAccount) {
    document.querySelectorAll("[data-profile-field]").forEach((field) => {
      field.value = activeAccount[field.dataset.profileField] || "";
    });
    document.querySelectorAll("[data-profile-name]").forEach((element) => {
      element.textContent = activeAccount.name;
    });
    document.querySelectorAll("[data-resident-name]").forEach((element) => {
      element.textContent = activeAccount.name;
    });
    document.querySelectorAll("[data-resident-greeting]").forEach((element) => {
      element.textContent = `Welcome back, ${activeAccount.firstName}`;
    });
    document.querySelectorAll("[data-resident-barangay]").forEach((element) => {
      element.textContent = `${activeAccount.barangay}, Bacolor`;
    });
  }

  document
    .querySelectorAll(
      "[data-sign-out], body[data-page^='resident'] .site-nav .button-outline",
    )
    .forEach((button) => {
      button.addEventListener("click", (event) => {
        event.preventDefault();
        sessionStorage.removeItem(SESSION_KEY);
        window.location.assign(button.href || "../../index.php");
      });
    });

  const registerForm = document.getElementById("registration-form");
  if (registerForm) {
    registerForm.addEventListener("submit", async (event) => {
      event.preventDefault();
      if (!registerForm.reportValidity()) return;
      const status = document.getElementById("registration-status");
      const data = new FormData(registerForm);
      if (data.get("password") !== data.get("confirmPassword")) {
        status.textContent = "The passwords do not match.";
        status.hidden = false;
        return;
      }
      if (!registerForm.dataset.latitude || !registerForm.dataset.longitude) {
        status.textContent =
          "Allow location access and capture your location before creating an account.";
        status.hidden = false;
        return;
      }
      if (!window.crypto?.subtle) {
        status.textContent =
          "Secure account creation requires a secure browser context (HTTPS or localhost).";
        status.hidden = false;
        return;
      }

      const email = String(data.get("email")).trim().toLowerCase();
      const accounts = JSON.parse(localStorage.getItem(ACCOUNTS_KEY) || "[]");
      if (accounts.some((account) => account.email === email)) {
        status.textContent =
          "An account with this email already exists. Please sign in.";
        status.hidden = false;
        return;
      }
      const salt = window.crypto.randomUUID();
      let passwordHash;
      try {
        passwordHash = await hashPassword(salt, String(data.get("password")));
      } catch (error) {
        console.error("Could not securely hash the password.", error);
        status.textContent =
          "The password could not be secured. Please try again.";
        status.hidden = false;
        return;
      }
      accounts.push({
        email,
        salt,
        passwordHash,
        name: `${data.get("firstName")} ${data.get("lastName")}`,
        firstName: data.get("firstName"),
        middleName: data.get("middleName"),
        lastName: data.get("lastName"),
        mobile: data.get("mobile"),
        barangay: data.get("barangay"),
        address: data.get("address"),
        location: {
          latitude: registerForm.dataset.latitude,
          longitude: registerForm.dataset.longitude,
        },
      });
      localStorage.setItem(ACCOUNTS_KEY, JSON.stringify(accounts));
      sessionStorage.setItem(SESSION_KEY, email);
      window.location.assign("resident/dashboard.php");
    });
  }

  const loginForm = document.getElementById("login-form");
  if (loginForm) {
    loginForm.addEventListener("submit", async (event) => {
      event.preventDefault();
      if (!loginForm.reportValidity()) return;
      const status = document.getElementById("login-status");
      const data = new FormData(loginForm);
      const email = String(data.get("email")).trim().toLowerCase();
      const accounts = JSON.parse(localStorage.getItem(ACCOUNTS_KEY) || "[]");
      const account = accounts.find((item) => item.email === email);
      if (!account || !window.crypto?.subtle) {
        status.textContent = account
          ? "Secure sign-in requires HTTPS or localhost."
          : "No account was found for that email. Create a resident account first.";
        status.hidden = false;
        return;
      }
      let attemptedHash;
      try {
        attemptedHash = await hashPassword(
          account.salt,
          String(data.get("password")),
        );
      } catch (error) {
        console.error("Could not verify the password.", error);
        status.textContent =
          "Sign-in could not be completed. Please try again.";
        status.hidden = false;
        return;
      }
      if (attemptedHash !== account.passwordHash) {
        status.textContent = "The email or password is incorrect.";
        status.hidden = false;
        return;
      }
      sessionStorage.setItem(SESSION_KEY, email);
      const next = new URLSearchParams(window.location.search).get("next");
      let destination = new URL("resident/dashboard.php", window.location.href);
      if (next) {
        try {
          const requestedDestination = new URL(next, window.location.href);
          if (
            requestedDestination.protocol === window.location.protocol &&
            requestedDestination.host === window.location.host
          ) {
            destination = requestedDestination;
          }
        } catch (error) {
          console.error("The requested return address was invalid.", error);
        }
      }
      window.location.assign(destination.href);
    });
  }

  const settingsForm = document.getElementById("settings-form");
  if (settingsForm) {
    const email = sessionStorage.getItem(SESSION_KEY);
    const settingsKey = `ibacolor.settings.${email}`;
    const savedSettings = JSON.parse(localStorage.getItem(settingsKey) || "{}");
    settingsForm.elements.requestNotifications.checked =
      savedSettings.requestNotifications !== false;
    settingsForm.elements.announcementNotifications.checked =
      savedSettings.announcementNotifications !== false;
    settingsForm.addEventListener("submit", async (event) => {
      event.preventDefault();
      if (!settingsForm.reportValidity()) return;
      const status = document.getElementById("settings-status");
      const data = new FormData(settingsForm);
      if (data.get("newPassword") !== data.get("confirmPassword")) {
        status.textContent = "The new passwords do not match.";
        status.hidden = false;
        return;
      }
      const accounts = JSON.parse(localStorage.getItem(ACCOUNTS_KEY) || "[]");
      const account = accounts.find((item) => item.email === email);
      if (!account || !window.crypto?.subtle) {
        status.textContent =
          "The account could not be verified in this browser session.";
        status.hidden = false;
        return;
      }
      const currentHash = await hashPassword(
        account.salt,
        String(data.get("currentPassword")),
      );
      if (currentHash !== account.passwordHash) {
        status.textContent = "The current password is incorrect.";
        status.hidden = false;
        return;
      }
      account.salt = window.crypto.randomUUID();
      account.passwordHash = await hashPassword(
        account.salt,
        String(data.get("newPassword")),
      );
      localStorage.setItem(ACCOUNTS_KEY, JSON.stringify(accounts));
      localStorage.setItem(
        settingsKey,
        JSON.stringify({
          requestNotifications: data.has("requestNotifications"),
          announcementNotifications: data.has("announcementNotifications"),
        }),
      );
      status.textContent = "Settings were saved in this browser.";
      status.hidden = false;
      settingsForm.reset();
    });
  }

  const profileForm = document.getElementById("profile-form");
  if (profileForm) {
    profileForm.addEventListener("submit", (event) => {
      event.preventDefault();
      if (!profileForm.reportValidity()) return;
      const email = sessionStorage.getItem(SESSION_KEY);
      const accounts = JSON.parse(localStorage.getItem(ACCOUNTS_KEY) || "[]");
      const account = accounts.find((item) => item.email === email);
      if (!account) {
        const status = document.getElementById("profile-status");
        status.textContent = "Your account information could not be loaded.";
        status.hidden = false;
        return;
      }
      const data = new FormData(profileForm);
      ["firstName", "lastName", "mobile", "barangay", "address"].forEach(
        (field) => {
          account[field] = data.get(field);
        },
      );
      account.name = `${account.firstName} ${account.lastName}`;
      localStorage.setItem(ACCOUNTS_KEY, JSON.stringify(accounts));
      document.querySelector("[data-profile-name]").textContent = account.name;
      const status = document.getElementById("profile-status");
      status.textContent = "Profile changes were saved in this browser.";
      status.hidden = false;
    });
  }

  document
    .querySelectorAll("#request-form [data-service-select]")
    .forEach((select) => {
      select.replaceChildren(new Option("Select a service", ""));
      window.MOCK_DATA.services.forEach((service) =>
        select.add(new Option(service.name, service.id)),
      );
      const requestedService = new URLSearchParams(window.location.search).get(
        "service",
      );
      if (
        requestedService &&
        window.MOCK_DATA.services.some(
          (service) => service.id === requestedService,
        )
      ) {
        select.value = requestedService;
      }
    });

  const locationButton = document.getElementById("capture-location");
  const reportForm = document.getElementById("registration-form");
  if (locationButton && reportForm) {
    locationButton.addEventListener("click", () => {
      const status = document.getElementById("location-status");
      if (!navigator.geolocation) {
        status.textContent = "This browser does not support location access.";
        status.hidden = false;
        return;
      }
      status.textContent = "Requesting location permission…";
      status.hidden = false;
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const { latitude, longitude } = position.coords;
          reportForm.dataset.latitude = String(latitude);
          reportForm.dataset.longitude = String(longitude);
          status.textContent = `Location pinned at ${latitude.toFixed(5)}, ${longitude.toFixed(5)}.`;
        },
        (error) => {
          const reasons = {
            1: "Location permission was denied. Allow it in your browser settings to register.",
            2: "Your location could not be determined. Try again from a place with a location signal.",
            3: "Location lookup timed out. Please try again.",
          };
          status.textContent =
            reasons[error.code] ||
            "Location could not be captured. Please try again.";
        },
        { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 },
      );
    });
  }
}

async function hashPassword(salt, password) {
  const bytes = new TextEncoder().encode(`${salt}:${password}`);
  const digest = await window.crypto.subtle.digest("SHA-256", bytes);
  return [...new Uint8Array(digest)]
    .map((byte) => byte.toString(16).padStart(2, "0"))
    .join("");
}
/* END OPTIONAL BLOCK: Registration, sign-in, and resident account settings */

/* BEGIN OPTIONAL BLOCK: Service request submission and resident request pages */
// Request submission: validates and saves a service request in the browser data store.
function initializeRequestSubmission() {
  const form = document.getElementById("request-form");
  if (!form) return;
  form.addEventListener("submit", (event) => {
    event.preventDefault();
    if (!form.reportValidity()) return;
    const data = new FormData(form);
    const requests = window.IBacolorStore.get("requests");
    const number = requests.length + 125;
    window.IBacolorStore.add("requests", {
      reference: `IB-${new Date().getFullYear()}-${String(number).padStart(5, "0")}`,
      service:
        window.MOCK_DATA.services.find(
          (service) => service.id === data.get("service"),
        )?.name || "Service request",
      submitted: new Date().toISOString(),
      updated: new Date().toISOString(),
      status: "Submitted",
      applicant: data.get("firstName"),
      ownerEmail: sessionStorage.getItem(SESSION_KEY),
      mobile: data.get("mobile"),
      barangay: data.get("barangay"),
      details: data.get("details"),
      remarks: "Request received and awaiting review.",
    });
    const status = document.getElementById("request-confirmation");
    status.textContent =
      "Your request was saved in this browser. You can track it from My Requests.";
    status.hidden = false;
    form.reset();
  });
}

// Resident requests: renders the signed-in resident's request list and detail links.
function initializeResidentRequests() {
  const list = document.getElementById("resident-request-list");
  const empty = document.getElementById("resident-request-empty");
  if (!list || !empty) return;
  const requests = getResidentRequests();
  list.replaceChildren(
    ...requests.map((request) => {
      const row = document.createElement("tr");
      const values = [
        request.reference,
        request.service,
        formatDateTime(request.submitted),
        formatDateTime(request.updated),
      ];
      values.forEach((value, index) => {
        const cell = document.createElement("td");
        if (index === 0) {
          const reference = document.createElement("strong");
          reference.textContent = value;
          cell.append(reference);
        } else {
          cell.textContent = value;
        }
        row.append(cell);
      });
      const statusCell = document.createElement("td");
      const status = document.createElement("span");
      status.className = `status status-${request.status.toLowerCase().replaceAll(" ", "-")}`;
      status.textContent = request.status;
      statusCell.append(status);
      const action = document.createElement("td");
      const detailLink = document.createElement("a");
      detailLink.className = "button button-small button-outline";
      detailLink.href = `request-details.php?reference=${encodeURIComponent(request.reference)}`;
      detailLink.textContent = "View details";
      action.append(detailLink);
      row.append(statusCell, action);
      return row;
    }),
  );
  empty.hidden = requests.length !== 0;
}

function getResidentRequests() {
  const email = sessionStorage.getItem(SESSION_KEY);
  return window.IBacolorStore.get("requests")
    .filter((request) => request.ownerEmail === email)
    .sort(
      (left, right) =>
        new Date(right.submitted).getTime() -
        new Date(left.submitted).getTime(),
    );
}

// Resident dashboard: shows request totals, recent activity, and the latest request.
function initializeResidentDashboard() {
  const list = document.getElementById("dashboard-recent-requests");
  if (!list) return;
  const requests = getResidentRequests();
  const statuses = requests.map((request) => request.status.toLowerCase());
  const statValues = {
    awaiting: statuses.filter((status) =>
      ["submitted", "under review", "reviewing"].includes(status),
    ).length,
    "in-progress": statuses.filter((status) =>
      ["on process", "in progress"].includes(status),
    ).length,
    completed: statuses.filter((status) =>
      ["done", "resolved", "closed", "completed"].includes(status),
    ).length,
  };
  Object.entries(statValues).forEach(([key, value]) => {
    const element = document.querySelector(`[data-request-stat="${key}"]`);
    if (element) element.textContent = String(value).padStart(2, "0");
  });

  const recent = requests.slice(0, 5);
  if (recent.length === 0) {
    const row = document.createElement("tr");
    const cell = document.createElement("td");
    cell.colSpan = 5;
    cell.textContent =
      "No requests yet. Start a service request to see your activity here.";
    row.append(cell);
    list.replaceChildren(row);
  } else {
    list.replaceChildren(
      ...recent.map((request) => {
        const row = document.createElement("tr");
        const referenceCell = document.createElement("td");
        const reference = document.createElement("strong");
        reference.textContent = request.reference;
        referenceCell.append(reference);
        const service = document.createElement("td");
        service.textContent = request.service;
        const updated = document.createElement("td");
        updated.textContent = formatDateTime(request.updated);
        const statusCell = document.createElement("td");
        const status = document.createElement("span");
        status.className = `status status-${request.status.toLowerCase().replaceAll(" ", "-")}`;
        status.textContent = request.status;
        statusCell.append(status);
        const action = document.createElement("td");
        const link = document.createElement("a");
        link.className = "text-link";
        link.href = `request-details.php?reference=${encodeURIComponent(request.reference)}`;
        link.textContent = "View";
        action.append(link);
        row.append(referenceCell, service, updated, statusCell, action);
        return row;
      }),
    );
  }

  const latest = requests[0];
  const latestService = document.getElementById("dashboard-latest-service");
  const latestNote = document.getElementById("dashboard-latest-note");
  const latestLink = document.getElementById("dashboard-latest-link");
  if (latest && latestService && latestNote && latestLink) {
    latestService.textContent = latest.service;
    latestNote.textContent = `Request ${latest.reference} is ${latest.status.toLowerCase()}.`;
    latestLink.href = `request-details.php?reference=${encodeURIComponent(latest.reference)}`;
    latestLink.textContent = "View request details";
  }
}

// Request details: displays the selected request when it belongs to the signed-in resident.
function initializeResidentRequestDetails() {
  const container = document.getElementById("resident-request-detail");
  const reference = new URLSearchParams(window.location.search).get(
    "reference",
  );
  if (!container || !reference) return;
  const email = sessionStorage.getItem(SESSION_KEY);
  const request = window.IBacolorStore.get("requests").find(
    (item) => item.reference === reference && item.ownerEmail === email,
  );
  const card = document.createElement("article");
  card.className = "card";
  const title = document.createElement("h2");
  title.textContent = request ? request.service : "Request not found";
  const description = document.createElement("p");
  description.textContent = request
    ? `Reference ${request.reference} · Submitted ${formatDateTime(request.submitted)} · Status: ${request.status}`
    : "This request could not be found in your account.";
  card.append(title, description);
  if (request?.details) {
    const details = document.createElement("p");
    details.textContent = request.details;
    card.append(details);
  }
  const back = document.createElement("a");
  back.className = "button button-outline";
  back.href = "requests.php";
  back.textContent = "Back to my requests";
  card.append(back);
  container.replaceChildren(card);
}
/* END OPTIONAL BLOCK: Service request submission and resident request pages */

/* BEGIN OPTIONAL BLOCK: Generic demo form feedback */
// Form feedback: prevents demo forms from submitting and shows their confirmation state.
function initializeFormFeedback() {
  document.addEventListener("submit", (event) => {
    const form = event.target;
    if (
      !(form instanceof HTMLFormElement) ||
      !form.hasAttribute("data-feedback-form")
    )
      return;
    if (
      form.id === "registration-form" ||
      form.id === "login-form" ||
      form.id === "request-form" ||
      form.id === "community-report-form" ||
      form.id === "profile-form" ||
      form.id === "settings-form"
    )
      return;
    event.preventDefault();

    let confirmation = form.querySelector(".form-confirmation");
    if (!confirmation) {
      confirmation = document.createElement("div");
      confirmation.className = "notice notice-info form-confirmation";
      confirmation.setAttribute("role", "status");
      form.prepend(confirmation);
    }
    confirmation.textContent = "Your changes were saved in this browser.";
  });
}
/* END OPTIONAL BLOCK: Generic demo form feedback */

/* BEGIN APPLICATION STARTUP BLOCK: Remove calls only with their matching feature */
initializeMobileNavigation();
populateBarangaySelects();
initializeServiceDirectory();
initializeRequestFilters();
initializeReports();
initializeNewsFilters();
renderDirectory();
initializeAccountAccess();
initializeRequestSubmission();
initializeResidentRequests();
initializeResidentDashboard();
initializeResidentRequestDetails();
initializeFormFeedback();
/* END APPLICATION STARTUP BLOCK */
