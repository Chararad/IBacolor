"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const search = document.getElementById("admin-request-search");
  const statusFilter = document.getElementById("admin-request-status");
  const count = document.getElementById("admin-request-count");
  const tbody = document.querySelector("table tbody");
  let requests = [];
  const render = () => {
    const query = search.value.trim().toLowerCase();
    const selected = statusFilter.value;
    const rows = requests.filter((request) =>
      (selected === "All statuses" || request.status.toLowerCase() === selected.toLowerCase()) &&
      [request.reference, request.applicant_name, request.service_name].some((value) => String(value || "").toLowerCase().includes(query)));
    count.textContent = `${rows.length} of ${requests.length} requests shown`;
    tbody.replaceChildren(...rows.map((request) => {
      const row = document.createElement("tr");
      row.innerHTML = `<td><strong>${portal.escape(request.reference)}</strong></td>
        <td>${portal.escape(request.applicant_name)}</td><td>${portal.escape(request.service_name || "Service")}</td>
        <td>${portal.date(request.submitted_at)}</td><td>${portal.status(request.status)}</td>
        <td class="table-actions"><a class="button button-small button-outline" href="request-details.php?reference=${encodeURIComponent(request.reference)}">View</a></td>`;
      return row;
    }));
  };
  try {
    ({ requests } = await portal.request("admin.requests"));
    render();
    search.addEventListener("input", render);
    statusFilter.addEventListener("change", render);
  } catch (error) {
    portal.message(document.querySelector(".card"), error.message, true);
  }
});
