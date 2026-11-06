const registerForm = document.getElementById("registration-form");

if (registerForm) {
    const status = document.getElementById("registration-status");
    const emailInput = registerForm.elements.namedItem("email");
    const passwordInput = registerForm.elements.namedItem("password");
    const confirmPasswordInput = registerForm.elements.namedItem("confirmPassword");
    
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

    if (passwordInput && confirmPasswordInput) {
        passwordInput.addEventListener("input", validatePasswordConfirmation);
        confirmPasswordInput.addEventListener("input", validatePasswordConfirmation);
    }

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
                (position) => {
                    const { latitude, longitude, accuracy } = position.coords;
                    locationButton.disabled = false;
                    locationButton.textContent = "Location pinned";
                    locationStatus.textContent = `Pinned at ${latitude.toFixed(5)}, ${longitude.toFixed(5)} (accuracy about ${Math.round(accuracy)} m). Coordinates are not saved with your account.`;
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

    registerForm.addEventListener("input", () => {
        if (status && status.textContent !== "The passwords do not match.") {
            status.textContent = "";
            status.hidden = true;
        }
    });

    registerForm.addEventListener("submit", (event) => {
        normalizeFields();
        validatePasswordConfirmation();
        if (!registerForm.reportValidity()) {
            event.preventDefault();
        }
    });
}