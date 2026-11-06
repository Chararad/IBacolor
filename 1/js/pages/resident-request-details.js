"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const reference = new URLSearchParams(location.search).get("reference");
  const container = document.getElementById("resident-request-detail");
  if (!reference || !container) {
    portal.message(container, "Request reference was not provided.", true);
    return;
  }
  try {
    const data = await portal.request("resident.request", { query: { reference } });
    const item = data.request;
    document.querySelector("main h1").textContent = item.service_name || "Service request";
    const heading = container.querySelector(".detail-heading");
    heading.innerHTML = `<div><p class="eyebrow">Reference number</p><h2>${portal.escape(item.reference)}</h2></div>${portal.status(item.status)}`;
    const details = container.querySelector(".detail-list");
    details.innerHTML = `<div><dt>Service</dt><dd>${portal.escape(item.service_name)}</dd></div>
      <div><dt>Date submitted</dt><dd>${portal.date(item.submitted_at)}</dd></div>
      <div><dt>Request details</dt><dd>${portal.escape(item.details)}</dd></div>`;
    const timeline = container.querySelector(".timeline");
    timeline.replaceChildren(...data.history.map((entry) => {
      const li = document.createElement("li");
      li.innerHTML = `<strong>${portal.escape(entry.status)}</strong><span>${portal.date(entry.changed_at)}${entry.note ? ` · ${portal.escape(entry.note)}` : ""}</span>`;
      return li;
    }));
    const card = Array.from(container.querySelectorAll(".card")).find((element) => /uploaded documents/i.test(element.textContent));
    const files = document.createElement("div");
    files.innerHTML = data.documents.map((doc) => `<div class="file-row"><a href="../../includes/api.php?action=download&type=request&id=${doc.document_id}">${portal.escape(doc.document_name)}</a>${portal.status(doc.review_status)}</div>`).join("") || "<p>No documents uploaded.</p>";
    const form = document.createElement("form");
    form.className = "form-card";
    form.innerHTML = `<input type="hidden" name="reference" value="${portal.escape(reference)}">
      <label>Upload another document<input type="file" name="document" accept=".pdf,image/jpeg,image/png" required></label>
      <button class="button button-outline button-block" type="submit">Upload document</button>
      <p class="form-help" role="status" hidden></p>`;
    card.replaceChildren(card.querySelector("h3"), files, form);
    form.addEventListener("submit", async (event) => {
      event.preventDefault();
      const notice = form.querySelector(".form-help");
      try {
        await portal.request("resident.upload-document", { method: "POST", body: new FormData(form) });
        portal.message(notice, "Document uploaded for review.");
        window.setTimeout(() => location.reload(), 500);
      } catch (error) {
        portal.message(notice, error.message, true);
      }
    });
  } catch (error) {
    portal.message(container, error.message, true);
  }
});
