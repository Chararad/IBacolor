"use strict";

(() => {
  const grid = document.querySelector(".card-grid-news");
  const categoryFilter = document.getElementById("news-category-filter");
  const fromDate = document.getElementById("news-date-from");
  const toDate = document.getElementById("news-date-to");
  const resultCount = document.getElementById("news-result-count");
  const emptyState = document.getElementById("news-empty");
  if (!grid || !categoryFilter || !fromDate || !toDate || !resultCount || !emptyState) return;

  const ANNOUNCEMENT_TYPES = [
    "Legal and Regulatory Notices",
    "Government and Administrative Announcements",
    "Public Safety and Emergency Advisories",
    "Community and Cultural Events",
  ];

  const dialog = document.createElement("dialog");
  dialog.className = "news-dialog";
  dialog.setAttribute("aria-labelledby", "news-dialog-title");
  dialog.innerHTML = `<button class="news-dialog-close" type="button" aria-label="Close">×</button>
    <div class="news-dialog-body">
      <span class="tag" data-dialog-tag></span>
      <time class="meta" data-dialog-date></time>
      <h2 id="news-dialog-title" data-dialog-title></h2>
      <img class="announcement-image" data-dialog-image alt="" hidden />
      <div class="news-dialog-content" data-dialog-content></div>
    </div>`;
  document.body.append(dialog);
  let lastTrigger = null;
  const openArticle = (article, trigger) => {
    lastTrigger = trigger;
    const publishedDate = String(article.published_at || "").slice(0, 10);
    dialog.querySelector("[data-dialog-tag]").textContent = article.category;
    const time = dialog.querySelector("[data-dialog-date]");
    time.textContent = publishedDate
      ? new Intl.DateTimeFormat(undefined, { dateStyle: "long", timeZone: "UTC" }).format(new Date(`${publishedDate}T00:00:00Z`))
      : "";
    time.dateTime = publishedDate;
    dialog.querySelector("[data-dialog-title]").textContent = article.title;
    const image = dialog.querySelector("[data-dialog-image]");
    if (Number(article.has_image) === 1) {
      image.src = `../includes/api.php?action=news.image&id=${encodeURIComponent(article.article_id)}`;
      image.alt = `Image for ${article.title}`;
      image.hidden = false;
    } else {
      image.removeAttribute("src");
      image.hidden = true;
    }
    dialog.querySelector("[data-dialog-content]").textContent = article.content;
    dialog.querySelector(".news-dialog-body").scrollTop = 0;
    if (typeof dialog.showModal === "function") dialog.showModal();
    else dialog.setAttribute("open", "");
    document.body.classList.add("news-dialog-open");
  };
  dialog.querySelector(".news-dialog-close").addEventListener("click", () => dialog.close());
  dialog.addEventListener("click", (event) => { if (event.target === dialog) dialog.close(); });
  dialog.addEventListener("close", () => {
    document.body.classList.remove("news-dialog-open");
    lastTrigger?.focus();
  });

  const render = (articles) => {
    const extraCategories = [...new Set(articles.map((article) => article.category))]
      .filter((category) => !ANNOUNCEMENT_TYPES.includes(category)).sort();
    categoryFilter.replaceChildren(new Option("All announcements", ""));
    [...ANNOUNCEMENT_TYPES, ...extraCategories].forEach((category) => categoryFilter.add(new Option(category, category)));
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
      const readMore = document.createElement("button");
      readMore.type = "button";
      readMore.className = "button button-outline news-read-more";
      readMore.textContent = "Read full announcement";
      card.classList.add("news-card-clickable");
      card.addEventListener("click", () => openArticle(article, readMore));
      card.append(tag, time, title, summary, readMore);
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
