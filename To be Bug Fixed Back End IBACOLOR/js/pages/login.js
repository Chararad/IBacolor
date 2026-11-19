"use strict";

(() => {
  const passwordInput = document.getElementById("login-password");
  const toggleButton = document.getElementById("toggle-password-visibility");
  const passwordIconPath = document.getElementById(
    "password-visibility-icon-path"
  );
  const eyeIconPathData =
    "M2 12s3.7-7 10-7 10 7 10 7-3.7 7-10 7-10-7-10-7Z M12 9a3 3 0 1 0 0 6a3 3 0 0 0 0-6Z";
  const eyeSlashIconPathData =
    "M2 12s3.7-7 10-7 10 7 10 7-3.7 7-10 7-10-7-10-7Z M12 9a3 3 0 1 0 0 6a3 3 0 0 0 0-6Z M3 3l18 18";

  if (!passwordInput || !toggleButton || !passwordIconPath) {
    return;
  }

  const syncVisibilityState = () => {
    const isVisible = passwordInput.type === "text";
    passwordIconPath.setAttribute(
      "d",
      isVisible ? eyeIconPathData : eyeSlashIconPathData
    );
    toggleButton.setAttribute(
      "aria-label",
      isVisible ? "Hide password" : "Show password"
    );
    toggleButton.setAttribute("title", isVisible ? "Hide password" : "Show password");
    toggleButton.setAttribute("aria-pressed", String(isVisible));
  };

  const clearPasswordInput = () => {
    passwordInput.value = "";
    syncVisibilityState();
  };

  clearPasswordInput();
  window.addEventListener("pageshow", clearPasswordInput);

  toggleButton.addEventListener("mousedown", (event) => {
    if (event.button === 0) event.preventDefault();
  });

  toggleButton.addEventListener("click", (event) => {
    const inputWasFocused = document.activeElement === passwordInput;
    const start = inputWasFocused
      ? passwordInput.selectionStart
      : passwordInput.value.length;
    const end = inputWasFocused
      ? passwordInput.selectionEnd
      : passwordInput.value.length;
    const shouldKeepInputFocused = event.detail > 0 || inputWasFocused;

    passwordInput.type =
      passwordInput.type === "password" ? "text" : "password";
    syncVisibilityState();

    if (shouldKeepInputFocused) {
      requestAnimationFrame(() => {
        passwordInput.focus();
        if (start !== null && end !== null) {
          passwordInput.setSelectionRange(start, end);
        }
      });
    }
  });

  syncVisibilityState();
})();
