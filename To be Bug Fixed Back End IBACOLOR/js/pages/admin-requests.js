"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const search = document.getElementById("admin-request-search");
  const count = document.getElementById("admin-request-count");
  const feedback = document.getElementById("admin-request-feedback");
  const tbody = document.getElementById("admin-request-list");
  const tabs = Array.from(document.querySelectorAll("[data-queue]"));
  if (!search || !count || !tbody) return;

  // Which statuses belong to which queue ("all" has no filter).
  const QUEUES = {
    "needs-action": ["submitted", "under review", "for revision", "pending"],
    "in-progress": ["in progress", "on process", "reviewing"],
    completed: ["completed", "resolved", "closed", "done"],
    rejected: ["rejected"],
    all: null,
  };
  // Statuses the API accepts (see admin.update-request).
  const STATUS_OPTIONS = ["Submitted", "Under Review", "In Progress", "For Revision", "Completed", "Rejected"];

  const normalize = (value) => String(value || "").trim().toLowerCase();
  const inQueue = (request, queue) => !QUEUES[queue] || QUEUES[queue].includes(normalize(request.status));

  const requestedView = new URLSearchParams(window.location.search).get("view");
  const requestedStatus = normalize(new URLSearchParams(window.location.search).get("status"));
  const queueFromStatus = Object.keys(QUEUES).find((queue) =>
    Array.isArray(QUEUES[queue]) && QUEUES[queue].includes(requestedStatus));
  let activeQueue = requestedView && Object.hasOwn(QUEUES, requestedView)
    ? requestedView
    : queueFromStatus || "needs-action";
  let requests = [];

  const statusSelect = (request) => {
    // Rejection needs a written reason, so it is only offered on the details page.
    const options = STATUS_OPTIONS.filter((status) => status !== "Rejected" || request.status === "Rejected");
    if (!options.some((status) => normalize(status) === normalize(request.status))) options.unshift(request.status);
    return `<select aria-label="New status for ${portal.escape(request.reference)}">${options
      .map((status) => `<option value="${portal.escape(status)}"${normalize(status) === normalize(request.status) ? " selected" : ""}>${portal.escape(status)}</option>`)
      .join("")}</select>`;
  };

  const render = () => {
    tabs.forEach((tab) => {
      const queue = tab.dataset.queue;
      tab.setAttribute("aria-pressed", String(queue === activeQueue));
      const badge = tab.querySelector("[data-queue-count]");
      if (badge) badge.textContent = requests.filter((request) => inQueue(request, queue)).length;
    });

    const query = normalize(search.value);
    const rows = requests.filter((request) =>
      inQueue(request, activeQueue) &&
      [request.reference, request.applicant_name, request.service_name].some((value) => normalize(value).includes(query)));
    count.textContent = `${rows.length} request${rows.length === 1 ? "" : "s"} in this view`;

    if (!rows.length) {
      tbody.innerHTML = '<tr><td colspan="7">No requests in this view.</td></tr>';
      return;
    }
    tbody.replaceChildren(...rows.map((request) => {
      const row = document.createElement("tr");
      row.innerHTML = `<td><strong>${portal.escape(request.reference)}</strong></td>
        <td>${portal.escape(request.applicant_name)}</td><td>${portal.escape(request.service_name || "Service")}</td>
        <td>${portal.date(request.submitted_at)}</td><td>${portal.status(request.status)}</td>
        <td class="quick-update">${statusSelect(request)}<button class="button button-small" type="button" data-update="${portal.escape(request.reference)}">Update</button></td>
        <td class="table-actions"><a class="button button-small button-outline" href="request-details.php?reference=${encodeURIComponent(request.reference)}">Manage</a></td>`;
      return row;
    }));
  };

  tabs.forEach((tab) => tab.addEventListener("click", () => {
    activeQueue = tab.dataset.queue;
    render();
  }));
  search.addEventListener("input", render);

  tbody.addEventListener("click", async (event) => {
    const button = event.target.closest("[data-update]");
    if (!button) return;
    const request = requests.find((item) => item.reference === button.dataset.update);
    const select = button.closest("tr")?.querySelector("select");
    if (!request || !select) return;
    if (normalize(select.value) === normalize(request.status)) {
      portal.message(feedback, `${request.reference} is already "${request.status}". Choose a different status first.`, true);
      return;
    }
    button.disabled = true;
    try {
      await portal.request("admin.update-request", {
        method: "POST",
        body: { reference: request.reference, status: select.value, note: "" },
      });
      request.status = select.value;
      portal.message(feedback, `${request.reference} updated to "${request.status}".`);
      render();
    } catch (error) {
      portal.message(feedback, error.message, true);
      button.disabled = false;
    }
  });

  try {
    ({ requests } = await portal.request("admin.requests"));
    render();
  } catch (error) {
    tbody.innerHTML = '<tr><td colspan="7">Requests could not be loaded.</td></tr>';
    portal.message(feedback, error.message, true);
  }
});
