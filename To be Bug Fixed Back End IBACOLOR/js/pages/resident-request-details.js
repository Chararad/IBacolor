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
    const documentsHeading = card.querySelector("h3");
    card.replaceChildren(documentsHeading);
    if (!data.documents.length) {
      card.insertAdjacentHTML("beforeend", "<p>No documents uploaded.</p>");
    }
    data.documents.forEach((doc) => {
      const wrapper = document.createElement("div");
      wrapper.className = "request-document";
      wrapper.innerHTML = `<div class="file-row"><a href="../../includes/api.php?action=download&type=request&id=${encodeURIComponent(doc.document_id)}">${portal.escape(doc.document_name)}</a>${portal.status(doc.review_status)}</div>`;
      if (doc.review_note) {
        const note = document.createElement("p");
        note.className = "notice notice-warning";
        note.textContent = doc.review_note;
        wrapper.append(note);
      }
      if (doc.review_status === "needs correction" && Number(doc.is_latest) === 1) {
        const replaceForm = document.createElement("form");
        replaceForm.className = "document-replacement-form";
        replaceForm.innerHTML = `<input type="hidden" name="documentId" value="${doc.document_id}">
          <label>Replace ${portal.escape(doc.document_name)}<input name="document" type="file" accept=".pdf,image/jpeg,image/png" required></label>
          <button class="button button-small button-outline" type="submit">Upload replacement</button>
          <p class="form-help" role="status" hidden></p>`;
        replaceForm.addEventListener("submit", async (event) => {
          event.preventDefault();
          if (!replaceForm.reportValidity()) return;
          const button = replaceForm.querySelector("button");
          button.disabled = true;
          try {
            await portal.request("resident.replace-document", { method: "POST", body: new FormData(replaceForm) });
            portal.message(replaceForm.querySelector(".form-help"), "Replacement submitted for review.");
            window.setTimeout(() => location.reload(), 500);
          } catch (error) {
            portal.message(replaceForm.querySelector(".form-help"), error.message, true);
            button.disabled = false;
          }
        });
        wrapper.append(replaceForm);
      }
      card.append(wrapper);
    });

    const extraUpload = document.createElement("form");
    extraUpload.className = "document-replacement-form";
    extraUpload.innerHTML = `<input type="hidden" name="reference" value="${portal.escape(reference)}">
      <label>Upload an additional supporting document<input type="file" name="document" accept=".pdf,image/jpeg,image/png" required></label>
      <button class="button button-outline button-block" type="submit">Upload document</button>
      <p class="form-help" role="status" hidden></p>`;
    extraUpload.addEventListener("submit", async (event) => {
      event.preventDefault();
      if (!extraUpload.reportValidity()) return;
      const button = extraUpload.querySelector("button");
      button.disabled = true;
      try {
        await portal.request("resident.upload-document", { method: "POST", body: new FormData(extraUpload) });
        portal.message(extraUpload.querySelector(".form-help"), "Document uploaded for review.");
        window.setTimeout(() => location.reload(), 500);
      } catch (error) {
        portal.message(extraUpload.querySelector(".form-help"), error.message, true);
        button.disabled = false;
      }
    });
    card.append(extraUpload);

    const appointments = document.createElement("article");
    appointments.className = "card";
    appointments.innerHTML = "<h3>Physical document appointments</h3>";
    if (!data.appointments.length) {
      appointments.insertAdjacentHTML("beforeend", "<p>No physical document appointment has been scheduled.</p>");
    }
    data.appointments.forEach((appointment) => {
      const entry = document.createElement("div");
      entry.className = "appointment-entry";
      entry.innerHTML = `<p>${portal.status(appointment.status)} <strong>${portal.escape(appointment.appointment_date)} ${portal.escape(appointment.appointment_time.slice(0, 5))}</strong></p>
        <p>${portal.escape(appointment.office)} · ${portal.escape(appointment.location)}</p>
        <p>${portal.escape(appointment.instructions || "")}</p>`;
      appointments.append(entry);
    });
    container.querySelector("aside").append(appointments);
  } catch (error) {
    portal.message(container, error.message, true);
  }
});
