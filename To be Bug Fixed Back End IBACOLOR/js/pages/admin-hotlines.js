"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const tbody = document.querySelector("table tbody");
  const addButton = document.getElementById("add-hotline-button");
  const searchInput = document.getElementById("hotline-search");
  const noMatchesRow = document.getElementById("hotline-search-empty");
  const modal = document.getElementById("hotline-editor-modal");
  const form = document.getElementById("hotline-editor-form");
  const title = document.getElementById("hotline-editor-title");
  const errorMessage = document.getElementById("hotline-editor-error");
  if (!tbody || !addButton || !searchInput || !noMatchesRow || !modal || !form || !title || !errorMessage) return;

  let editorTrigger = null;
  const closeEditor = () => {
    modal.classList.remove("is-open");
    modal.setAttribute("aria-hidden", "true");
    form.reset();
    errorMessage.hidden = true;
    errorMessage.textContent = "";
    if (editorTrigger?.isConnected) editorTrigger.focus();
    editorTrigger = null;
  };

  const openEditor = (item = {}, trigger = addButton) => {
    editorTrigger = trigger;
    form.reset();
    form.elements.contactId.value = item.contact_id || "";
    form.elements.name.value = item.name || "";
    form.elements.type.value = item.type || "hotline";
    form.elements.telephone.value = item.telephone || "";
    form.elements.email.value = item.email || "";
    form.elements.address.value = item.address || "";
    form.elements.category.value = item.category || "";
    form.elements.contact_name.value = item.contact_name || "";
    form.elements.purpose.value = item.purpose || "";
    form.elements.social_link.value = item.social_link || "";
    form.elements.fb_page_link.value = item.fb_page_link || "";
    form.elements.website_link.value = item.website_link || "";
    form.elements.official_socmed.value = item.official_socmed || "";
    form.elements.is_verified.checked = ["1", "true", "yes", "verified"].includes(
      String(item.is_verified).toLowerCase(),
    );
    title.textContent = item.contact_id ? "Edit hotline" : "Add hotline";
    errorMessage.hidden = true;
    errorMessage.textContent = "";
    modal.classList.add("is-open");
    modal.setAttribute("aria-hidden", "false");
    form.elements.name.focus();
  };

  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    errorMessage.hidden = true;
    errorMessage.textContent = "";
    const body = Object.fromEntries(new FormData(form).entries());
    body.is_verified = form.elements.is_verified.checked;
    body.type = String(body.type).toLowerCase();
    try {
      await portal.request("admin.save-contact", { method: "POST", body });
      location.reload();
    } catch (error) {
      errorMessage.textContent = error.message;
      errorMessage.hidden = false;
    }
  });

  modal.querySelectorAll("[data-close-hotline-editor]").forEach((button) => {
    button.addEventListener("click", closeEditor);
  });
  modal.addEventListener("click", (event) => {
    if (event.target === modal) closeEditor();
  });
  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && modal.classList.contains("is-open")) closeEditor();
  });
  addButton.addEventListener("click", () => openEditor());

  let contactRows = [];
  const filterContacts = () => {
    const query = searchInput.value.trim().toLocaleLowerCase();
    let visibleCount = 0;
    contactRows.forEach((row) => {
      const matches = query === "" || row.dataset.search.includes(query);
      row.hidden = !matches;
      if (matches) visibleCount += 1;
    });
    noMatchesRow.hidden = query === "" || visibleCount > 0;
  };
  searchInput.addEventListener("input", filterContacts);

  try {
    const { contacts } = await portal.request("admin.contacts");
    contactRows = contacts.map((contact) => {
      const row = document.createElement("tr");
      row.innerHTML = `<td><strong>${portal.escape(contact.name)}</strong><br><small>${portal.escape(contact.type)}</small></td>
        <td>${portal.escape(contact.telephone || contact.email || "—")}</td>
        <td>${portal.escape(contact.purpose || contact.address || "")}</td>
        <td class="table-actions"><button class="button button-small button-outline hotline-edit-button" type="button">Edit</button>
        <button class="button button-small button-outline hotline-delete-button" type="button">Delete</button></td>`;
      row.dataset.search = [
        contact.name, contact.type, contact.telephone, contact.email, contact.address,
        contact.contact_name, contact.purpose, contact.category, contact.social_link,
        contact.fb_page_link, contact.website_link, contact.official_socmed,
      ].filter(Boolean).join(" ").toLocaleLowerCase();
      row.querySelector(".hotline-edit-button").addEventListener("click", (event) => openEditor(contact, event.currentTarget));
      row.querySelector(".hotline-delete-button").addEventListener("click", async () => {
        if (!window.confirm(`Delete "${contact.name}"?`)) return;
        try {
          await portal.request("admin.delete-contact", { method: "POST", body: { contactId: contact.contact_id } });
          location.reload();
        } catch (error) {
          portal.message(row, error.message, true);
        }
      });
      return row;
    });
    tbody.replaceChildren(...contactRows, noMatchesRow);
    filterContacts();
  } catch (error) {
    portal.message(document.querySelector(".card"), error.message, true);
  }
});
