"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const form = document.getElementById("admin-news-form");
  const modal = document.getElementById("news-editor-modal");
  const tbody = document.getElementById("admin-news-list");
  const categoryFilter = document.getElementById("admin-news-category");
  const preview = document.getElementById("news-image-preview");
  const addButton = document.getElementById("add-news-article");
  const message = document.getElementById("news-form-message");
  if (!form || !modal || !tbody || !categoryFilter || !preview || !addButton || !message) return;

  const defaultCategoryOptions = Array.from(categoryFilter.options).slice(1).map((option) => ({
    label: option.textContent,
    value: option.value
  }));
  let articles = [];
  let previewUrl = "";
  let formTrigger = null;

  const showPreview = (url) => {
    if (previewUrl.startsWith("blob:")) URL.revokeObjectURL(previewUrl);
    previewUrl = url || "";
    preview.src = previewUrl;
    preview.hidden = !previewUrl;
  };
  const closeForm = (restoreFocus = true) => {
    modal.classList.remove("is-open");
    modal.setAttribute("aria-hidden", "true");
    form.reset();
    form.elements.articleId.value = "";
    message.hidden = true;
    showPreview("");
    if (restoreFocus) (formTrigger?.isConnected ? formTrigger : addButton).focus();
    formTrigger = null;
  };
  const editArticle = (article = null, trigger = addButton) => {
    formTrigger = trigger;
    form.reset();
    form.elements.articleId.value = article?.article_id || "";
    form.elements.title.value = article?.title || "";
    const categorySelect = form.elements.category;
    categorySelect.querySelectorAll("option[data-legacy]").forEach((option) => option.remove());
    if (article?.category && !Array.from(categorySelect.options).some((option) => option.value === article.category)) {
      const legacy = new Option(`${article.category} (legacy category)`, article.category);
      legacy.dataset.legacy = "";
      categorySelect.add(legacy, 1);
    }
    categorySelect.value = Array.from(categorySelect.options).some((option) => option.value === article?.category) ? article.category : "";
    form.elements.content.value = article?.content || "";
    form.elements.status.value = article?.status || "draft";
    if (article?.published_at) {
      const date = new Date(`${article.published_at.replace(" ", "T")}Z`);
      form.elements.publishedAt.value = new Date(date.getTime() - date.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
    } else {
      form.elements.publishedAt.value = "";
    }
    document.getElementById("news-form-heading").textContent = article ? "Edit news article" : "Add news article";
    message.hidden = true;
    showPreview(article?.has_image
      ? `../../includes/api.php?action=news.image&id=${encodeURIComponent(article.article_id)}`
      : "");
    modal.classList.add("is-open");
    modal.setAttribute("aria-hidden", "false");
    form.elements.title.focus();
  };

  modal.querySelectorAll("[data-close-news-editor]").forEach((button) => {
    button.addEventListener("click", () => closeForm());
  });
  modal.addEventListener("click", (event) => {
    if (event.target === modal) closeForm();
  });
  modal.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      event.preventDefault();
      closeForm();
      return;
    }
    if (event.key !== "Tab") return;
    const focusable = Array.from(form.querySelectorAll(
      'button:not([disabled]), input:not([type="hidden"]):not([disabled]), select:not([disabled]), textarea:not([disabled])'
    ));
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

  const updateCategoryOptions = () => {
    const selectedCategory = categoryFilter.value;
    const knownCategories = new Set(defaultCategoryOptions.map((option) => option.value));
    const categories = [...new Set(articles.map((article) => article.category).filter(Boolean))]
      .filter((category) => !knownCategories.has(category))
      .sort((left, right) => left.localeCompare(right));
    categoryFilter.replaceChildren(
      new Option("All news", ""),
      ...defaultCategoryOptions.map((option) => new Option(option.label, option.value)),
      ...categories.map((category) => new Option(category, category))
    );
    categoryFilter.value = knownCategories.has(selectedCategory) || categories.includes(selectedCategory)
      ? selectedCategory
      : "";
  };

  const render = () => {
    updateCategoryOptions();
    const visibleArticles = categoryFilter.value
      ? articles.filter((article) => article.category === categoryFilter.value)
      : articles;
    if (!articles.length) {
      tbody.innerHTML = '<tr><td colspan="4">No news articles yet.</td></tr>';
      return;
    }
    if (!visibleArticles.length) {
      tbody.innerHTML = '<tr><td colspan="4">No articles in this category.</td></tr>';
      return;
    }
    tbody.replaceChildren(...visibleArticles.map((article) => {
      const row = document.createElement("tr");
      row.innerHTML = `<td><strong>${portal.escape(article.title)}</strong></td>
        <td>${portal.date(article.published_at || article.updated_at)}</td>
        <td>${portal.escape(article.category)} · ${portal.status(article.status)}</td>
        <td class="table-actions"><button class="button button-small button-outline" type="button">Edit</button>
        <button class="button button-small button-ghost" type="button">Delete</button></td>`;
      const [editButton, deleteButton] = row.querySelectorAll("button");
      editButton.addEventListener("click", () => editArticle(article, editButton));
      deleteButton.addEventListener("click", async () => {
        if (!window.confirm(`Delete "${article.title}"?`)) return;
        deleteButton.disabled = true;
        try {
          await portal.request("admin.delete-news", { method: "POST", body: { articleId: article.article_id } });
          articles = articles.filter((item) => item.article_id !== article.article_id);
          render();
        } catch (error) {
          portal.message(row, error.message, true);
          deleteButton.disabled = false;
        }
      });
      return row;
    }));
  };

  addButton.addEventListener("click", () => editArticle(null, addButton));
  categoryFilter.addEventListener("change", render);
  form.elements.image.addEventListener("change", () => {
    const file = form.elements.image.files[0];
    if (file) showPreview(URL.createObjectURL(file));
  });
  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    if (!form.reportValidity()) return;
    const saveButton = form.querySelector('[type="submit"]');
    saveButton.disabled = true;
    message.hidden = true;
    try {
      await portal.request("admin.save-news", { method: "POST", body: new FormData(form) });
      closeForm(false);
      addButton.focus();
      try {
        const result = await portal.request("admin.news");
        articles = result.articles;
        render();
      } catch (error) {
        portal.message(document.querySelector(".card"), `Article saved, but the list could not be refreshed: ${error.message}`, true);
      }
    } catch (error) {
      portal.message(message, error.message, true);
    } finally {
      saveButton.disabled = false;
    }
  });

  try {
    const result = await portal.request("admin.news");
    articles = result.articles;
    render();
  } catch (error) {
    tbody.innerHTML = '<tr><td colspan="4">News articles could not be loaded.</td></tr>';
    portal.message(document.querySelector(".card"), error.message, true);
  }
});
