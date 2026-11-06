const adminMenuToggle = document.querySelector(".menu-toggle");
const adminNavigation = document.getElementById("main-nav");

if (adminMenuToggle && adminNavigation) {
    adminMenuToggle.addEventListener("click", () => {
        const isOpen = document.body.classList.toggle("nav-open");
        adminMenuToggle.setAttribute("aria-expanded", String(isOpen));
    });

    adminNavigation.addEventListener("click", (event) => {
        if (!event.target.closest("a")) return;

        document.body.classList.remove("nav-open");
        adminMenuToggle.setAttribute("aria-expanded", "false");
    });

    document.addEventListener("keydown", (event) => {
        if (event.key !== "Escape" || !document.body.classList.contains("nav-open")) return;

        document.body.classList.remove("nav-open");
        adminMenuToggle.setAttribute("aria-expanded", "false");
        adminMenuToggle.focus();
    });
}

const adminCurrentPath = window.location.pathname;

document.querySelectorAll(".site-nav .nav-links a").forEach((link) => {
    if (new URL(link.href, window.location.href).pathname === adminCurrentPath) {
        link.setAttribute("aria-current", "page");
    }
});

window.addEventListener("pageshow", (event) => {
    if (event.persisted) window.location.reload();
});
