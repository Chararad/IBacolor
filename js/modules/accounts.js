"use strict";

window.IBacolorModules = window.IBacolorModules || {};
(function () {
const { SESSION_KEY, ACCOUNTS_KEY } = window.IBacolorModules.shared;

function redirectToLogin() {
  const loginPath = window.location.pathname.includes("/resident/")
    ? "../login.php"
    : "login.php";
  const login = new URL(loginPath, window.location.href);
  login.searchParams.set("next", window.location.href);
  window.location.replace(login.href);
}

// Account access: fills resident details, handles sign-in state, and processes registration.
function initializeAccountAccess() {
  const page = document.body.dataset.page;
  const protectedPages = new Set([
    "services",
    "resident/dashboard",
    "resident/profile",
    "resident/settings",
    "resident/reports",
    "resident/requests",
    "resident/request-form",
    "resident/request-details",
  ]);
  const activeEmail = sessionStorage.getItem(SESSION_KEY);
  const storedAccounts = JSON.parse(localStorage.getItem(ACCOUNTS_KEY) || "[]");
  const activeAccount = storedAccounts.find(
    (account) => account.email === activeEmail,
  );
  if (protectedPages.has(page) && !activeAccount) {
    redirectToLogin();
    return;
  }

  if (activeAccount) {
    document.querySelectorAll("[data-profile-field]").forEach((field) => {
      field.value = activeAccount[field.dataset.profileField] || "";
    });
    document.querySelectorAll("[data-profile-name]").forEach((element) => {
      element.textContent = activeAccount.name;
    });
    document.querySelectorAll("[data-resident-name]").forEach((element) => {
      element.textContent = activeAccount.name;
    });
    document.querySelectorAll("[data-resident-greeting]").forEach((element) => {
      element.textContent = `Welcome back, ${activeAccount.firstName}`;
    });
    document.querySelectorAll("[data-resident-barangay]").forEach((element) => {
      element.textContent = `${activeAccount.barangay}, Bacolor`;
    });
  }

  document
    .querySelectorAll(
      "[data-sign-out], body[data-page^='resident'] .site-nav .button-outline",
    )
    .forEach((button) => {
      button.addEventListener("click", (event) => {
        event.preventDefault();
        sessionStorage.removeItem(SESSION_KEY);
        window.location.assign(button.href || "../../index.php");
      });
    });

  const registerForm = document.getElementById("registration-form");
  if (registerForm) {
    registerForm.addEventListener("submit", async (event) => {
      event.preventDefault();
      if (!registerForm.reportValidity()) return;
      const status = document.getElementById("registration-status");
      const data = new FormData(registerForm);
      if (data.get("password") !== data.get("confirmPassword")) {
        status.textContent = "The passwords do not match.";
        status.hidden = false;
        return;
      }
      if (!registerForm.dataset.latitude || !registerForm.dataset.longitude) {
        status.textContent =
          "Allow location access and capture your location before creating an account.";
        status.hidden = false;
        return;
      }
      if (!window.crypto?.subtle) {
        status.textContent =
          "Secure account creation requires a secure browser context (HTTPS or localhost).";
        status.hidden = false;
        return;
      }

      const email = String(data.get("email")).trim().toLowerCase();
      const accounts = JSON.parse(localStorage.getItem(ACCOUNTS_KEY) || "[]");
      if (accounts.some((account) => account.email === email)) {
        status.textContent =
          "An account with this email already exists. Please sign in.";
        status.hidden = false;
        return;
      }
      const salt = window.crypto.randomUUID();
      let passwordHash;
      try {
        passwordHash = await hashPassword(salt, String(data.get("password")));
      } catch (error) {
        console.error("Could not securely hash the password.", error);
        status.textContent =
          "The password could not be secured. Please try again.";
        status.hidden = false;
        return;
      }
      accounts.push({
        email,
        salt,
        passwordHash,
        name: `${data.get("firstName")} ${data.get("lastName")}`,
        firstName: data.get("firstName"),
        middleName: data.get("middleName"),
        lastName: data.get("lastName"),
        mobile: data.get("mobile"),
        barangay: data.get("barangay"),
        address: data.get("address"),
        location: {
          latitude: registerForm.dataset.latitude,
          longitude: registerForm.dataset.longitude,
        },
      });
      localStorage.setItem(ACCOUNTS_KEY, JSON.stringify(accounts));
      sessionStorage.setItem(SESSION_KEY, email);
      window.location.assign("resident/dashboard.php");
    });
  }

  const loginForm = document.getElementById("login-form");
  if (loginForm) {
    loginForm.addEventListener("submit", async (event) => {
      event.preventDefault();
      if (!loginForm.reportValidity()) return;
      const status = document.getElementById("login-status");
      const data = new FormData(loginForm);
      const email = String(data.get("email")).trim().toLowerCase();
      const accounts = JSON.parse(localStorage.getItem(ACCOUNTS_KEY) || "[]");
      const account = accounts.find((item) => item.email === email);
      if (!account || !window.crypto?.subtle) {
        status.textContent = account
          ? "Secure sign-in requires HTTPS or localhost."
          : "No account was found for that email. Create a resident account first.";
        status.hidden = false;
        return;
      }
      let attemptedHash;
      try {
        attemptedHash = await hashPassword(
          account.salt,
          String(data.get("password")),
        );
      } catch (error) {
        console.error("Could not verify the password.", error);
        status.textContent =
          "Sign-in could not be completed. Please try again.";
        status.hidden = false;
        return;
      }
      if (attemptedHash !== account.passwordHash) {
        status.textContent = "The email or password is incorrect.";
        status.hidden = false;
        return;
      }
      sessionStorage.setItem(SESSION_KEY, email);
      const next = new URLSearchParams(window.location.search).get("next");
      let destination = new URL("resident/dashboard.php", window.location.href);
      if (next) {
        try {
          const requestedDestination = new URL(next, window.location.href);
          if (
            requestedDestination.protocol === window.location.protocol &&
            requestedDestination.host === window.location.host
          ) {
            destination = requestedDestination;
          }
        } catch (error) {
          console.error("The requested return address was invalid.", error);
        }
      }
      window.location.assign(destination.href);
    });
  }

  const settingsForm = document.getElementById("settings-form");
  if (settingsForm) {
    const email = sessionStorage.getItem(SESSION_KEY);
    const settingsKey = `ibacolor.settings.${email}`;
    const savedSettings = JSON.parse(localStorage.getItem(settingsKey) || "{}");
    settingsForm.elements.requestNotifications.checked =
      savedSettings.requestNotifications !== false;
    settingsForm.elements.announcementNotifications.checked =
      savedSettings.announcementNotifications !== false;
    settingsForm.addEventListener("submit", async (event) => {
      event.preventDefault();
      if (!settingsForm.reportValidity()) return;
      const status = document.getElementById("settings-status");
      const data = new FormData(settingsForm);
      if (data.get("newPassword") !== data.get("confirmPassword")) {
        status.textContent = "The new passwords do not match.";
        status.hidden = false;
        return;
      }
      const accounts = JSON.parse(localStorage.getItem(ACCOUNTS_KEY) || "[]");
      const account = accounts.find((item) => item.email === email);
      if (!account || !window.crypto?.subtle) {
        status.textContent =
          "The account could not be verified in this browser session.";
        status.hidden = false;
        return;
      }
      const currentHash = await hashPassword(
        account.salt,
        String(data.get("currentPassword")),
      );
      if (currentHash !== account.passwordHash) {
        status.textContent = "The current password is incorrect.";
        status.hidden = false;
        return;
      }
      account.salt = window.crypto.randomUUID();
      account.passwordHash = await hashPassword(
        account.salt,
        String(data.get("newPassword")),
      );
      localStorage.setItem(ACCOUNTS_KEY, JSON.stringify(accounts));
      localStorage.setItem(
        settingsKey,
        JSON.stringify({
          requestNotifications: data.has("requestNotifications"),
          announcementNotifications: data.has("announcementNotifications"),
        }),
      );
      status.textContent = "Settings were saved in this browser.";
      status.hidden = false;
      settingsForm.reset();
    });
  }

  const profileForm = document.getElementById("profile-form");
  if (profileForm) {
    profileForm.addEventListener("submit", (event) => {
      event.preventDefault();
      if (!profileForm.reportValidity()) return;
      const email = sessionStorage.getItem(SESSION_KEY);
      const accounts = JSON.parse(localStorage.getItem(ACCOUNTS_KEY) || "[]");
      const account = accounts.find((item) => item.email === email);
      if (!account) {
        const status = document.getElementById("profile-status");
        status.textContent = "Your account information could not be loaded.";
        status.hidden = false;
        return;
      }
      const data = new FormData(profileForm);
      ["firstName", "lastName", "mobile", "barangay", "address"].forEach(
        (field) => {
          account[field] = data.get(field);
        },
      );
      account.name = `${account.firstName} ${account.lastName}`;
      localStorage.setItem(ACCOUNTS_KEY, JSON.stringify(accounts));
      document.querySelector("[data-profile-name]").textContent = account.name;
      const status = document.getElementById("profile-status");
      status.textContent = "Profile changes were saved in this browser.";
      status.hidden = false;
    });
  }

  document
    .querySelectorAll("#request-form [data-service-select]")
    .forEach((select) => {
      select.replaceChildren(new Option("Select a service", ""));
      window.MOCK_DATA.services.forEach((service) =>
        select.add(new Option(service.name, service.id)),
      );
      const requestedService = new URLSearchParams(window.location.search).get(
        "service",
      );
      if (
        requestedService &&
        window.MOCK_DATA.services.some(
          (service) => service.id === requestedService,
        )
      ) {
        select.value = requestedService;
      }
    });

  const locationButton = document.getElementById("capture-location");
  const reportForm = document.getElementById("registration-form");
  if (locationButton && reportForm) {
    locationButton.addEventListener("click", () => {
      const status = document.getElementById("location-status");
      if (!navigator.geolocation) {
        status.textContent = "This browser does not support location access.";
        status.hidden = false;
        return;
      }
      status.textContent = "Requesting location permission…";
      status.hidden = false;
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const { latitude, longitude } = position.coords;
          reportForm.dataset.latitude = String(latitude);
          reportForm.dataset.longitude = String(longitude);
          status.textContent = `Location pinned at ${latitude.toFixed(5)}, ${longitude.toFixed(5)}.`;
        },
        (error) => {
          const reasons = {
            1: "Location permission was denied. Allow it in your browser settings to register.",
            2: "Your location could not be determined. Try again from a place with a location signal.",
            3: "Location lookup timed out. Please try again.",
          };
          status.textContent =
            reasons[error.code] ||
            "Location could not be captured. Please try again.";
        },
        { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 },
      );
    });
  }
}

async function hashPassword(salt, password) {
  const bytes = new TextEncoder().encode(`${salt}:${password}`);
  const digest = await window.crypto.subtle.digest("SHA-256", bytes);
  return [...new Uint8Array(digest)]
    .map((byte) => byte.toString(16).padStart(2, "0"))
    .join("");
}

window.IBacolorModules.accounts = { initializeAccountAccess };
})();
