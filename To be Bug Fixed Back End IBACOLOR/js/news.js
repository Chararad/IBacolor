"use strict";

(() => {
  const grid = document.querySelector(".card-grid-news");
  const categoryFilter = document.getElementById("news-category-filter");
  const fromDate = document.getElementById("news-date-from");
  const toDate = document.getElementById("news-date-to");
  const resultCount = document.getElementById("news-result-count");
  const emptyState = document.getElementById("news-empty");
  if (!grid || !categoryFilter || !fromDate || !toDate || !resultCount || !emptyState) return;

  const render = (articles) => {
    const categories = [...new Set(articles.map((article) => article.category))].sort();
    categoryFilter.replaceChildren(new Option("All announcements", ""));
    categories.forEach((category) => categoryFilter.add(new Option(category, category)));
    grid.replaceChildren(...articles.map((article) => {
      const card = document.createElement("article");
      const publishedDate = String(article.published_at || "").slice(0, 10);
      card.className = "card news-card";
      card.dataset.newsCard = "";
      card.dataset.category = article.category;
      card.dataset.date = publishedDate;

      const tag = document.createElement("span");
      tag.className = "tag";
      tag.textContent = article.category;
      const time = document.createElement("time");
      time.className = "meta";
      time.dateTime = publishedDate;
      time.textContent = article.published_at
        ? new Intl.DateTimeFormat(undefined, { dateStyle: "long", timeZone: "UTC" }).format(new Date(`${publishedDate}T00:00:00Z`))
        : "";
      const title = document.createElement("h3");
      title.textContent = article.title;
      const summary = document.createElement("p");
      summary.textContent = article.content.length > 240 ? `${article.content.slice(0, 240).trim()}…` : article.content;
      const details = document.createElement("details");
      details.className = "news-details";
      const disclosure = document.createElement("summary");
      disclosure.textContent = "Read full announcement";
      const content = document.createElement("p");
      content.textContent = article.content;
      details.append(disclosure);
      if (Number(article.has_image) === 1) {
        const image = document.createElement("img");
        image.className = "announcement-image";
        image.src = `../includes/api.php?action=news.image&id=${encodeURIComponent(article.article_id)}`;
        image.alt = `Image for ${article.title}`;
        details.append(image);
      }
      details.append(content);
      card.append(tag, time, title, summary, details);
      return card;
    }));

    const filter = () => {
      let visible = 0;
      grid.querySelectorAll("[data-news-card]").forEach((card) => {
        const matches = (!categoryFilter.value || card.dataset.category === categoryFilter.value)
          && (!fromDate.value || card.dataset.date >= fromDate.value)
          && (!toDate.value || card.dataset.date <= toDate.value);
        card.hidden = !matches;
        if (matches) visible += 1;
      });
      resultCount.textContent = `${visible} announcement${visible === 1 ? "" : "s"} shown`;
      emptyState.hidden = visible > 0;
      if (!articles.length) {
        emptyState.querySelector("strong").textContent = "No announcements have been published.";
        emptyState.querySelector("p").textContent = "Please check back for municipal updates.";
      }
    };
    categoryFilter.addEventListener("change", filter);
    fromDate.addEventListener("change", filter);
    toDate.addEventListener("change", filter);
    filter();
  };

  let articles = [];
  grid.replaceChildren();
  fetch("../includes/api.php?action=news.public", { credentials: "same-origin", cache: "no-store" })
    .then(async (response) => {
      const result = await response.json();
      if (!response.ok) throw new Error(result.error || "News could not be loaded.");
      return result.articles;
    })
    .then((publishedArticles) => {
      articles = publishedArticles;
      render(articles);
    })
    .catch((error) => {
      grid.replaceChildren();
      emptyState.hidden = false;
      emptyState.querySelector("strong").textContent = error.message;
      emptyState.querySelector("p").textContent = "Please try again later.";
      resultCount.textContent = "";
    });
})();
