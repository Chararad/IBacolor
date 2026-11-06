function updateResidentButton() {
    const button = document.getElementById("resident-login-button");
    if (!button || button.dataset.authenticated !== "true") return;

    button.href = button.dataset.logoutHref;
    button.textContent = "Sign out";
}

document.addEventListener("DOMContentLoaded", updateResidentButton);