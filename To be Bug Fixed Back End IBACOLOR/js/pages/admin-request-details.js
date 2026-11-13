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
    const requestCard = cards[0];
    const notesCard = cards[1];
    const updateCard = cards[2];
    const documentsCard = cards[3];
    requestCard.querySelector(".detail-heading").innerHTML =
      `<div><p class="eyebrow">Reference number</p><h2>${portal.escape(request.reference)}</h2></div>${portal.status(request.status)}`;
    requestCard.querySelector(".detail-list").innerHTML =
      `<div><dt>Applicant</dt><dd>${portal.escape(request.applicant_name)} · ${portal.escape(request.barangay_name || "")}</dd></div>
       <div><dt>Service</dt><dd>${portal.escape(request.service_name || "Service")}</dd></div>
       <div><dt>Submitted</dt><dd>${portal.date(request.submitted_at)}</dd></div>
       <div><dt>Contact</dt><dd>${portal.escape(request.mobile_number || "—")}</dd></div>
       <div><dt>Details</dt><dd>${portal.escape(request.details)}</dd></div>`;

    const notes = notesCard.querySelector("textarea");
    const statusSelect = updateCard.querySelector("select");
    const updateButton = updateCard.querySelector("button");
    statusSelect.replaceChildren(...["Submitted", "Under Review", "In Progress", "For Revision", "Completed", "Rejected"]
      .map((item) => new Option(item, item)));
    statusSelect.value = request.status;
    const saveStatus = async () => {
      if (statusSelect.value === "Rejected" && !notes.value.trim()) {
        portal.message(notesCard, "Enter a rejection reason before rejecting the request.", true);
        notes.focus();
        return;
      }
      updateButton.disabled = true;
      try {
        await portal.request("admin.update-request", {
          method: "POST",
          body: { reference, status: statusSelect.value, note: notes.value },
        });
        window.location.assign("requests.php");
      } catch (error) {
        portal.message(notesCard, error.message, true);
        updateButton.disabled = false;
      }
    };
    updateButton.addEventListener("click", saveStatus);
    notesCard.querySelector("button").addEventListener("click", saveStatus);
    const revisionButton = notesCard.querySelectorAll("button")[1];
    if (revisionButton) revisionButton.addEventListener("click", () => {
      statusSelect.value = "For Revision";
      saveStatus();
    });

    documentsCard.replaceChildren(documentsCard.querySelector("h3"));
    if (!data.documents.length) {
      documentsCard.insertAdjacentHTML("beforeend", "<p>No documents have been uploaded.</p>");
    }
    data.documents.forEach((doc) => {
      const wrapper = document.createElement("section");
      wrapper.className = "document-review";
      wrapper.innerHTML = `<p class="file-row"><a href="../../includes/api.php?action=download&type=request&id=${encodeURIComponent(doc.document_id)}">${portal.escape(doc.document_name)}</a>${portal.status(doc.review_status)}</p>
        <form class="document-review-form">
          <label>Review status<select name="status">
            <option value="submitted">Submitted</option>
            <option value="accepted">Accepted</option>
            <option value="needs correction">Needs correction</option>
          </select></label>
          <label>Feedback<textarea name="note" rows="2" maxlength="2000">${portal.escape(doc.review_note || "")}</textarea></label>
          <button class="button button-small button-outline" type="submit">Save document review</button>
        </form>`;
      const form = wrapper.querySelector("form");
      form.elements.status.value = doc.review_status;
      form.addEventListener("submit", async (event) => {
        event.preventDefault();
        const button = form.querySelector("button");
        button.disabled = true;
        try {
          await portal.request("admin.review-document", {
            method: "POST",
            body: { documentId: doc.document_id, status: form.elements.status.value, note: form.elements.note.value },
          });
          portal.message(wrapper, "Document review saved.");
        } catch (error) {
          portal.message(wrapper, error.message, true);
        } finally {
          button.disabled = false;
        }
      });
      documentsCard.append(wrapper);
    });

    const appointmentCard = document.createElement("article");
    appointmentCard.className = "card";
    appointmentCard.innerHTML = `<h3>Physical document appointment</h3>
      <div class="appointment-history"></div>
      <form class="appointment-form">
        <label>Date<input name="date" type="date" required></label>
        <label>Time<input name="time" type="time" required></label>
        <label>Office or department<input name="office" maxlength="255" required></label>
        <label>Office address or location<input name="location" maxlength="255" required></label>
        <label>Instructions<textarea name="instructions" rows="3" maxlength="2000"></textarea></label>
        <button class="button button-small" type="submit">Schedule / reschedule</button>
      </form>`;
    const appointmentHistory = appointmentCard.querySelector(".appointment-history");
    const activeAppointment = data.appointments.find((appointment) =>
      ["scheduled", "rescheduled"].includes(appointment.status));
    data.appointments.forEach((appointment) => {
      const row = document.createElement("div");
      row.className = "appointment-entry";
      row.innerHTML = `<p>${portal.status(appointment.status)} <strong>${portal.escape(appointment.appointment_date)} ${portal.escape(appointment.appointment_time.slice(0, 5))}</strong></p>
        <p>${portal.escape(appointment.office)} · ${portal.escape(appointment.location)}</p>
        <p>${portal.escape(appointment.instructions || "")}</p>`;
      if (activeAppointment && appointment.appointment_id === activeAppointment.appointment_id) {
        const cancel = document.createElement("button");
        cancel.type = "button";
        cancel.className = "button button-small button-outline";
        cancel.textContent = "Cancel appointment";
        const complete = document.createElement("button");
        complete.type = "button";
        complete.className = "button button-small button-ghost";
        complete.textContent = "Mark completed";
        const updateAppointment = async (status, button) => {
          button.disabled = true;
          try {
            await portal.request("admin.update-appointment", {
              method: "POST",
              body: { appointmentId: appointment.appointment_id, status },
            });
            window.location.reload();
          } catch (error) {
            portal.message(row, error.message, true);
            button.disabled = false;
          }
        };
        cancel.addEventListener("click", () => updateAppointment("cancelled", cancel));
        complete.addEventListener("click", () => updateAppointment("completed", complete));
        row.append(cancel, complete);
      }
      appointmentHistory.append(row);
    });
    if (!data.appointments.length) appointmentHistory.innerHTML = "<p>No appointment is scheduled.</p>";
    const appointmentForm = appointmentCard.querySelector("form");
    appointmentForm.addEventListener("submit", async (event) => {
      event.preventDefault();
      if (!appointmentForm.reportValidity()) return;
      const button = appointmentForm.querySelector("button");
      button.disabled = true;
      try {
        await portal.request("admin.schedule-appointment", {
          method: "POST",
          body: { ...portal.formData(appointmentForm), reference },
        });
        window.location.reload();
      } catch (error) {
        portal.message(appointmentForm, error.message, true);
        button.disabled = false;
      }
    });
    container.querySelector("aside").append(appointmentCard);
  } catch (error) {
    portal.message(container, error.message, true);
  }
});
