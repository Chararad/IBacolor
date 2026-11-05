"use strict";

const currentPath = window.location.pathname;

document.querySelectorAll(".site-nav .nav-links a").forEach((link) => {
  const linkPath = new URL(link.href, window.location.href).pathname;
  if (linkPath === currentPath) link.setAttribute("aria-current", "page");
});
