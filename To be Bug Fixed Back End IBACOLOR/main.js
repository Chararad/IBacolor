(function () {
    "use strict";

    /* ------------------------------------------------------------------
       Cookie consent: shown only to new visitors.
       The choice is saved in localStorage so returning visitors never see
       the banner again.
       ------------------------------------------------------------------ */
    var CONSENT_KEY = "ibacolor_cookie_consent";

    function hasConsent() {
        try {
            return window.localStorage.getItem(CONSENT_KEY) === "accepted";
        } catch (error) {
            return false;
        }
    }

    function saveConsent() {
        try {
            window.localStorage.setItem(CONSENT_KEY, "accepted");
        } catch (error) {
            /* Storage unavailable (e.g. private mode): the banner will
               simply reappear on the next visit. */
        }
    }

    function initCookieBanner() {
        var banner = document.getElementById("cookie-banner");
        var acceptButton = document.getElementById("cookie-accept");
        if (!banner || !acceptButton) return;

        if (hasConsent()) {
            banner.hidden = true;
            return;
        }

        banner.hidden = false;
        acceptButton.addEventListener("click", function () {
            saveConsent();
            banner.hidden = true;
        });
    }

    /* ------------------------------------------------------------------
       Profile drop-down: close on outside click or Escape.
       ------------------------------------------------------------------ */
    function initProfileMenu() {
        var menus = document.querySelectorAll("details.profile-menu");
        if (!menus.length) return;

        document.addEventListener("click", function (event) {
            menus.forEach(function (menu) {
                if (menu.open && !menu.contains(event.target)) menu.open = false;
            });
        });

        document.addEventListener("keydown", function (event) {
            if (event.key !== "Escape") return;
            menus.forEach(function (menu) {
                if (!menu.open) return;
                menu.open = false;
                var summary = menu.querySelector("summary");
                if (summary) summary.focus();
            });
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        initCookieBanner();
        initProfileMenu();
    });
})();
