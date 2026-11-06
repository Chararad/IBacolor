"use strict";

window.IBacolorModules = window.IBacolorModules || {};
(function () {
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

window.IBacolorModules.filters = { initializeRequestFilters, initializeNewsFilters };
})();
