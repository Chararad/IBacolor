"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const form = document.getElementById("service-form");
  const modal = document.getElementById("service-editor-modal");
  const tbody = document.getElementById("admin-services-list");
  const addButton = document.getElementById("add-service");
  const categoryFilter = document.getElementById("admin-services-category");
  const availabilityFilter = document.getElementById("admin-services-availability");
  const categorySelect = document.getElementById("service-category");
  const newCategoryField = document.getElementById("new-service-category-field");
  const newCategoryInput = document.getElementById("new-service-category");
  const requirementList = document.getElementById("service-requirement-list");
  const addRequirementButton = document.getElementById("add-service-requirement");
  const message = document.getElementById("service-form-message");
  if (!form || !modal || !tbody || !addButton || !categoryFilter || !availabilityFilter || !categorySelect || !newCategoryField
    || !newCategoryInput || !requirementList || !addRequirementButton || !message) return;

  const newCategoryValue = "__add_category__";
  let services = [];
  let formTrigger = null;
  let codeManuallyEdited = false;

  const setCategoryOptions = (selectedCategory = "") => {
    const categories = [...new Set(services.map((service) => service.category).filter(Boolean))];
    if (selectedCategory && !categories.includes(selectedCategory)) categories.push(selectedCategory);
    categories.sort((left, right) => left.localeCompare(right));
    categorySelect.replaceChildren(
      new Option("Select a category", ""),
      ...categories.map((category) => new Option(category, category)),
      new Option("Add a new category…", newCategoryValue)
    );
    categorySelect.value = selectedCategory;
  };

  const updateNewCategoryField = () => {
    const addingCategory = categorySelect.value === newCategoryValue;
    newCategoryField.hidden = !addingCategory;
    newCategoryInput.required = addingCategory;
    if (!addingCategory) newCategoryInput.value = "";
    if (addingCategory) newCategoryInput.focus();
  };

  const showEmptyRequirements = () => {
    const emptyState = document.createElement("p");
    emptyState.className = "service-requirements-empty";
    emptyState.textContent = "No requirements added.";
    requirementList.append(emptyState);
  };

  const addRequirement = (value = "") => {
    requirementList.querySelector(".service-requirements-empty")?.remove();
    const row = document.createElement("div");
    row.className = "service-requirement-row";

    const input = document.createElement("input");
    input.type = "text";
    input.maxLength = 255;
    input.value = value;
    input.setAttribute("aria-label", `Requirement ${requirementList.querySelectorAll("input").length + 1}`);

    const removeButton = document.createElement("button");
    removeButton.className = "button button-outline button-small";
    removeButton.type = "button";
    removeButton.textContent = "Remove";
    removeButton.addEventListener("click", () => {
      row.remove();
      if (!requirementList.querySelector(".service-requirement-row")) showEmptyRequirements();
    });

    row.append(input, removeButton);
    requirementList.append(row);
    input.focus();
  };

  const setRequirements = (requirements = []) => {
    requirementList.replaceChildren();
    if (!requirements.length) {
      showEmptyRequirements();
      return;
    }
    requirements.forEach((requirement) => addRequirement(requirement));
  };

  const closeEditor = (restoreFocus = true) => {
    modal.classList.remove("is-open");
    modal.setAttribute("aria-hidden", "true");
    form.reset();
    newCategoryField.hidden = true;
    newCategoryInput.required = false;
    setRequirements();
    message.hidden = true;
    message.textContent = "";
    if (restoreFocus) (formTrigger?.isConnected ? formTrigger : addButton).focus();
    formTrigger = null;
    codeManuallyEdited = false;
  };

  const openEditor = (service = null, trigger = addButton) => {
    formTrigger = trigger;
    form.reset();
    form.elements.serviceId.value = service?.service_id || "";
    form.elements.name.value = service?.name || "";
    form.elements.code.value = service?.code || "";
    form.elements.office.value = service?.office || "";
    form.elements.status.value = service?.status || "Available";
    form.elements.description.value = service?.description || "";
    form.elements.fee_information.value = service?.fee_information || "";
    form.elements.processing_info.value = service?.processing_info || "";
    form.elements.instructions.value = service?.instructions || "";
    form.querySelector(".service-optional-details").open = Boolean(service
      && (service.fee_information || service.processing_info || service.instructions));
    setCategoryOptions(service?.category || "");
    newCategoryField.hidden = true;
    newCategoryInput.required = false;
    newCategoryInput.value = "";
    setRequirements(service?.requirements || []);
    document.getElementById("service-form-heading").textContent = service ? "Edit service" : "Add service";
    message.hidden = true;
    message.textContent = "";
    codeManuallyEdited = Boolean(service);
    modal.classList.add("is-open");
    modal.setAttribute("aria-hidden", "false");
    form.elements.name.focus();
  };

  const render = () => {
    const selectedCategory = categoryFilter.value;
    const selectedAvailability = availabilityFilter.value;
    const categories = [...new Set(services.map((service) => service.category).filter(Boolean))]
      .sort((left, right) => left.localeCompare(right));
    const standardAvailabilityOptions = ["Available", "Unavailable", "In review"];
    const otherAvailabilityOptions = [...new Set(services.map((service) => service.status).filter(Boolean))]
      .filter((status) => !standardAvailabilityOptions.includes(status))
      .sort((left, right) => left.localeCompare(right));
    const availabilityOptions = [...standardAvailabilityOptions, ...otherAvailabilityOptions];
    categoryFilter.replaceChildren(
      new Option("All categories", ""),
      ...categories.map((category) => new Option(category, category))
    );
    availabilityFilter.replaceChildren(
      new Option("All availability", ""),
      ...availabilityOptions.map((status) => new Option(status, status))
    );
    categoryFilter.value = categories.includes(selectedCategory) ? selectedCategory : "";
    availabilityFilter.value = availabilityOptions.includes(selectedAvailability) ? selectedAvailability : "";

    if (!services.length) {
      tbody.innerHTML = '<tr><td colspan="4">No services yet.</td></tr>';
      return;
    }
    const visibleServices = categoryFilter.value
      ? services.filter((service) => service.category === categoryFilter.value)
      : services;
    const filteredServices = availabilityFilter.value
      ? visibleServices.filter((service) => service.status === availabilityFilter.value)
      : visibleServices;
    if (!filteredServices.length) {
      tbody.innerHTML = '<tr><td colspan="4">No services match these filters.</td></tr>';
      return;
    }
    tbody.replaceChildren(...filteredServices.map((service) => {
      const row = document.createElement("tr");
      const isAvailable = ["available", "active", "published"].includes(String(service.status).toLowerCase());
      const nextStatus = isAvailable ? "Unavailable" : "Available";
      const statusButtonLabel = isAvailable ? "Disable" : "Enable";
      row.innerHTML = `<td><strong>${portal.escape(service.name)}</strong></td>
        <td>${portal.escape(service.category)}</td><td>${portal.status(service.status)}</td>
        <td class="table-actions"><button class="button button-small button-outline" type="button">Edit</button>
        <button class="button button-small button-outline" type="button">${statusButtonLabel}</button></td>`;
      const [editButton, statusButton] = row.querySelectorAll("button");
      editButton.addEventListener("click", () => openEditor(service, editButton));
      statusButton.addEventListener("click", async () => {
        const confirmation = isAvailable
          ? `Set "${service.name}" to unavailable? Existing requests will remain intact.`
          : `Make "${service.name}" available? Residents will be able to submit new requests.`;
        if (!window.confirm(confirmation)) return;
        statusButton.disabled = true;
        try {
          await portal.request("admin.set-service-status", {
            method: "POST",
            body: { serviceId: service.service_id, status: nextStatus },
          });
          await loadServices();
        } catch (error) {
          portal.message(row, error.message, true);
          statusButton.disabled = false;
        }
      });
      return row;
    }));
  };

  const loadServices = async () => {
    const result = await portal.request("admin.services");
    services = result.services;
    render();
  };

  modal.querySelectorAll("[data-close-service-editor]").forEach((button) => {
    button.addEventListener("click", () => closeEditor());
  });
  modal.addEventListener("click", (event) => {
    if (event.target === modal) closeEditor();
  });
  modal.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      event.preventDefault();
      closeEditor();
      return;
    }
    if (event.key !== "Tab") return;
    const focusable = Array.from(form.querySelectorAll(
      'button:not([disabled]), input:not([type="hidden"]):not([disabled]), select:not([disabled]), textarea:not([disabled])'
    )).filter((element) => !element.closest("[hidden]"));
    const first = focusable[0];
    const last = focusable[focusable.length - 1];
    if (event.shiftKey && document.activeElement === first) {
      event.preventDefault();
      last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
      event.preventDefault();
      first.focus();
    }
  });

  categorySelect.addEventListener("change", () => {
    updateNewCategoryField();
    message.hidden = true;
  });
  form.elements.name.addEventListener("input", () => {
    if (codeManuallyEdited) return;
    form.elements.code.value = form.elements.name.value
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, "-")
      .replace(/^-|-$/g, "");
  });
  form.elements.code.addEventListener("input", () => {
    codeManuallyEdited = true;
  });
  addRequirementButton.addEventListener("click", () => addRequirement());
  addButton.addEventListener("click", () => openEditor());
  categoryFilter.addEventListener("change", render);
  availabilityFilter.addEventListener("change", render);

  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    if (!form.reportValidity()) return;

    const isNewCategory = categorySelect.value === newCategoryValue;
    const category = isNewCategory ? newCategoryInput.value.trim() : categorySelect.value;
    if (isNewCategory && services.some((service) => String(service.category || "").trim().toLocaleLowerCase() === category.toLocaleLowerCase())) {
      portal.message(message, "That category already exists. Select it from the category list.", true);
      newCategoryInput.focus();
      return;
    }

    const saveButton = form.querySelector('[type="submit"]');
    const payload = {
      serviceId: form.elements.serviceId.value,
      name: form.elements.name.value.trim(),
      code: form.elements.code.value.trim(),
      category,
      office: form.elements.office.value.trim(),
      status: form.elements.status.value,
      description: form.elements.description.value.trim(),
      fee_information: form.elements.fee_information.value.trim(),
      processing_info: form.elements.processing_info.value.trim(),
      instructions: form.elements.instructions.value.trim(),
      requirements: Array.from(requirementList.querySelectorAll("input"))
        .map((input) => input.value.trim())
        .filter(Boolean),
    };

    saveButton.disabled = true;
    message.hidden = true;
    try {
      await portal.request("admin.save-service", { method: "POST", body: payload });
      closeEditor(false);
      addButton.focus();
      try {
        await loadServices();
      } catch (error) {
        portal.message(document.querySelector(".card"), `Service saved, but the list could not be refreshed: ${error.message}`, true);
      }
    } catch (error) {
      portal.message(message, error.message, true);
    } finally {
      saveButton.disabled = false;
    }
  });

  tbody.innerHTML = '<tr><td colspan="4">Loading services...</td></tr>';
  try {
    await loadServices();
  } catch (error) {
    tbody.innerHTML = '<tr><td colspan="4">Services could not be loaded.</td></tr>';
    portal.message(document.querySelector(".card"), error.message, true);
  }
});
