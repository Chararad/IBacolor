"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const form = document.getElementById("request-form");
  if (!form) return;
  const confirmation = document.getElementById("request-confirmation");
  const serviceSelect = form.elements.service;
  const requirementsContainer = document.getElementById("document-requirements");
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
    try {
      const result = await portal.request("resident.submit-request", { method: "POST", body: new FormData(form) });
      portal.message(confirmation, `Request ${result.reference} submitted successfully.`);
      window.setTimeout(() => location.assign(`request-details.php?reference=${encodeURIComponent(result.reference)}`), 700);
    } catch (error) {
      portal.message(confirmation, error.message, true);
    } finally {
      submit.disabled = false;
    }
  });
});
