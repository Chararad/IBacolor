"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const form = document.getElementById("community-report-form");
  const category = form?.elements.category;
  const filter = document.getElementById("report-status-filter");
  const list = document.getElementById("my-report-list");
  const count = document.getElementById("report-count");
  const notice = document.getElementById("report-confirmation");
  if (!form || !category || !filter || !list || !count) return;

  category.replaceChildren(new Option("Select a category", ""));
  ["Roads and transportation", "Garbage disposal", "Drainage and flooding", "Street lighting", "Public safety", "Other"].forEach((value) => category.add(new Option(value, value)));
  const customLabel = document.getElementById("custom-report-category-label");
  const customInput = document.getElementById("custom-report-category");
  const categoryIcon = document.getElementById("report-category-icon");
  const icons = window.IBacolorReportIcons;
  // Show the icon that matches the chosen category (empty until one is chosen).
  const updateCategoryIcon = () => {
    if (!categoryIcon || !icons) return;
    categoryIcon.innerHTML = category.value ? icons.svg(category.value) : "";
  };
  category.addEventListener("change", () => {
    const custom = category.value === "Other";
    customLabel.hidden = !custom;
    customInput.disabled = !custom;
    customInput.required = custom;
    updateCategoryIcon();
  });
  try {
    const { barangays } = await portal.request("resident.barangays");
    const select = form.elements.barangay;
    select.replaceChildren(new Option("Select barangay", ""));
    barangays.forEach((item) => select.add(new Option(item.name, item.barangay_id)));
  } catch (error) {
    portal.message(notice, error.message, true);
    return;
  }
  const render = async () => {
    try {
      const { reports } = await portal.request("resident.reports");
      const visible = reports.filter((report) => filter.value === "All statuses" || report.status === filter.value);
      count.textContent = `${visible.length} report${visible.length === 1 ? "" : "s"}`;
      list.replaceChildren(...visible.map((report) => {
        const article = document.createElement("article");
        article.className = "report-item";
        article.innerHTML = `<div class="report-heading"><strong>${portal.escape(report.reference)}</strong>${portal.status(report.status)}</div>
          <span class="report-category">${icons ? icons.badge(report.category, "category-icon-sm") : ""}<span>${portal.escape(report.category)} · ${portal.escape(report.barangay_name || "")} · Submitted ${portal.date(report.submitted_at)}</span></span>
          <p>${portal.escape(report.description)}</p><span>${portal.escape(report.location_details)}${report.frequency ? ` · ${portal.escape(report.frequency)}` : ""}</span>`;
        const details = document.createElement("details");
        details.className = "report-updates";
        const summary = document.createElement("summary");
        summary.textContent = "View photos and status updates";
        details.append(summary);
        const history = document.createElement("div");
        history.className = "report-history";
        if (report.history?.length) {
          report.history.forEach((entry) => {
            const update = document.createElement("p");
            update.innerHTML = `${portal.status(entry.status)} <span>${portal.date(entry.changed_at)}</span>`;
            if (entry.note) {
              const note = document.createElement("span");
              note.textContent = ` — ${entry.note}`;
              update.append(note);
            }
            history.append(update);
          });
        } else {
          history.textContent = "No updates have been posted.";
        }
        details.append(history);
        (report.attachments || []).forEach((file) => {
          const url = `../../includes/api.php?action=download&type=report&id=${encodeURIComponent(file.attachment_id)}&inline=1`;
          if (file.mime_type.startsWith("image/")) {
            const link = document.createElement("a");
            link.href = url;
            link.target = "_blank";
            link.rel = "noopener";
            const image = document.createElement("img");
            image.className = "report-preview";
            image.src = url;
            image.alt = `Uploaded evidence: ${file.file_name}`;
            link.append(image);
            details.append(link);
          } else {
            const link = document.createElement("a");
            link.href = url;
            link.textContent = file.file_name;
            details.append(link);
          }
        });
        article.append(details);
        return article;
      }));
    } catch (error) {
      portal.message(notice, error.message, true);
    }
  };
  filter.addEventListener("change", render);
  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    if (!form.reportValidity()) return;
    const button = form.querySelector('[type="submit"]');
    button.disabled = true;
    try {
      const data = new FormData(form);
      if (category.value === "Other") data.set("category", customInput.value.trim());
      const result = await portal.request("resident.submit-report", { method: "POST", body: data });
      portal.message(notice, `Report ${result.reference} submitted successfully.`);
      form.reset();
      updateCategoryIcon();
      customLabel.hidden = true;
      customInput.disabled = true;
      await render();
    } catch (error) {
      portal.message(notice, error.message, true);
    } finally {
      button.disabled = false;
    }
  });
  await render();
});
