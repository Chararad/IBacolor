const menuToggle = document.querySelector(".menu-toggle");
const mainNavigation = document.getElementById("main-nav");

if (menuToggle && mainNavigation) {
    menuToggle.addEventListener("click", () => {
        const isOpen = document.body.classList.toggle("nav-open");
        menuToggle.setAttribute("aria-expanded", String(isOpen));
    });

    mainNavigation.addEventListener("click", (event) => {
        if (!event.target.closest("a")) return;

        document.body.classList.remove("nav-open");
        menuToggle.setAttribute("aria-expanded", "false");
    });

    document.addEventListener("keydown", (event) => {
        if (event.key !== "Escape" || !document.body.classList.contains("nav-open")) return;

        document.body.classList.remove("nav-open");
        menuToggle.setAttribute("aria-expanded", "false");
        menuToggle.focus();
    });
}

const currentPath = window.location.pathname;

document.querySelectorAll(".site-nav .nav-links a").forEach((link) => {
    const linkPath = new URL(link.href, window.location.href).pathname;
    if (linkPath === currentPath) link.setAttribute("aria-current", "page");
});
