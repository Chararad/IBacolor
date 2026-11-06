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
  category.addEventListener("change", () => {
    const custom = category.value === "Other";
    customLabel.hidden = !custom;
    customInput.disabled = !custom;
    customInput.required = custom;
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
          <span>${portal.escape(report.category)} · ${portal.escape(report.barangay_name || "")} · Submitted ${portal.date(report.submitted_at)}</span>
          <p>${portal.escape(report.description)}</p><span>${portal.escape(report.location_details)}${report.frequency ? ` · ${portal.escape(report.frequency)}` : ""}</span>`;
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
