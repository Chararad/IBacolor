"use strict";

window.IBacolorModules = window.IBacolorModules || {};
(function () {
// Service directory: builds service cards and filters them by search and category.
function initializeServiceDirectory() {
  const search = document.getElementById("service-search");
  const category = document.getElementById("service-category");
  const list = document.getElementById("service-list");
  const empty = document.getElementById("service-empty");
  const count = document.getElementById("service-count");
  if (!search || !category || !list || !empty || !count) return;

  const categories = [
    ...new Set(window.MOCK_DATA.services.map((service) => service.category)),
  ].sort();
  category.replaceChildren(new Option("All categories", "All categories"));
  categories.forEach((name) => category.add(new Option(name, name)));

  list.replaceChildren(
    ...window.MOCK_DATA.services.map((service) => {
      const card = document.createElement("article");
      card.className = "card service-card";
      card.dataset.serviceId = service.id;

      const tag = document.createElement("span");
      tag.className = "tag";
      tag.textContent = service.category;
      const title = document.createElement("h3");
      title.textContent = service.name;
      const description = document.createElement("p");
      description.textContent = service.description;
      const office = document.createElement("p");
      office.className = "service-office";
      const officeLabel = document.createElement("span");
      officeLabel.textContent = "Responsible office";
      const officeName = document.createElement("strong");
      officeName.textContent = service.office;
      office.append(officeLabel, officeName);

      const details = document.createElement("details");
      details.className = "service-details";
      const summary = document.createElement("summary");
      summary.textContent = "View requirements and how to apply";
      const requirements = document.createElement("ul");
      requirements.className = "compact-list";
      service.requirements.forEach((requirement) => {
        const item = document.createElement("li");
        item.textContent = requirement;
        requirements.append(item);
      });
      const fees = document.createElement("p");
      fees.textContent = `Fees: ${service.fees}`;
      const processing = document.createElement("p");
      processing.textContent = `Processing: ${service.processing}`;
      const instructions = document.createElement("p");
      instructions.textContent = `How to apply: ${service.instructions}`;
      details.append(summary, requirements, fees, processing, instructions);

      const start = document.createElement("a");
      start.className = "button button-outline";
      start.href = `resident/request-form.php?service=${encodeURIComponent(service.id)}`;
      start.textContent = "Start request";
      card.append(tag, title, description, office, details, start);
      return card;
    }),
  );

  const filterServices = () => {
    const keyword = search.value.trim().toLowerCase();
    const selectedCategory = category.value;
    let visibleCount = 0;

    list.querySelectorAll(".service-card").forEach((card, index) => {
      const service = window.MOCK_DATA.services[index];
      const searchText = [
        service.name,
        service.description,
        service.category,
        service.office,
        ...service.requirements,
      ]
        .join(" ")
        .toLowerCase();
      const matches =
        (!keyword || searchText.includes(keyword)) &&
        (selectedCategory === "All categories" ||
          service.category === selectedCategory);
      card.hidden = !matches;
      if (matches) visibleCount += 1;
    });

    count.textContent = `${visibleCount} service${visibleCount === 1 ? "" : "s"} available`;
    empty.hidden = visibleCount !== 0;
  };

  search.addEventListener("input", filterServices);
  category.addEventListener("change", filterServices);
  const parameters = new URLSearchParams(window.location.search);
  const query = parameters.get("q");
  const selectedService = parameters.get("service");
  if (query) search.value = query;
  if (selectedService) {
    const serviceIndex = window.MOCK_DATA.services.findIndex(
      (service) => service.id === selectedService,
    );
    if (serviceIndex >= 0)
      search.value = window.MOCK_DATA.services[serviceIndex].name;
  }
  filterServices();
}

window.IBacolorModules.services = { initializeServiceDirectory };
})();
