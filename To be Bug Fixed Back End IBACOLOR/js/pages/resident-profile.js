"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const form = document.getElementById("profile-form");
  if (!form) return;
  const notice = document.getElementById("profile-status");
  try {
    const [{ profile }, { barangays }, { documents }] = await Promise.all([
      portal.request("resident.profile"),
      portal.request("resident.barangays"),
      portal.request("resident.verification-documents"),
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
    const verificationNotice = document.getElementById("resident-verification-status");
    if (verificationNotice) {
      const verification = profile.verification_status || "pending";
      const labels = {
        pending: "Resident verification is pending review.",
        verified: "Your resident account is verified.",
        rejected: "Resident verification was rejected. Review the administrator's remarks below.",
      };
      verificationNotice.textContent = `${labels[verification] || labels.pending}${profile.verification_note ? ` ${profile.verification_note}` : ""}`;
      verificationNotice.classList.toggle("notice-warning", verification !== "verified");
      verificationNotice.classList.toggle("notice-info", verification === "verified");
    }
    const documentList = document.getElementById("verification-documents-list");
    const renderVerificationDocuments = (items) => {
      documentList.replaceChildren();
      if (!items.length) {
        documentList.textContent = "No verification documents submitted.";
        return;
      }
      items.forEach((item) => {
        const link = document.createElement("a");
        link.className = "text-link";
        link.href = `../../includes/api.php?action=download&type=verification&id=${encodeURIComponent(item.document_id)}&inline=1`;
        link.textContent = `${item.file_name} · ${portal.date(item.uploaded_at)}`;
        link.target = "_blank";
        link.rel = "noopener";
        documentList.append(link, document.createElement("br"));
      });
    };
    renderVerificationDocuments(documents);
    const documentForm = document.getElementById("verification-documents-form");
    if (documentForm) {
      const uploadInput = documentForm.elements.namedItem("documents[]");
      uploadInput.addEventListener("change", () => {
        const files = Array.from(uploadInput.files);
        uploadInput.setCustomValidity(files.length > 5
          ? "Choose no more than five files."
          : files.some((file) => file.size > 10 * 1024 * 1024)
            ? "Each file must be 10 MB or smaller."
            : "");
      });
      documentForm.addEventListener("submit", async (event) => {
        event.preventDefault();
        if (!documentForm.reportValidity()) return;
        const button = documentForm.querySelector("button");
        button.disabled = true;
        try {
          await portal.request("resident.upload-verification-documents", {
            method: "POST",
            body: new FormData(documentForm),
          });
          portal.message(document.getElementById("verification-documents-status"), "Verification documents submitted.");
          const result = await portal.request("resident.verification-documents");
          renderVerificationDocuments(result.documents);
          documentForm.reset();
        } catch (error) {
          portal.message(document.getElementById("verification-documents-status"), error.message, true);
        } finally {
          button.disabled = false;
        }
      });
    }
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
