"use strict";

(() => {
  const notice = document.createElement("p");
  notice.className = "notice notice-info";
  notice.setAttribute("role", "status");
  notice.textContent = "News publishing is not available here because the relational schema does not include news content; the separate publishing workflow has not been configured.";
  document.querySelector("main .container")?.prepend(notice);
  document.querySelectorAll("main button, main input, main textarea, main select").forEach((control) => {
    control.disabled = true;
  });
})();
