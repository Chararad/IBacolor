"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const tbody = document.querySelector("table tbody");
  const addButton = document.querySelector(".section-heading button");
  const edit = async (item = {}) => {
    const name = window.prompt("Office or hotline name", item.name || "");
    if (name === null) return;
    const type = window.prompt("Contact type: office, emergency, or hotline", item.type || "hotline");
    if (type === null) return;
    const telephone = window.prompt("Telephone number(s)", item.telephone || "");
    if (telephone === null) return;
    const email = window.prompt("Email (optional)", item.email || "");
    if (email === null) return;
    const address = window.prompt("Address or service description", item.address || "");
    if (address === null) return;
    const category = window.prompt("Directory category (optional)", item.category || "");
    if (category === null) return;
    const contact_name = window.prompt("Official contact person (optional)", item.contact_name || "");
    if (contact_name === null) return;
    const purpose = window.prompt("Services or purposes (separate with semicolons)", item.purpose || "");
    if (purpose === null) return;
    const social_link = window.prompt("Other HTTPS social link (optional)", item.social_link || "");
    if (social_link === null) return;
    const fb_page_link = window.prompt("Facebook page HTTPS URL (optional)", item.fb_page_link || "");
    if (fb_page_link === null) return;
    const website_link = window.prompt("Website HTTPS URL (optional)", item.website_link || "");
    if (website_link === null) return;
    const official_socmed = window.prompt("Official social media HTTPS URL (optional)", item.official_socmed || "");
    if (official_socmed === null) return;
    const is_verified = window.confirm("Have these contact details been officially verified?");
    await portal.request("admin.save-contact", {
      method: "POST",
      body: { contactId: item.contact_id, name, type: type.toLowerCase(), telephone, email, address, category, contact_name, purpose, social_link, fb_page_link, website_link, official_socmed, is_verified },
    });
    location.reload();
  };
  try {
    const { contacts } = await portal.request("admin.contacts");
    tbody.replaceChildren(...contacts.map((contact) => {
      const row = document.createElement("tr");
      row.innerHTML = `<td><strong>${portal.escape(contact.name)}</strong><br><small>${portal.escape(contact.type)}</small></td>
        <td>${portal.escape(contact.telephone || contact.email || "—")}</td>
        <td>${portal.escape(contact.purpose || contact.address || "")}</td>
        <td class="table-actions"><button class="button button-small button-outline" type="button">Edit</button>
        <button class="button button-small button-ghost" type="button">Delete</button></td>`;
      row.querySelector(".button-outline").addEventListener("click", () => edit(contact).catch((error) => portal.message(row, error.message, true)));
      row.querySelector(".button-ghost").addEventListener("click", async () => {
        if (!window.confirm(`Delete "${contact.name}"?`)) return;
        try {
          await portal.request("admin.delete-contact", { method: "POST", body: { contactId: contact.contact_id } });
          location.reload();
        } catch (error) {
          portal.message(row, error.message, true);
        }
      });
      return row;
    }));
    addButton.addEventListener("click", () => edit().catch((error) => portal.message(document.querySelector(".card"), error.message, true)));
  } catch (error) {
    portal.message(document.querySelector(".card"), error.message, true);
  }
});
