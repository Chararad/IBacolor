"use strict";

window.IBacolorModules = window.IBacolorModules || {};
(function () {
const { SESSION_KEY, formatDateTime } = window.IBacolorModules.shared;

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

window.IBacolorModules.resident = {
  initializeRequestSubmission,
  initializeResidentRequests,
  initializeResidentDashboard,
  initializeResidentRequestDetails,
};
})();
