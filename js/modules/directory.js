"use strict";

window.IBacolorModules = window.IBacolorModules || {};
(function () {
// Contact directory: renders verified office contacts and barangay contact cards.
function renderDirectory() {
  const offices = document.getElementById("office-directory");
  if (offices) {
    offices.replaceChildren(
      ...window.MOCK_DATA.officeDirectory.map((entry) => {
        const card = document.createElement("article");
        card.className = "card office-card";
        const title = document.createElement("h3");
        title.textContent = entry.office;
        const category = document.createElement("p");
        category.className = "meta";
        category.textContent = entry.category;
        card.append(title, category);
        [
          ["Office contact", entry.official],
          ["Telephone", entry.telephone],
          ["Email", entry.email],
          ["Location", entry.location],
          [
            "Office-specific social media",
            entry.social?.startsWith("https://")
              ? entry.social
              : "No verified office-specific social media page",
          ],
          ["Municipal Facebook", window.MOCK_DATA.municipality.facebook],
          [
            "Municipal website",
            entry.website?.startsWith("https://")
              ? entry.website
              : window.MOCK_DATA.municipality.website,
          ],
        ].forEach(([label, value]) => {
          const paragraph = document.createElement("p");
          const strong = document.createElement("strong");
          strong.textContent = `${label}: `;
          const normalized = value || "Contact details to be confirmed";
          const link = document.createElement("a");
          if (label === "Email" && normalized.includes("@")) {
            link.href = `mailto:${normalized}`;
            link.textContent = normalized;
            paragraph.append(strong, link);
          } else if (
            label === "Telephone" &&
            /^[+()\d\s-]+$/.test(normalized)
          ) {
            link.href = `tel:${normalized.replace(/[^\d+]/g, "")}`;
            link.textContent = normalized;
            paragraph.append(strong, link);
          } else if (
            [
              "Office-specific social media",
              "Municipal Facebook",
              "Municipal website",
            ].includes(label) &&
            normalized.startsWith("https://")
          ) {
            link.href = normalized;
            link.target = "_blank";
            link.rel = "noreferrer";
            link.textContent = "Open official page";
            paragraph.append(strong, link);
          } else {
            paragraph.append(strong, document.createTextNode(normalized));
          }
          card.append(paragraph);
        });
        const services = document.createElement("p");
        services.textContent = `Services: ${entry.services.join(", ")}`;
        card.append(services);
        return card;
      }),
    );
  }

  const barangayList = document.getElementById("barangay-contacts");
  if (barangayList) {
    barangayList.replaceChildren(
      ...window.MOCK_DATA.barangays.map((name) => {
        const card = document.createElement("article");
        card.className = "card barangay-contact-card";
        const title = document.createElement("h3");
        title.textContent = `Barangay ${name}`;
        const contact = document.createElement("p");
        contact.textContent =
          "Contact the barangay hall for local assistance. Phone and social media details are awaiting official confirmation.";
        card.append(title, contact);
        return card;
      }),
    );
  }
}

window.IBacolorModules.directory = { renderDirectory };
})();
