"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const tbody = document.getElementById("admin-documents-list");
  const previewModal = document.getElementById("document-preview-modal");
  const previewTitle = document.getElementById("document-preview-title");
  const previewDetails = document.getElementById("document-preview-details");
  const previewDownload = document.getElementById("document-preview-download");
  const previewFrame = document.getElementById("document-preview-frame");
  const previewImage = document.getElementById("document-preview-image");
  const previewUnavailable = document.getElementById("document-preview-unavailable");
  const reviewModal = document.getElementById("document-review-modal");
  const reviewForm = document.getElementById("document-review-form");
  const reviewStatus = document.getElementById("document-review-status");
  const reviewNote = document.getElementById("document-review-note");
  const reviewNoteHelp = document.getElementById("document-review-note-help");
  const reviewMessage = document.getElementById("document-review-message");
  const reviewName = document.getElementById("document-review-name");
  if (!tbody || !previewModal || !previewTitle || !previewDetails || !previewDownload
    || !previewFrame || !previewImage || !previewUnavailable || !reviewModal || !reviewForm
    || !reviewStatus || !reviewNote || !reviewNoteHelp || !reviewMessage || !reviewName) return;

  let previewTrigger = null;
  let reviewTrigger = null;
  const documentUrl = (documentId) =>
    `../../includes/api.php?action=download&type=request&id=${encodeURIComponent(documentId)}`;

  const updateReviewNoteRequirement = () => {
    const noteRequired = reviewStatus.value === "needs correction";
    reviewNote.required = noteRequired;
    reviewNoteHelp.textContent = noteRequired
      ? "Explain what needs to be corrected."
      : "A note is required if the document needs correction.";
  };

  const closeReview = (restoreFocus = true) => {
    reviewModal.classList.remove("is-open");
    reviewModal.setAttribute("aria-hidden", "true");
    reviewForm.reset();
    reviewMessage.hidden = true;
    reviewMessage.textContent = "";
    updateReviewNoteRequirement();
    if (restoreFocus && reviewTrigger?.isConnected) reviewTrigger.focus();
    reviewTrigger = null;
  };

  const openReview = (documentItem, trigger) => {
    reviewTrigger = trigger;
    reviewForm.reset();
    reviewForm.elements.documentId.value = documentItem.document_id;
    reviewStatus.value = ["submitted", "accepted", "needs correction"].includes(documentItem.review_status)
      ? documentItem.review_status
      : "submitted";
    reviewNote.value = documentItem.review_note || "";
    reviewName.textContent = documentItem.document_name || "";
    reviewMessage.hidden = true;
    reviewMessage.textContent = "";
    updateReviewNoteRequirement();
    reviewModal.classList.add("is-open");
    reviewModal.setAttribute("aria-hidden", "false");
    reviewStatus.focus();
  };

  const closePreview = () => {
    previewFrame.removeAttribute("src");
    previewFrame.hidden = true;
    previewImage.removeAttribute("src");
    previewImage.hidden = true;
    previewUnavailable.hidden = true;
    previewModal.classList.remove("is-open");
    previewModal.setAttribute("aria-hidden", "true");
    if (previewTrigger?.isConnected) previewTrigger.focus();
    previewTrigger = null;
  };

  const openPreview = (documentItem, trigger) => {
    previewTrigger = trigger;
    previewTitle.textContent = documentItem.document_name || "Document preview";
    previewDetails.textContent = [documentItem.owner_name, documentItem.mime_type, documentItem.reference]
      .filter(Boolean)
      .join(" · ");
    previewDownload.href = documentUrl(documentItem.document_id);

    const isPdf = documentItem.mime_type === "application/pdf";
    const isImage = ["image/jpeg", "image/png", "image/webp"].includes(documentItem.mime_type);
    previewUnavailable.hidden = isPdf || isImage;
    previewFrame.hidden = !isPdf;
    previewImage.hidden = !isImage;
    if (isPdf) {
      previewFrame.title = `Preview of ${documentItem.document_name || "document"}`;
      previewFrame.src = `${documentUrl(documentItem.document_id)}&inline=1`;
    } else {
      previewFrame.removeAttribute("src");
    }
    if (isImage) {
      previewImage.alt = documentItem.document_name || "Document preview";
      previewImage.src = `${documentUrl(documentItem.document_id)}&inline=1`;
    } else {
      previewImage.removeAttribute("src");
    }

    previewModal.classList.add("is-open");
    previewModal.setAttribute("aria-hidden", "false");
    previewModal.querySelector("[data-close-document-preview]").focus();
  };

  previewModal.querySelectorAll("[data-close-document-preview]").forEach((button) => {
    button.addEventListener("click", closePreview);
  });
  previewModal.addEventListener("click", (event) => {
    if (event.target === previewModal) closePreview();
  });
  previewModal.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      event.preventDefault();
      closePreview();
      return;
    }
    if (event.key !== "Tab") return;
    const focusable = Array.from(previewModal.querySelectorAll("a[href],button:not([disabled]),iframe"))
      .filter((element) => !element.hidden);
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

  reviewModal.querySelectorAll("[data-close-document-review]").forEach((button) => {
    button.addEventListener("click", () => closeReview());
  });
  reviewModal.addEventListener("click", (event) => {
    if (event.target === reviewModal) closeReview();
  });
  reviewModal.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      event.preventDefault();
      closeReview();
      return;
    }
    if (event.key !== "Tab") return;
    const focusable = Array.from(reviewForm.querySelectorAll(
      'button:not([disabled]), select:not([disabled]), textarea:not([disabled])'
    ));
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
  reviewStatus.addEventListener("change", updateReviewNoteRequirement);
  reviewForm.addEventListener("submit", async (event) => {
    event.preventDefault();
    if (!reviewForm.reportValidity()) return;
    const saveButton = reviewForm.querySelector('[type="submit"]');
    saveButton.disabled = true;
    reviewMessage.hidden = true;
    try {
      await portal.request("admin.review-document", {
        method: "POST",
        body: {
          documentId: reviewForm.elements.documentId.value,
          status: reviewStatus.value,
          note: reviewNote.value.trim(),
        },
      });
      closeReview(false);
      location.reload();
    } catch (error) {
      portal.message(reviewMessage, error.message, true);
    } finally {
      saveButton.disabled = false;
    }
  });

  try {
    const { documents } = await portal.request("admin.documents");
    if (!documents.length) {
      tbody.innerHTML = "<tr><td colspan=\"6\">No documents have been uploaded.</td></tr>";
      return;
    }
    tbody.replaceChildren(...documents.map((documentItem) => {
      const row = document.createElement("tr");
      row.innerHTML = `<td><strong>${portal.escape(documentItem.document_name)}</strong><br><small>${portal.escape(documentItem.reference)}</small></td>
        <td>${portal.escape(documentItem.owner_name)}</td><td>${portal.escape(documentItem.mime_type)}</td>
        <td>${portal.date(documentItem.uploaded_at)}</td><td>${portal.status(documentItem.review_status)}</td>
        <td class="table-actions"><button class="button button-small button-outline" type="button" data-preview-document>Preview</button>
        <a class="button button-small button-outline" href="${documentUrl(documentItem.document_id)}">Download</a>
        <button class="button button-small button-outline" type="button" data-review-document>Review</button></td>`;
      row.querySelector("[data-preview-document]").addEventListener("click", (event) => {
        openPreview(documentItem, event.currentTarget);
      });
      row.querySelector("[data-review-document]").addEventListener("click", (event) => {
        openReview(documentItem, event.currentTarget);
      });
      return row;
    }));
  } catch (error) {
    tbody.innerHTML = "<tr><td colspan=\"6\">Documents could not be loaded.</td></tr>";
    portal.message(document.querySelector(".card"), error.message, true);
  }
});
