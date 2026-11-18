"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const tbody = document.querySelector("table tbody");
  if (!tbody) return;
  const statusModal = document.getElementById("account-status-modal");
  const statusForm = document.getElementById("account-status-form");
  const statusSelect = document.getElementById("account-status-select");
  const statusDescription = document.getElementById("account-status-description");
  const statusError = document.getElementById("account-status-error");
  const statusSaveButton = document.getElementById("account-status-save");
  const reviewModal = document.getElementById("resident-review-modal");
  const reviewForm = document.getElementById("resident-review-form");
  const reviewSelect = document.getElementById("resident-review-status");
  const reviewNote = document.getElementById("resident-review-note");
  const reviewNoteHint = document.getElementById("resident-review-note-hint");
  const reviewDescription = document.getElementById("resident-review-description");
  const reviewError = document.getElementById("resident-review-error");
  const reviewSaveButton = document.getElementById("resident-review-save");
  let selectedUser = null;
  let statusTrigger = null;
  let reviewUser = null;
  let reviewTrigger = null;
  const closeStatusModal = () => {
    statusModal.classList.remove("is-open");
    statusModal.setAttribute("aria-hidden", "true");
    statusTrigger?.focus();
    selectedUser = null;
    statusTrigger = null;
  };
  statusModal.querySelectorAll("[data-close-account-status]").forEach((button) => {
    button.addEventListener("click", closeStatusModal);
  });
  statusModal.addEventListener("click", (event) => {
    if (event.target === statusModal) closeStatusModal();
  });
  statusModal.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      event.preventDefault();
      closeStatusModal();
      return;
    }
    if (event.key !== "Tab") return;
    const focusable = Array.from(statusModal.querySelectorAll("button:not([disabled]), select:not([disabled])"));
    const first = focusable[0];
    const last = focusable[focusable.length - 1];
    if (event.shiftKey && document.activeElement === first) {
      event.preventDefault();
      last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
      event.preventDefault();
      first.focus();
    }
  });
  const closeReviewModal = () => {
    reviewModal.classList.remove("is-open");
    reviewModal.setAttribute("aria-hidden", "true");
    reviewTrigger?.focus();
    reviewUser = null;
    reviewTrigger = null;
  };
  reviewModal.querySelectorAll("[data-close-resident-review]").forEach((button) => {
    button.addEventListener("click", closeReviewModal);
  });
  reviewModal.addEventListener("click", (event) => {
    if (event.target === reviewModal) closeReviewModal();
  });
  reviewModal.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      event.preventDefault();
      closeReviewModal();
      return;
    }
    if (event.key !== "Tab") return;
    const focusable = Array.from(reviewModal.querySelectorAll("button:not([disabled]), select:not([disabled]), textarea:not([disabled])"));
    const first = focusable[0];
    const last = focusable[focusable.length - 1];
    if (event.shiftKey && document.activeElement === first) {
      event.preventDefault();
      last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
      event.preventDefault();
      first.focus();
    }
  });
  const updateReviewNoteRequirement = () => {
    const required = reviewSelect.value === "rejected";
    reviewNote.required = required;
    reviewNoteHint.textContent = required ? "(required for rejection)" : "(optional)";
  };
  reviewSelect.addEventListener("change", updateReviewNoteRequirement);
  reviewForm.addEventListener("submit", async (event) => {
    event.preventDefault();
    if (!reviewUser) return;
    const verification = reviewSelect.value;
    const note = reviewNote.value.trim();
    if (!["pending", "verified", "rejected"].includes(verification)) return;
    if (verification === "rejected" && !note) {
      reviewError.textContent = "Enter a reason before rejecting resident verification.";
      reviewError.hidden = false;
      reviewNote.focus();
      return;
    }
    reviewSaveButton.disabled = true;
    reviewError.hidden = true;
    try {
      await portal.request("admin.update-user", {
        method: "POST",
        body: { accountId: reviewUser.account_id, verification, note },
      });
      location.reload();
    } catch (error) {
      reviewError.textContent = error.message;
      reviewError.hidden = false;
      reviewSaveButton.disabled = false;
    }
  });
  statusForm.addEventListener("submit", async (event) => {
    event.preventDefault();
    if (!selectedUser) return;
    const status = statusSelect.value;
    if (!["active", "pending", "suspended"].includes(status)) return;
    if (status === selectedUser.status) {
      closeStatusModal();
      return;
    }
    statusSaveButton.disabled = true;
    statusError.hidden = true;
    try {
      await portal.request("admin.update-user", {
        method: "POST",
        body: { accountId: selectedUser.account_id, status },
      });
      location.reload();
    } catch (error) {
      statusError.textContent = error.message;
      statusError.hidden = false;
      statusSaveButton.disabled = false;
    }
  });
  try {
    const { users } = await portal.request("admin.users");
    tbody.replaceChildren(...users.map((user) => {
      const row = document.createElement("tr");
      row.dataset.accountStatus = user.status;
      row.dataset.verificationStatus = user.verification;
      const fullName = [user.first_name, user.middle_name, user.last_name]
        .filter((name) => typeof name === "string" && name.trim())
        .map((name) => name.trim())
        .join(" ");
      const email = portal.escape(user.email).replace(/([@._+-])/g, "$1<wbr>");
      row.dataset.userDetails = "true";
      row.tabIndex = 0;
      row.setAttribute("aria-label", `Open resident details for ${fullName}`);
      row.innerHTML = `<td><strong>${portal.escape(fullName)}</strong></td>
        <td>${email}</td><td>${portal.escape(user.barangay_name || "—")}</td>
        <td>${portal.date(user.created_at)}</td>
        <td>${portal.status(user.status)} ${portal.status(user.verification)}</td>
        <td class="table-actions"><button class="button button-small button-outline" type="button">Account status</button>
          <button class="button button-small button-ghost" type="button">Review resident</button>
          <button class="button button-small button-outline" type="button">Verification documents</button></td>`;
      const [accountButton, verificationButton, documentsButton] = row.querySelectorAll("button");
      const openResidentDetails = () => {
        location.assign(`user-details.php?accountId=${encodeURIComponent(user.account_id)}`);
      };
      row.addEventListener("click", (event) => {
        if (event.target.closest("button, a, input, select, textarea")) return;
        openResidentDetails();
      });
      row.addEventListener("keydown", (event) => {
        if (event.target !== row || (event.key !== "Enter" && event.key !== " ")) return;
        event.preventDefault();
        openResidentDetails();
      });
      accountButton.addEventListener("click", () => {
        selectedUser = user;
        statusTrigger = accountButton;
        statusSelect.value = user.status;
        statusDescription.textContent = `Choose an account status for ${fullName}.`;
        statusError.hidden = true;
        statusSaveButton.disabled = false;
        statusModal.classList.add("is-open");
        statusModal.setAttribute("aria-hidden", "false");
        statusSelect.focus();
      });
      verificationButton.addEventListener("click", () => {
        reviewUser = user;
        reviewTrigger = verificationButton;
        reviewSelect.value = user.verification;
        reviewNote.value = user.verification_note || "";
        reviewDescription.textContent = `Review verification for ${fullName}.`;
        reviewError.hidden = true;
        reviewSaveButton.disabled = false;
        updateReviewNoteRequirement();
        reviewModal.classList.add("is-open");
        reviewModal.setAttribute("aria-hidden", "false");
        reviewSelect.focus();
      });
      documentsButton.addEventListener("click", async () => {
        const existing = row.nextElementSibling;
        if (existing?.classList.contains("verification-documents-row")) {
          existing.remove();
          return;
        }
        documentsButton.disabled = true;
        try {
          const { documents } = await portal.request("admin.user-documents", {
            query: { accountId: user.account_id },
          });
          const detailRow = document.createElement("tr");
          detailRow.className = "verification-documents-row";
          const cell = document.createElement("td");
          cell.colSpan = 6;
          if (!documents.length) {
            cell.textContent = "No verification documents submitted.";
          } else {
            documents.forEach((documentInfo) => {
              const url = `../../includes/api.php?action=download&type=verification&id=${encodeURIComponent(documentInfo.document_id)}&inline=1`;
              if (documentInfo.mime_type.startsWith("image/")) {
                const link = document.createElement("a");
                link.href = url;
                link.target = "_blank";
                link.rel = "noopener";
                const image = document.createElement("img");
                image.className = "report-preview";
                image.src = url;
                image.alt = `Verification document: ${documentInfo.file_name}`;
                link.append(image);
                cell.append(link);
              } else {
                const link = document.createElement("a");
                link.className = "text-link";
                link.href = url;
                link.textContent = documentInfo.file_name;
                link.target = "_blank";
                link.rel = "noopener";
                cell.append(link, document.createElement("br"));
              }
            });
          }
          detailRow.append(cell);
          row.after(detailRow);
        } catch (error) {
          portal.message(row, error.message, true);
        } finally {
          documentsButton.disabled = false;
        }
      });
      return row;
    }));
    const search = document.getElementById("resident-search");
    const accountFilter = document.getElementById("account-status-filter");
    const verificationFilter = document.getElementById("verification-status-filter");
    const emptyMessage = document.getElementById("users-filter-empty");
    const applyFilters = () => {
      const query = search.value.trim().toLocaleLowerCase();
      const accountStatus = accountFilter.value;
      const verificationStatus = verificationFilter.value;
      let visibleCount = 0;
      Array.from(tbody.querySelectorAll("tr:not(.verification-documents-row)")).forEach((row) => {
        const matches = row.textContent.toLocaleLowerCase().includes(query)
          && (!accountStatus || row.dataset.accountStatus === accountStatus)
          && (!verificationStatus || row.dataset.verificationStatus === verificationStatus);
        row.hidden = !matches;
        if (row.nextElementSibling?.classList.contains("verification-documents-row")) {
          row.nextElementSibling.hidden = !matches;
        }
        if (matches) visibleCount += 1;
      });
      emptyMessage.hidden = visibleCount > 0;
    };
    search.addEventListener("input", applyFilters);
    accountFilter.addEventListener("change", applyFilters);
    verificationFilter.addEventListener("change", applyFilters);
    applyFilters();
  } catch (error) {
    tbody.innerHTML = "<tr><td colspan=\"6\">Resident accounts could not be loaded.</td></tr>";
    portal.message(document.querySelector(".card"), error.message, true);
  }
});
