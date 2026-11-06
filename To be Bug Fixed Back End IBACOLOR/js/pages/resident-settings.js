"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const form = document.getElementById("settings-form");
  if (!form) return;
  const notice = document.getElementById("settings-status");
  try {
    const { profile } = await portal.request("resident.profile");
    const checks = form.querySelectorAll('input[type="checkbox"]');
    checks[0].checked = Boolean(Number(profile.request_updates));
    checks[1].checked = Boolean(Number(profile.announcement_updates));
  } catch (error) {
    portal.message(notice, error.message, true);
  }
  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    if (!form.reportValidity()) return;
    const values = portal.formData(form);
    try {
      const result = await portal.request("resident.save-settings", {
        method: "POST",
        body: {
          requestUpdates: form.elements.requestNotifications.checked,
          announcementUpdates: form.elements.announcementNotifications.checked,
          currentPassword: values.currentPassword,
          newPassword: values.newPassword,
          confirmPassword: values.confirmPassword,
        },
      });
      portal.message(notice, result.message);
      form.elements.currentPassword.value = "";
      form.elements.newPassword.value = "";
      form.elements.confirmPassword.value = "";
    } catch (error) {
      portal.message(notice, error.message, true);
    }
  });
});
