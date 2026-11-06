"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const form = document.getElementById("profile-form");
  if (!form) return;
  const notice = document.getElementById("profile-status");
  try {
    const [{ profile }, { barangays }] = await Promise.all([
      portal.request("resident.profile"),
      portal.request("resident.barangays"),
    ]);
    if (!profile) throw new Error("Resident profile could not be found.");
    form.elements.firstName.value = profile.first_name || "";
    form.elements.lastName.value = profile.last_name || "";
    form.elements.email.value = profile.email || "";
    form.elements.mobile.value = profile.mobile_number || "";
    form.elements.address.value = profile.address || "";
    const select = form.elements.barangay;
    select.replaceChildren(new Option("Select barangay", ""));
    barangays.forEach((item) => select.add(new Option(item.name, item.barangay_id)));
    select.value = profile.barangay_id;
    const checks = form.querySelectorAll('input[type="checkbox"]');
    checks[0].checked = Boolean(Number(profile.request_updates));
    checks[1].checked = Boolean(Number(profile.announcement_updates));
    const name = document.querySelector("[data-profile-name]");
    if (name) name.textContent = `${profile.first_name} ${profile.last_name}`;
    const memberSince = document.querySelector(".profile-banner p");
    if (memberSince) memberSince.textContent = `Resident account · Member since ${portal.date(profile.created_at)}`;
  } catch (error) {
    portal.message(notice, error.message, true);
  }
  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    try {
      const values = portal.formData(form);
      await portal.request("resident.save-profile", {
        method: "POST",
        body: { ...values, barangay: values.barangay },
      });
      await portal.request("resident.save-preferences", {
        method: "POST",
        body: {
          requestUpdates: form.querySelectorAll('input[type="checkbox"]')[0].checked,
          announcementUpdates: form.querySelectorAll('input[type="checkbox"]')[1].checked,
        },
      });
      portal.message(notice, "Profile and notification preferences saved.");
    } catch (error) {
      portal.message(notice, error.message, true);
    }
  });
});
