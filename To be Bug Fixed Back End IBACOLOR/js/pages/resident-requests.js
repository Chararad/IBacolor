"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  try {
    const { requests } = await portal.request("resident.requests");
    const tbody = document.querySelector("table tbody");
    tbody.replaceChildren();
    requests.forEach((item) => {
      const row = document.createElement("tr");
      row.innerHTML = `<td><strong>${portal.escape(item.reference)}</strong></td>
        <td>${portal.escape(item.service_name || "Service")}</td><td>${portal.date(item.submitted_at)}</td>
        <td>${portal.date(item.updated_at)}</td><td>${portal.status(item.status)}</td>
        <td><a class="text-link" href="request-details.php?reference=${encodeURIComponent(item.reference)}">View</a></td>`;
      tbody.append(row);
    });
    const empty = document.getElementById("resident-request-empty");
    if (empty) empty.hidden = requests.length > 0;
  } catch (error) {
    portal.message(document.querySelector(".card"), error.message, true);
  }
});
