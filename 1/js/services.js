const searchInput = document.getElementById("service-search");
const categorySelect = document.getElementById("service-category");
const serviceList = document.getElementById("service-list");
const emptyState = document.getElementById("service-empty");
const serviceCount = document.getElementById("service-count");

if (searchInput && categorySelect && serviceList && emptyState && serviceCount) {
    const serviceCards = Array.from(serviceList.querySelectorAll(".service-card"));

    function filterServices() {
        const keyword = searchInput.value.trim().toLowerCase();
        const selectedCategory = categorySelect.value;
        let visibleCount = 0;

        serviceCards.forEach((card) => {
            const matchesKeyword = !keyword || card.textContent.toLowerCase().includes(keyword);
            const matchesCategory = !selectedCategory || card.dataset.category === selectedCategory;
            const matches = matchesKeyword && matchesCategory;

            card.hidden = !matches;
            if (matches) visibleCount += 1;
        });

        serviceCount.textContent = `${visibleCount} service${visibleCount === 1 ? "" : "s"} available`;
        emptyState.hidden = visibleCount > 0;
    }

    searchInput.addEventListener("input", filterServices);
    categorySelect.addEventListener("change", filterServices);

    const parameters = new URLSearchParams(window.location.search);
    const query = parameters.get("q");
    const selectedService = parameters.get("service");

    if (query) {
        searchInput.value = query;
    } else if (selectedService) {
        const selectedCard = serviceCards.find((card) => card.dataset.serviceCode === selectedService);
        if (selectedCard) searchInput.value = selectedCard.querySelector("h3").textContent;
    }

    filterServices();
}
