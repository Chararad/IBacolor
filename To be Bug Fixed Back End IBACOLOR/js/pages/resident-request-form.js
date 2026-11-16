"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const form = document.getElementById("request-form");
  if (!form) return;
  const confirmation = document.getElementById("request-confirmation");
  const serviceSelect = form.elements.service;
  const requirementsContainer = document.getElementById("document-requirements");
  const showSuccessDialog = (reference) => {
    const detailsUrl = `request-details.php?reference=${encodeURIComponent(reference)}`;
    const dialog = document.createElement("dialog");
    dialog.className = "success-dialog";
    dialog.setAttribute("aria-labelledby", "success-dialog-title");
    const icon = document.createElement("div");
    icon.className = "success-dialog-icon";
    icon.setAttribute("aria-hidden", "true");
    icon.textContent = "✓";
    const title = document.createElement("h2");
    title.id = "success-dialog-title";
    title.textContent = "Request submitted";
    const text = document.createElement("p");
    text.textContent = "Your request was received. Keep your reference number to track its status.";
    const ref = document.createElement("p");
    ref.className = "success-dialog-reference";
    ref.textContent = reference;
    const action = document.createElement("a");
    action.className = "button";
    action.href = detailsUrl;
    action.textContent = "View request details";
    dialog.append(icon, title, text, ref, action);
    dialog.addEventListener("cancel", () => location.assign(detailsUrl));
    document.body.append(dialog);
    if (typeof dialog.showModal === "function") dialog.showModal();
    else dialog.setAttribute("open", "");
    action.focus();
  };
  try {
    const [{ services }, { profile }] = await Promise.all([
      portal.request("resident.services"),
      portal.request("resident.profile"),
    ]);
    serviceSelect.replaceChildren(new Option("Select a service", ""));
    services.forEach((service) => serviceSelect.add(new Option(service.name, service.service_id)));
    const renderRequirements = () => {
      requirementsContainer.replaceChildren();
      const selectedService = services.find((service) => Number(service.service_id) === Number(serviceSelect.value));
      if (!selectedService) {
        const prompt = document.createElement("p");
        prompt.className = "form-help";
        prompt.textContent = "Select a service to see its required documents.";
        requirementsContainer.append(prompt);
        return;
      }
      if (!selectedService.requirements.length) {
        const message = document.createElement("p");
        message.className = "form-help";
        message.textContent = "No supporting documents are required for this service.";
        requirementsContainer.append(message);
        return;
      }
      selectedService.requirements.forEach((requirement) => {
        const label = document.createElement("label");
        label.append(document.createTextNode(requirement.requirement));
        const help = document.createElement("small");
        help.className = "form-help";
        help.textContent = "Required. PDF, JPG, or PNG, up to 10 MB.";
        const input = document.createElement("input");
        input.type = "file";
        input.name = `documents[${requirement.requirement_id}]`;
        input.accept = ".pdf,image/jpeg,image/png,application/pdf";
        input.required = true;
        input.addEventListener("change", () => {
          const file = input.files[0];
          input.setCustomValidity(file && file.size > 10 * 1024 * 1024 ? "Choose a file that is 10 MB or smaller." : "");
        });
        label.append(help, input);
        requirementsContainer.append(label);
      });
    };
    serviceSelect.addEventListener("change", renderRequirements);
    const requestedCode = new URLSearchParams(window.location.search).get("service");
    const preselected = requestedCode ? services.find((service) => service.code === requestedCode) : null;
    if (requestedCode && !preselected) {
      portal.message(confirmation, "That service is currently unavailable or could not be found. Please choose another service.", true);
    }
    if (preselected) {
      serviceSelect.value = preselected.service_id;
      serviceSelect.closest("label").hidden = true;
      const fieldset = serviceSelect.closest("fieldset");
      const legend = fieldset.querySelector("legend");
      if (legend) legend.textContent = "Step 1: Selected service";
      const summary = document.createElement("div");
      summary.className = "selected-service";
      const name = document.createElement("strong");
      name.textContent = preselected.name;
      const meta = document.createElement("small");
      meta.textContent = [preselected.category, preselected.office].filter(Boolean).join(" · ");
      const change = document.createElement("a");
      change.className = "text-link";
      change.href = "../services.php";
      change.textContent = "Choose a different service";
      summary.append(name, meta, change);
      fieldset.append(summary);
    }
    renderRequirements();
    if (profile) {
      form.elements.firstName.value = profile.first_name || "";
      form.elements.lastName.value = profile.last_name || "";
      form.elements.mobile.value = profile.mobile_number || "";
    }
    const { barangays } = await portal.request("resident.barangays");
    const barangaySelect = form.elements.barangay;
    barangaySelect.replaceChildren(new Option("Select barangay", ""));
    barangays.forEach((item) => barangaySelect.add(new Option(item.name, item.barangay_id)));
    if (profile) barangaySelect.value = profile.barangay_id;
  } catch (error) {
    portal.message(confirmation, error.message, true);
  }
  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    if (!form.reportValidity()) return;
    const submit = form.querySelector('[type="submit"]');
    submit.disabled = true;
    let submitted = false;
    try {
      const result = await portal.request("resident.submit-request", { method: "POST", body: new FormData(form) });
      submitted = true;
      showSuccessDialog(result.reference);
    } catch (error) {
      portal.message(confirmation, error.message, true);
    } finally {
      if (!submitted) submit.disabled = false;
    }
  });
});
