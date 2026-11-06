"use strict";

(() => {
  const endpoint = new URL("../../includes/api.php", window.location.href);
  let csrfToken = "";

  async function request(action, { method = "GET", body, query = {} } = {}) {
    const url = new URL(endpoint);
    url.searchParams.set("action", action);
    Object.entries(query).forEach(([key, value]) => {
      if (value !== undefined && value !== null) url.searchParams.set(key, value);
    });
    const headers = {};
    if (method === "POST") headers["X-CSRF-Token"] = csrfToken;
    if (body && !(body instanceof FormData)) headers["Content-Type"] = "application/json";
    const response = await fetch(url, {
      method,
      headers,
      body: body instanceof FormData ? body : body ? JSON.stringify(body) : undefined,
      credentials: "same-origin",
      cache: "no-store",
    });
    const data = await response.json();
    if (!response.ok) {
      if (response.status === 401) window.location.assign("../login.php");
      throw new Error(data.error || "The request could not be completed.");
    }
    return data;
  }

  const escape = (value) => String(value ?? "").replace(/[&<>"']/g, (char) => ({
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#39;",
  })[char]);
  const statusClass = (value) => String(value || "").toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/^-|-$/g, "");
  const status = (value) => `<span class="status status-${statusClass(value)}">${escape(value)}</span>`;
  const date = (value) => {
    if (!value) return "—";
    const parsed = new Date(String(value).replace(" ", "T") + (String(value).endsWith("Z") ? "" : "Z"));
    return Number.isNaN(parsed.getTime()) ? escape(value) : new Intl.DateTimeFormat(undefined, { dateStyle: "medium" }).format(parsed);
  };
  const message = (element, text, error = false) => {
    if (!element) return;
    let target = element.matches('[role="status"],[role="alert"],.notice,.form-help')
      ? element
      : element.querySelector(".portal-message");
    if (!target) {
      target = document.createElement("p");
      target.className = "portal-message notice";
      if (element.tagName === "TR") {
        target = document.createElement("td");
        target.colSpan = element.cells.length || 1;
        target.className = "portal-message notice";
      }
      target.setAttribute("role", error ? "alert" : "status");
      if (element.tagName === "TR") element.append(target);
      else element.prepend(target);
    }
    target.textContent = text;
    target.hidden = false;
    target.classList.toggle("notice-warning", error);
    target.classList.toggle("notice-info", !error);
  };
  const formData = (form) => Object.fromEntries(new FormData(form).entries());

  function initializeNavigation() {
    const toggle = document.querySelector(".menu-toggle");
    const nav = document.getElementById("main-nav");
    if (toggle && nav) {
      toggle.addEventListener("click", () => {
        const open = document.body.classList.toggle("nav-open");
        toggle.setAttribute("aria-expanded", String(open));
      });
      nav.addEventListener("click", (event) => {
        if (event.target.closest("a")) {
          document.body.classList.remove("nav-open");
          toggle.setAttribute("aria-expanded", "false");
        }
      });
    }
    const currentPath = window.location.pathname;
    document.querySelectorAll(".site-nav .nav-links a").forEach((link) => {
      if (new URL(link.href, location.href).pathname === currentPath) link.setAttribute("aria-current", "page");
    });
  }

  document.addEventListener("click", async (event) => {
    const link = event.target.closest("a");
    if (!link || !/sign out/i.test(link.textContent)) return;
    event.preventDefault();
    try {
      await request("logout", { method: "POST", body: {} });
      location.assign("../logout.php");
    } catch (error) {
      window.alert(error.message);
    }
  });
  document.addEventListener("portal:error", (event) => {
    const notice = document.createElement("p");
    notice.className = "notice notice-warning";
    notice.setAttribute("role", "alert");
    notice.textContent = event.detail || "The database request failed.";
    document.querySelector("main .container")?.prepend(notice);
  });

  initializeNavigation();
  request("csrf")
    .then(({ token }) => {
      csrfToken = token;
      document.dispatchEvent(new CustomEvent("portal:ready"));
    })
    .catch((error) => {
      document.dispatchEvent(new CustomEvent("portal:error", { detail: error.message }));
    });

  window.IBacolorPortal = { request, escape, status, date, message, formData };
})();
