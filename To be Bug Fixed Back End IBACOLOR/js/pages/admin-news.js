"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const form = document.getElementById("admin-news-form");
  const tbody = document.getElementById("admin-news-list");
  const preview = document.getElementById("news-image-preview");
  const addButton = document.getElementById("add-news-article");
  const cancelButton = document.getElementById("cancel-news-edit");
  const message = document.getElementById("news-form-message");
  if (!form || !tbody || !preview || !addButton || !cancelButton || !message) return;

  let articles = [];
  let previewUrl = "";

  const showPreview = (url) => {
    if (previewUrl.startsWith("blob:")) URL.revokeObjectURL(previewUrl);
    previewUrl = url || "";
    preview.src = previewUrl;
    preview.hidden = !previewUrl;
  };
  const closeForm = () => {
    form.reset();
    form.elements.articleId.value = "";
    form.hidden = true;
    message.hidden = true;
    showPreview("");
  };
  const editArticle = (article = null) => {
    form.reset();
    form.elements.articleId.value = article?.article_id || "";
    form.elements.title.value = article?.title || "";
    form.elements.category.value = article?.category || "";
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
    form.hidden = false;
    form.scrollIntoView({ behavior: "smooth", block: "start" });
  };

  const render = () => {
    if (!articles.length) {
      tbody.innerHTML = '<tr><td colspan="4">No news articles yet.</td></tr>';
      return;
    }
    tbody.replaceChildren(...articles.map((article) => {
      const row = document.createElement("tr");
      row.innerHTML = `<td><strong>${portal.escape(article.title)}</strong></td>
        <td>${portal.date(article.published_at || article.updated_at)}</td>
        <td>${portal.escape(article.category)} · ${portal.status(article.status)}</td>
        <td class="table-actions"><button class="button button-small button-outline" type="button">Edit</button>
        <button class="button button-small button-ghost" type="button">Delete</button></td>`;
      const [editButton, deleteButton] = row.querySelectorAll("button");
      editButton.addEventListener("click", () => editArticle(article));
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

  addButton.addEventListener("click", () => editArticle());
  cancelButton.addEventListener("click", closeForm);
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
      closeForm();
      const result = await portal.request("admin.news");
      articles = result.articles;
      render();
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
