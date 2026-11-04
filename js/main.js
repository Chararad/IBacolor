"use strict";

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
    if (event.key !== "Escape" || !document.body.classList.contains("nav-open")) return;
    document.body.classList.remove("nav-open");
    toggle.setAttribute("aria-expanded", "false");
    toggle.focus();
  });
}

function initializeServiceDirectory() {
  const search = document.getElementById("service-search");
  const category = document.getElementById("service-category");
  const list = document.getElementById("service-list");
  const empty = document.getElementById("service-empty");
  const count = document.getElementById("service-count");
  if (!search || !category || !list || !empty || !count) return;

  const cards = [...list.querySelectorAll(".service-card")];
  const filterServices = () => {
    const keyword = search.value.trim().toLowerCase();
    const selectedCategory = category.value;
    let visibleCount = 0;

    cards.forEach((card, index) => {
      const service = MOCK_DATA.services[index];
      const searchText = [
        service.name,
        service.description,
        service.category,
        service.office || "",
        ...service.requirements
      ].join(" ").toLowerCase();
      const matches = (!keyword || searchText.includes(keyword))
        && (selectedCategory === "All categories" || service.category === selectedCategory);

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
    const serviceIndex = MOCK_DATA.services.findIndex((service) => service.id === selectedService);
    if (serviceIndex >= 0) {
      search.value = MOCK_DATA.services[serviceIndex].name;
      cards[serviceIndex]?.querySelector("details")?.setAttribute("open", "");
    }
  }

  filterServices();
}

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
      const matches = row.textContent.toLowerCase().includes(keyword)
        && (status.value === "All statuses" || rowStatus === status.value);

      row.hidden = !matches;
      if (matches) visibleCount += 1;
    });

    count.textContent = `${visibleCount} of ${rows.length} requests shown`;
  };

  search.addEventListener("input", filterRequests);
  status.addEventListener("change", filterRequests);
  filterRequests();
}

function initializeFormFeedback() {
  document.addEventListener("submit", (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement) || !form.hasAttribute("data-feedback-form")) return;
    event.preventDefault();

    let confirmation = form.querySelector(".form-confirmation");
    if (!confirmation) {
      confirmation = document.createElement("div");
      confirmation.className = "notice notice-info form-confirmation";
      confirmation.setAttribute("role", "status");
      form.prepend(confirmation);
    }
    confirmation.textContent = "Thank you. Your form has been completed.";
  });
}

initializeMobileNavigation();
initializeServiceDirectory();
initializeRequestFilters();
initializeFormFeedback();
