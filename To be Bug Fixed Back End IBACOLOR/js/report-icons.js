"use strict";

/*
 * Community report category icons.
 * One consistent style: 24x24 line icons, 1.75 stroke, round caps and joins,
 * drawn in currentColor so they follow the surrounding text colour.
 */
(() => {
  const ICONS = {
    // Roads and transportation: road with a dashed centre line
    roads: '<path d="M8 3 5 21"/><path d="M16 3l3 18"/><path d="M12 4v3"/><path d="M12 10.5v3"/><path d="M12 17v3"/>',
    // Garbage disposal: trash can
    garbage: '<path d="M4 7h16"/><path d="M9 7V4.5A1.5 1.5 0 0 1 10.5 3h3A1.5 1.5 0 0 1 15 4.5V7"/><path d="M6 7l1 13a1.5 1.5 0 0 0 1.5 1.4h7A1.5 1.5 0 0 0 17 20l1-13"/><path d="M10 11v6"/><path d="M14 11v6"/>',
    // Drainage and flooding: water drop above waves
    drainage: '<path d="M12 3s4 4.2 4 7.2a4 4 0 0 1-8 0C8 7.2 12 3 12 3z"/><path d="M3 17c1.5 0 1.5-1 3-1s1.5 1 3 1 1.5-1 3-1 1.5 1 3 1 1.5-1 3-1 1.5 1 3 1"/><path d="M3 21c1.5 0 1.5-1 3-1s1.5 1 3 1 1.5-1 3-1 1.5 1 3 1 1.5-1 3-1 1.5 1 3 1"/>',
    // Street lighting: light bulb
    lighting: '<path d="M9 18h6"/><path d="M10 21h4"/><path d="M12 3a6 6 0 0 0-3.5 10.9c.6.5 1 1.2 1 2.1v.5h5V16c0-.9.4-1.6 1-2.1A6 6 0 0 0 12 3z"/>',
    // Public safety: shield with a check mark
    safety: '<path d="M12 3 5 6v5c0 4.5 2.9 8.3 7 10 4.1-1.7 7-5.5 7-10V6l-7-3z"/><path d="m9 12 2 2 4-4"/>',
    // Other: circle with an ellipsis
    other: '<circle cx="12" cy="12" r="9"/><path d="M8 12h.01"/><path d="M12 12h.01"/><path d="M16 12h.01"/>',
  };

  const CATEGORY_TO_ICON = {
    "roads and transportation": "roads",
    "garbage disposal": "garbage",
    "drainage and flooding": "drainage",
    "street lighting": "lighting",
    "public safety": "safety",
  };

  const iconKey = (category) => CATEGORY_TO_ICON[String(category || "").trim().toLowerCase()] || "other";

  // Returns the <svg> markup for a category. Anything unrecognised
  // (including residents' own "Other" text) falls back to the "other" icon.
  const svg = (category) =>
    `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">${ICONS[iconKey(category)]}</svg>`;

  window.IBacolorReportIcons = {
    svg,
    key: iconKey,
    // A ready-made badge: <span class="category-icon category-icon-roads">…</span>
    badge: (category, extraClass = "") =>
      `<span class="category-icon category-icon-${iconKey(category)}${extraClass ? ` ${extraClass}` : ""}">${svg(category)}</span>`,
  };
})();
