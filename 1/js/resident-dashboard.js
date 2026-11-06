const residentMenuToggle = document.querySelector(".menu-toggle");
const residentNavigation = document.getElementById("main-nav");

if (residentMenuToggle && residentNavigation) {
    residentMenuToggle.addEventListener("click", () => {
        const isOpen = document.body.classList.toggle("nav-open");
        residentMenuToggle.setAttribute("aria-expanded", String(isOpen));
    });

    residentNavigation.addEventListener("click", (event) => {
        if (!event.target.closest("a")) return;

        document.body.classList.remove("nav-open");
        residentMenuToggle.setAttribute("aria-expanded", "false");
    });

    document.addEventListener("keydown", (event) => {
        if (event.key !== "Escape" || !document.body.classList.contains("nav-open")) return;

        document.body.classList.remove("nav-open");
        residentMenuToggle.setAttribute("aria-expanded", "false");
        residentMenuToggle.focus();
    });
}

const residentCurrentPath = window.location.pathname;

document.querySelectorAll(".site-nav .nav-links a").forEach((link) => {
    if (new URL(link.href, window.location.href).pathname === residentCurrentPath) {
        link.setAttribute("aria-current", "page");
    }
});

window.addEventListener("pageshow", (event) => {
    if (event.persisted) window.location.reload();
});
