const newsCards = Array.from(document.querySelectorAll("[data-news-card]"));
const categoryFilter = document.getElementById("news-category-filter");
const fromDate = document.getElementById("news-date-from");
const toDate = document.getElementById("news-date-to");
const resultCount = document.getElementById("news-result-count");
const emptyState = document.getElementById("news-empty");

if (newsCards.length && categoryFilter && fromDate && toDate && resultCount && emptyState) {
    const categories = [...new Set(newsCards.map((card) => card.dataset.category))].sort();
    categories.forEach((category) => categoryFilter.add(new Option(category, category)));

    function filterNews() {
        let visibleCount = 0;

        newsCards.forEach((card) => {
            const matchesCategory = !categoryFilter.value || card.dataset.category === categoryFilter.value;
            const matchesStartDate = !fromDate.value || card.dataset.date >= fromDate.value;
            const matchesEndDate = !toDate.value || card.dataset.date <= toDate.value;
            const isVisible = matchesCategory && matchesStartDate && matchesEndDate;

            card.hidden = !isVisible;
            if (isVisible) visibleCount += 1;
        });

        resultCount.textContent = `${visibleCount} announcement${visibleCount === 1 ? "" : "s"} shown`;
        emptyState.hidden = visibleCount > 0;
    }

    categoryFilter.addEventListener("change", filterNews);
    fromDate.addEventListener("change", filterNews);
    toDate.addEventListener("change", filterNews);
    filterNews();
}
