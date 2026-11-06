"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  try {
    const data = await portal.request("admin.dashboard");
    const values = document.querySelectorAll(".stats-grid .stat-value");
    [data.requests.total, data.requests.in_progress, data.reports.open, data.residents].forEach((value, index) => {
      if (values[index]) values[index].textContent = value || 0;
    });
    const tbody = document.querySelector("table tbody");
    tbody.replaceChildren(...data.recent.map((request) => {
      const row = document.createElement("tr");
      row.innerHTML = `<td><strong>${portal.escape(request.reference)}</strong></td>
        <td>${portal.escape(request.applicant_name)}</td><td>${portal.escape(request.service_name || "Service")}</td>
        <td>${portal.status(request.status)}</td><td><a class="text-link" href="request-details.php?reference=${encodeURIComponent(request.reference)}">View</a></td>`;
      return row;
    }));
  } catch (error) {
    portal.message(document.querySelector("main .container"), error.message, true);
  }
});
