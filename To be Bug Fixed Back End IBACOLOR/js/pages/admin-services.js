"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const table = document.querySelector("table");
  const tbody = table.querySelector("tbody");
  const addButton = document.querySelector(".section-heading button");
  let services = [];
  const edit = async (item = {}) => {
    const name = window.prompt("Service name", item.name || "");
    if (name === null) return;
    const code = window.prompt("Unique service code", item.code || name.toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/^-|-$/g, ""));
    if (code === null) return;
    const category = window.prompt("Category", item.category || "");
    if (category === null) return;
    const office = window.prompt("Responsible office", item.office || "");
    if (office === null) return;
    const description = window.prompt("Public description", item.description || "");
    if (description === null) return;
    const status = window.prompt("Status: Available, Unavailable, or In review", item.status || "Available");
    if (status === null) return;
    const fee_information = window.prompt("Fee information (optional)", item.fee_information || "");
    if (fee_information === null) return;
    const processing_info = window.prompt("Processing information (optional)", item.processing_info || "");
    if (processing_info === null) return;
    const instructions = window.prompt("Instructions (optional)", item.instructions || "");
    if (instructions === null) return;
    const requirementText = window.prompt("Required documents or prerequisites (one per line)", (item.requirements || []).join("\n"));
    if (requirementText === null) return;
    await portal.request("admin.save-service", {
      method: "POST",
      body: {
        serviceId: item.service_id,
        name,
        code,
        category,
        office,
        description,
        status,
        fee_information,
        processing_info,
        instructions,
        requirements: requirementText.split(/\r?\n/).map((value) => value.trim()).filter(Boolean),
      },
    });
    location.reload();
  };
  try {
    ({ services } = await portal.request("admin.services"));
    tbody.replaceChildren(...services.map((service) => {
      const row = document.createElement("tr");
      row.innerHTML = `<td><strong>${portal.escape(service.name)}</strong></td>
        <td>${portal.escape(service.category)}</td><td>${portal.status(service.status)}</td>
        <td class="table-actions"><button class="button button-small button-outline" type="button">Edit</button>
        <button class="button button-small button-ghost" type="button">Disable</button></td>`;
      row.querySelector(".button-outline").addEventListener("click", () => edit(service).catch((error) => portal.message(row, error.message, true)));
      row.querySelector(".button-ghost").addEventListener("click", async () => {
        if (!window.confirm(`Set "${service.name}" to unavailable? Existing requests will remain intact.`)) return;
        try {
          await portal.request("admin.archive-service", { method: "POST", body: { serviceId: service.service_id } });
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
