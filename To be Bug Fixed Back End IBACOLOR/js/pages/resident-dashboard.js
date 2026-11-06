"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  try {
    const data = await portal.request("resident.dashboard");
    const greeting = document.querySelector("[data-resident-greeting]");
    const name = document.querySelector("[data-resident-name]");
    const barangay = document.querySelector("[data-resident-barangay]");
    const fullName = [data.profile?.first_name, data.profile?.last_name].filter(Boolean).join(" ");
    if (greeting && fullName) greeting.textContent = `Welcome back, ${fullName}`;
    if (name) name.textContent = fullName || "Resident account";
    if (barangay) barangay.textContent = data.profile?.barangay_name || "Bacolor";
    document.querySelector('[data-request-stat="awaiting"]').textContent = data.stats.awaiting || 0;
    document.querySelector('[data-request-stat="in-progress"]').textContent = data.stats.in_progress || 0;
    document.querySelector('[data-request-stat="completed"]').textContent = data.stats.completed || 0;
    const tbody = document.getElementById("dashboard-recent-requests");
    tbody.replaceChildren();
    data.requests.forEach((request) => {
      const row = document.createElement("tr");
      row.innerHTML = `<td><strong>${portal.escape(request.reference)}</strong></td>
        <td>${portal.escape(request.service_name || "Service")}</td>
        <td>${portal.date(request.updated_at)}</td><td>${portal.status(request.status)}</td>
        <td><a class="text-link" href="request-details.php?reference=${encodeURIComponent(request.reference)}">View</a></td>`;
      tbody.append(row);
    });
    if (data.requests[0]) {
      document.getElementById("dashboard-latest-service").textContent = data.requests[0].service_name || "Service request";
      document.getElementById("dashboard-latest-note").textContent = `Updated ${portal.date(data.requests[0].updated_at)} · ${data.requests[0].status}`;
      document.getElementById("dashboard-latest-link").href = `request-details.php?reference=${encodeURIComponent(data.requests[0].reference)}`;
      document.getElementById("dashboard-latest-link").textContent = "View request →";
    }
  } catch (error) {
    portal.message(document.querySelector(".resident-dashboard .container"), error.message, true);
  }
});
