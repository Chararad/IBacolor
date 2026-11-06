"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const form = document.getElementById("request-form");
  if (!form) return;
  const confirmation = document.getElementById("request-confirmation");
  const serviceSelect = form.elements.service;
  try {
    const [{ services }, { profile }] = await Promise.all([
      portal.request("resident.services"),
      portal.request("resident.profile"),
    ]);
    serviceSelect.replaceChildren(new Option("Select a service", ""));
    services.forEach((service) => serviceSelect.add(new Option(service.name, service.service_id)));
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
