"use strict";

window.IBacolorModules = window.IBacolorModules || {};
(function () {
// Form feedback: prevents demo forms from submitting and shows their confirmation state.
function initializeFormFeedback() {
  document.addEventListener("submit", (event) => {
    const form = event.target;
    if (
      !(form instanceof HTMLFormElement) ||
      !form.hasAttribute("data-feedback-form")
    )
      return;
    if (
      form.id === "registration-form" ||
      form.id === "login-form" ||
      form.id === "request-form" ||
      form.id === "community-report-form" ||
      form.id === "profile-form" ||
      form.id === "settings-form"
    )
      return;
    event.preventDefault();

    let confirmation = form.querySelector(".form-confirmation");
    if (!confirmation) {
      confirmation = document.createElement("div");
      confirmation.className = "notice notice-info form-confirmation";
      confirmation.setAttribute("role", "status");
      form.prepend(confirmation);
    }
    confirmation.textContent = "Your changes were saved in this browser.";
  });
}

window.IBacolorModules.feedback = { initializeFormFeedback };
})();
