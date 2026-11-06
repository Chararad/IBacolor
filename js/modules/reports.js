"use strict";

window.IBacolorModules = window.IBacolorModules || {};
(function () {
const { SESSION_KEY, formatDateTime } = window.IBacolorModules.shared;

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

window.IBacolorModules.reports = { initializeReports };
})();
