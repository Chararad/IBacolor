"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const tbody = document.getElementById("admin-reports-list");
  try {
    const { reports } = await portal.request("admin.reports");
    if (!reports.length) {
      tbody.innerHTML = "<tr><td colspan=\"6\">No community reports have been submitted.</td></tr>";
      return;
    }
    tbody.replaceChildren(...reports.map((report) => {
      const row = document.createElement("tr");
      row.innerHTML = `<td><strong>${portal.escape(report.reference)}</strong><br><small>${portal.escape(report.description)}</small></td>
        <td>${portal.escape(report.category)}</td><td>${portal.escape(report.barangay_name || "")}, ${portal.escape(report.location_details)}</td>
        <td>${portal.date(report.submitted_at)}</td><td>${portal.status(report.status)}</td>
        <td class="table-actions"><button class="button button-small button-outline" type="button">Update</button>
        <button class="button button-small button-ghost" type="button">Files</button></td>`;
      const buttons = row.querySelectorAll("button");
      buttons[0].addEventListener("click", async () => {
        const status = window.prompt("Enter report status: Submitted, Under Review, In Progress, Resolved, or Closed", report.status);
        if (!status) return;
        const note = window.prompt("Optional status note", "") || "";
        try {
          await portal.request("admin.update-report", { method: "POST", body: { reference: report.reference, status, note } });
          location.reload();
        } catch (error) {
          portal.message(row, error.message, true);
        }
      });
      buttons[1].addEventListener("click", async () => {
        try {
          const { attachments } = await portal.request("admin.report-files", { query: { reference: report.reference } });
          const details = document.createElement("tr");
          const cell = document.createElement("td");
          cell.colSpan = 6;
          cell.innerHTML = attachments.length
            ? attachments.map((file) => `<a class="text-link" href="../../includes/api.php?action=download&type=report&id=${file.attachment_id}">${portal.escape(file.file_name)}</a>`).join("<br>")
            : "No attachments.";
          details.append(cell);
          row.after(details);
        } catch (error) {
          portal.message(row, error.message, true);
        }
      });
      return row;
    }));
  } catch (error) {
    tbody.innerHTML = "<tr><td colspan=\"6\">Community reports could not be loaded.</td></tr>";
    portal.message(document.querySelector(".card"), error.message, true);
  }
});
