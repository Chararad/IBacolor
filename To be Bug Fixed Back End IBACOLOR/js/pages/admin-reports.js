"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const tbody = document.getElementById("admin-reports-list");
  if (!tbody) return;

  const renderFiles = async (report, row) => {
    const detailRow = document.createElement("tr");
    const cell = document.createElement("td");
    cell.colSpan = 6;
    detailRow.append(cell);
    try {
      const { attachments } = await portal.request("admin.report-files", { query: { reference: report.reference } });
      if (!attachments.length) {
        cell.textContent = "No attachments.";
      } else {
        attachments.forEach((file) => {
          const url = `../../includes/api.php?action=download&type=report&id=${encodeURIComponent(file.attachment_id)}&inline=1`;
          if (file.mime_type.startsWith("image/")) {
            const link = document.createElement("a");
            link.href = url;
            link.target = "_blank";
            link.rel = "noopener";
            const image = document.createElement("img");
            image.className = "report-preview";
            image.src = url;
            image.alt = `Evidence for ${report.reference}: ${file.file_name}`;
            link.append(image);
            cell.append(link);
          } else {
            const link = document.createElement("a");
            link.className = "text-link";
            link.href = `../../includes/api.php?action=download&type=report&id=${encodeURIComponent(file.attachment_id)}`;
            link.textContent = file.file_name;
            cell.append(link);
          }
        });
      }
      row.after(detailRow);
    } catch (error) {
      portal.message(row, error.message, true);
    }
  };

  try {
    const { reports } = await portal.request("admin.reports");
    if (!reports.length) {
      tbody.innerHTML = "<tr><td colspan=\"6\">No community reports have been submitted.</td></tr>";
      return;
    }
    tbody.replaceChildren(...reports.map((report) => {
      const row = document.createElement("tr");
      row.innerHTML = `<td><strong>${portal.escape(report.reference)}</strong>
          <details><summary>View report</summary><p>${portal.escape(report.description)}</p>
            <p>Reported by ${portal.escape(report.applicant_name)}</p>
            <p>Observed: ${portal.date(report.observed_at)} · Frequency: ${portal.escape(report.frequency || "Not specified")}</p></details></td>
        <td>${portal.escape(report.category)}</td>
        <td>${portal.escape(report.barangay_name || "")}, ${portal.escape(report.location_details)}</td>
        <td>${portal.date(report.submitted_at)}</td><td>${portal.status(report.status)}</td>
        <td class="table-actions"><details class="report-review">
          <summary class="button button-small button-outline">Update</summary>
          <form>
            <label>Status<select name="status">
              <option>Submitted</option><option>Under Review</option><option>In Progress</option>
              <option>Resolved</option><option>Rejected</option><option>Closed</option>
            </select></label>
            <label>Resident feedback<textarea name="note" rows="3" maxlength="2000"></textarea></label>
            <button class="button button-small" type="submit">Save update</button>
          </form>
        </details><button class="button button-small button-ghost" type="button">View files</button></td>`;
      const form = row.querySelector(".report-review form");
      form.elements.status.value = report.status;
      form.addEventListener("submit", async (event) => {
        event.preventDefault();
        if (form.elements.status.value === "Rejected" && !form.elements.note.value.trim()) {
          portal.message(form, "Enter a reason before rejecting the report.", true);
          form.elements.note.focus();
          return;
        }
        const button = form.querySelector("button");
        button.disabled = true;
        try {
          await portal.request("admin.update-report", {
            method: "POST",
            body: { reference: report.reference, status: form.elements.status.value, note: form.elements.note.value },
          });
          location.reload();
        } catch (error) {
          portal.message(form, error.message, true);
          button.disabled = false;
        }
      });
      row.querySelector(".button-ghost").addEventListener("click", async (event) => {
        const button = event.currentTarget;
        button.disabled = true;
        try {
          await renderFiles(report, row);
        } finally {
          button.disabled = false;
        }
      });
      return row;
    }));
  } catch (error) {
    tbody.innerHTML = "<tr><td colspan=\"6\">Community reports could not be loaded.</td></tr>";
    portal.message(document.querySelector(".card"), error.message, true);
  }
});
