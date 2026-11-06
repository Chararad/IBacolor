"use strict";

window.IBacolorModules = window.IBacolorModules || {};
(function () {
const SESSION_KEY = "ibacolor.mock-session.v1";
const ACCOUNTS_KEY = "ibacolor.mock-accounts.v1";

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

function formatDateTime(value) {
  const date = new Date(value);
  return Number.isNaN(date.getTime())
    ? value
    : date.toLocaleString("en-PH", {
        dateStyle: "medium",
        timeStyle: "short",
      });
}

window.IBacolorModules.shared = {
  SESSION_KEY,
  ACCOUNTS_KEY,
  initializeMobileNavigation,
  populateBarangaySelects,
  formatDateTime,
};
})();
