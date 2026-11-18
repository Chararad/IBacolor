"use strict";

(() => {
  const form = document.querySelector(".transparency-upload-form");
  const modal = document.getElementById("transparency-upload-validation-modal");
  const fieldsList = document.getElementById("transparency-upload-validation-fields");
  if (!form || !modal || !fieldsList) return;

  const closeButtons = modal.querySelectorAll("[data-close-transparency-validation]");
  let firstInvalidField = null;

  const closeModal = () => {
    modal.classList.remove("is-open");
    modal.setAttribute("aria-hidden", "true");
    if (firstInvalidField?.isConnected) firstInvalidField.focus();
    firstInvalidField = null;
  };

  form.addEventListener("submit", (event) => {
    const invalidFields = Array.from(form.querySelectorAll("input[required], select[required], textarea[required]"))
      .filter((field) => !field.checkValidity());
    if (invalidFields.length === 0) return;

    event.preventDefault();
    firstInvalidField = invalidFields[0];
    fieldsList.replaceChildren();
    invalidFields.forEach((field) => {
      const label = field.closest("label");
      const labelText = Array.from(label?.childNodes || [])
        .find((node) => node.nodeType === Node.TEXT_NODE && node.textContent.trim());
      const item = document.createElement("li");
      item.textContent = labelText?.textContent.trim() || field.name;
      fieldsList.append(item);
    });
    modal.classList.add("is-open");
    modal.setAttribute("aria-hidden", "false");
    modal.querySelector("[data-close-transparency-validation]")?.focus();
  });

  closeButtons.forEach((button) => button.addEventListener("click", closeModal));
  modal.addEventListener("click", (event) => {
    if (event.target === modal) closeModal();
  });
  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && modal.classList.contains("is-open")) closeModal();
  });
})();
