(function () {
const registerForm = document.getElementById("registration-form");

if (registerForm) {
    const status = document.getElementById("registration-status");
    const emailInput = registerForm.elements.namedItem("email");
    const passwordInput = registerForm.elements.namedItem("password");
    const confirmPasswordInput = registerForm.elements.namedItem("confirmPassword");
    const requiredFields = Array.from(registerForm.querySelectorAll("[required]"));
    const passwordToggleButtons = registerForm.querySelectorAll(".register-password-toggle");
    const eyeIconPathData =
        "M2 12s3.7-7 10-7 10 7 10 7-3.7 7-10 7-10-7-10-7Z M12 9a3 3 0 1 0 0 6a3 3 0 0 0 0-6Z";
    const eyeSlashIconPathData =
        "M2 12s3.7-7 10-7 10 7 10 7-3.7 7-10 7-10-7-10-7Z M12 9a3 3 0 1 0 0 6a3 3 0 0 0 0-6Z M3 3l18 18";

    const requiredFieldMessages = {
        firstName: "Please enter your first name.",
        lastName: "Please enter your last name.",
        email: "Please enter your email address.",
        mobile: "Please enter your mobile number.",
        barangay_id: "Please select your barangay.",
        address: "Please enter your residential address.",
        password: "Please enter a password.",
        confirmPassword: "Please confirm your password.",
        policyConsent: "Please agree to the Terms of Service and Privacy Policy.",
    };

    function getFieldErrorElement(field) {
        const errorId = `register-error-${field.name}`;
        let error = document.getElementById(errorId);
        if (error) return error;

        error = document.createElement("small");
        error.id = errorId;
        error.className = "register-field-error";
        error.hidden = true;
        error.setAttribute("aria-live", "polite");

        const label = field.closest("label");
        const hint = label ? label.querySelector(".register-password-hint") : null;
        if (hint) {
            hint.insertAdjacentElement("afterend", error);
        } else if (field.type === "checkbox" && label) {
            label.insertAdjacentElement("afterend", error);
        } else {
            field.insertAdjacentElement("afterend", error);
        }

        const describedBy = (field.getAttribute("aria-describedby") || "")
            .split(/\s+/)
            .filter(Boolean);
        if (!describedBy.includes(errorId)) {
            describedBy.push(errorId);
            field.setAttribute("aria-describedby", describedBy.join(" "));
        }

        return error;
    }

    function getFieldErrorMessage(field) {
        const isEmpty = field.type === "checkbox"
            ? !field.checked
            : field.value.trim() === "";

        if (field.required && isEmpty) {
            return requiredFieldMessages[field.name] || "This field is required.";
        }
        if (field.name === "confirmPassword" && field.value !== passwordInput.value) {
            return "The passwords do not match.";
        }
        if (field.validity.typeMismatch) {
            return "Enter a valid email address.";
        }
        if (field.name === "mobile" && field.validity.patternMismatch) {
            return "Enter a valid Philippine mobile number.";
        }
        if (field.name === "password" && !field.validity.valid) {
            return "Enter a password that meets the requirements above.";
        }
        if (!field.validity.valid) {
            return field.validationMessage || "Please check this field.";
        }

        return "";
    }

    function validateField(field, reveal) {
        const error = getFieldErrorElement(field);
        if (reveal) error.dataset.touched = "true";
        if (error.dataset.touched !== "true") return true;

        const message = getFieldErrorMessage(field);
        error.textContent = message;
        error.hidden = message === "";

        if (message) {
            field.setAttribute("aria-invalid", "true");
        } else {
            field.removeAttribute("aria-invalid");
        }

        return message === "";
    }

    passwordToggleButtons.forEach((toggleButton) => {
        const passwordField = document.getElementById(toggleButton.getAttribute("aria-controls"));
        const passwordFieldContainer = toggleButton.closest(".register-password-field");
        const passwordIconPath = toggleButton.querySelector("path");

        if (!passwordField || !passwordFieldContainer || !passwordIconPath) return;

        const syncPasswordToggle = () => {
            const isVisible = passwordField.type === "text";
            passwordFieldContainer.classList.toggle("has-value", passwordField.value.length > 0);
            passwordIconPath.setAttribute(
                "d",
                isVisible ? eyeIconPathData : eyeSlashIconPathData,
            );
            toggleButton.setAttribute("aria-label", isVisible ? "Hide password" : "Show password");
            toggleButton.setAttribute("title", isVisible ? "Hide password" : "Show password");
            toggleButton.setAttribute("aria-pressed", String(isVisible));
        };

        passwordField.addEventListener("input", syncPasswordToggle);

        toggleButton.addEventListener("mousedown", (event) => {
            if (event.button === 0) event.preventDefault();
        });

        toggleButton.addEventListener("click", (event) => {
            const inputWasFocused = document.activeElement === passwordField;
            const start = inputWasFocused ? passwordField.selectionStart : passwordField.value.length;
            const end = inputWasFocused ? passwordField.selectionEnd : passwordField.value.length;
            const shouldKeepInputFocused = event.detail > 0 || inputWasFocused;

            passwordField.type = passwordField.type === "password" ? "text" : "password";
            syncPasswordToggle();

            if (shouldKeepInputFocused) {
                requestAnimationFrame(() => {
                    passwordField.focus();
                    if (start !== null && end !== null) {
                        passwordField.setSelectionRange(start, end);
                    }
                });
            }
        });

        syncPasswordToggle();
    });
    
    function normalizeFields() {
        ["firstName", "middleName", "lastName", "mobile", "address"].forEach((name) => {
            const field = registerForm.elements.namedItem(name);
            if (field) field.value = field.value.trim();
        });

        if (emailInput) {
            emailInput.value = emailInput.value.trim().toLowerCase();
        }
    }

    function validatePasswordConfirmation() {
        if (!confirmPasswordInput || !passwordInput) return;

        const mismatch = confirmPasswordInput.value &&
            confirmPasswordInput.value !== passwordInput.value;
        confirmPasswordInput.setCustomValidity(
            mismatch ? "The passwords do not match." : "",
        );

        if (mismatch && status) {
            status.textContent = "The passwords do not match.";
            status.hidden = false;
        } else if (status) {
            status.textContent = "";
            status.hidden = true;
        }
    }

    function updateFieldAfterEdit(field) {
        if (!requiredFields.includes(field)) return;

        if (field === passwordInput || field === confirmPasswordInput) {
            validatePasswordConfirmation();
        }

        const error = document.getElementById(`register-error-${field.name}`);
        if (error && error.dataset.touched === "true") {
            validateField(field);
        }

        if (field === passwordInput) {
            const confirmError = document.getElementById(
                `register-error-${confirmPasswordInput.name}`,
            );
            if (confirmError && confirmError.dataset.touched === "true") {
                validateField(confirmPasswordInput);
            }
        }
    }

    registerForm.addEventListener("focusout", (event) => {
        const field = event.target;
        if (!requiredFields.includes(field)) return;

        if (field === passwordInput || field === confirmPasswordInput) {
            validatePasswordConfirmation();
        }
        validateField(field, true);
    });

    registerForm.addEventListener("invalid", (event) => {
        const field = event.target;
        if (!requiredFields.includes(field)) return;

        if (field === passwordInput || field === confirmPasswordInput) {
            validatePasswordConfirmation();
        }
        validateField(field, true);
    }, true);

    const locationButton = document.getElementById("capture-location");
    const locationStatus = document.getElementById("location-status");

    if (locationButton && locationStatus) {
        locationButton.addEventListener("click", () => {
            if (!navigator.geolocation) {
                locationStatus.textContent = "This browser does not support location access.";
                locationStatus.hidden = false;
                return;
            }

            locationButton.disabled = true;
            locationButton.textContent = "Finding location...";
            locationStatus.textContent = "Waiting for location permission...";
            locationStatus.hidden = false;

            navigator.geolocation.getCurrentPosition(
                () => {
                    locationButton.disabled = false;
                    locationButton.textContent = "Location pinned";
                    locationStatus.textContent = "Location pinned successfully.";
                },
                (error) => {
                    const messages = {
                        1: "Location permission was denied. Allow it in your browser settings and try again.",
                        2: "Your location could not be determined. Try again from a place with a location signal.",
                        3: "Location lookup timed out. Please try again.",
                    };

                    locationButton.disabled = false;
                    locationButton.textContent = "Pin my current location";
                    locationStatus.textContent = messages[error.code] || "Location could not be captured. Please try again.";
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 },
            );
        });
    }

    registerForm.addEventListener("input", (event) => {
        updateFieldAfterEdit(event.target);
        if (status && status.textContent !== "The passwords do not match.") {
            status.textContent = "";
            status.hidden = true;
        }
    });

    registerForm.addEventListener("change", (event) => {
        updateFieldAfterEdit(event.target);
    });

    registerForm.addEventListener("submit", (event) => {
        normalizeFields();
        validatePasswordConfirmation();
        requiredFields.forEach((field) => validateField(field, true));
        if (!registerForm.reportValidity()) {
            event.preventDefault();
        }
    });
}
})();