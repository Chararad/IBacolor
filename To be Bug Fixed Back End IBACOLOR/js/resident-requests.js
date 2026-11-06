const requestsMenuToggle = document.querySelector(".menu-toggle");
const requestsNavigation = document.getElementById("main-nav");

if (requestsMenuToggle && requestsNavigation) {
    requestsMenuToggle.addEventListener("click", () => {
        const isOpen = document.body.classList.toggle("nav-open");
        requestsMenuToggle.setAttribute("aria-expanded", String(isOpen));
    });

    requestsNavigation.addEventListener("click", (event) => {
        if (!event.target.closest("a")) return;

        document.body.classList.remove("nav-open");
        requestsMenuToggle.setAttribute("aria-expanded", "false");
    });

    document.addEventListener("keydown", (event) => {
        if (event.key !== "Escape" || !document.body.classList.contains("nav-open")) return;

        document.body.classList.remove("nav-open");
        requestsMenuToggle.setAttribute("aria-expanded", "false");
        requestsMenuToggle.focus();
    });
}

const currentRequestsPath = window.location.pathname;

document.querySelectorAll(".site-nav .nav-links a").forEach((link) => {
    if (new URL(link.href, window.location.href).pathname === currentRequestsPath) {
        link.setAttribute("aria-current", "page");
    }
});

window.addEventListener("pageshow", (event) => {
    if (event.persisted) window.location.reload();
});
