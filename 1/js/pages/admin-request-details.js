"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const reference = new URLSearchParams(location.search).get("reference");
  const container = document.querySelector("main .content-grid");
  if (!reference || !container) {
    portal.message(container, "Request reference was not provided.", true);
    return;
  }
  try {
    const data = await portal.request("admin.request", { query: { reference } });
    const request = data.request;
    const cards = container.querySelectorAll(".card");
    cards[0].querySelector(".detail-heading").innerHTML =
      `<div><p class="eyebrow">Reference number</p><h2>${portal.escape(request.reference)}</h2></div>${portal.status(request.status)}`;
    cards[0].querySelector(".detail-list").innerHTML =
      `<div><dt>Applicant</dt><dd>${portal.escape(request.applicant_name)} · ${portal.escape(request.barangay_name || "")}</dd></div>
       <div><dt>Service</dt><dd>${portal.escape(request.service_name || "Service")}</dd></div>
       <div><dt>Submitted</dt><dd>${portal.date(request.submitted_at)}</dd></div>
       <div><dt>Details</dt><dd>${portal.escape(request.details)}</dd></div>`;
    const notes = cards[1].querySelector("textarea");
    const statusSelect = container.querySelector("aside select");
    statusSelect.replaceChildren(...["Submitted", "Under Review", "In Progress", "For Revision", "Completed", "Rejected"].map((item) => new Option(item, item)));
    statusSelect.value = request.status;
    const updateButton = container.querySelector("aside button");
    const saveStatus = async () => {
      updateButton.disabled = true;
      try {
        await portal.request("admin.update-request", {
          method: "POST",
          body: { reference, status: statusSelect.value, note: notes.value },
        });
        portal.message(cards[1], "Request status and history updated.");
        window.setTimeout(() => location.reload(), 500);
      } catch (error) {
        portal.message(cards[1], error.message, true);
      } finally {
        updateButton.disabled = false;
      }
    };
    updateButton.addEventListener("click", saveStatus);
    cards[1].querySelector("button").addEventListener("click", saveStatus);
    const revisionButton = cards[1].querySelectorAll("button")[1];
    if (revisionButton) revisionButton.addEventListener("click", () => {
      statusSelect.value = "For Revision";
      updateButton.click();
    });
    const documentCard = Array.from(container.querySelectorAll(".card")).find((card) => /documents/i.test(card.textContent));
    documentCard.replaceChildren(documentCard.querySelector("h3"), ...data.documents.map((doc) => {
      const row = document.createElement("div");
      row.className = "file-row";
      row.innerHTML = `<a href="../../includes/api.php?action=download&type=request&id=${doc.document_id}">${portal.escape(doc.document_name)}</a>${portal.status(doc.review_status)}`;
      return row;
    }));
    if (!data.documents.length) documentCard.insertAdjacentHTML("beforeend", "<p>No documents have been uploaded.</p>");
  } catch (error) {
    portal.message(container, error.message, true);
  }
});
