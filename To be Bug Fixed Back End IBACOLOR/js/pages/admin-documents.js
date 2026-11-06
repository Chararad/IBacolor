"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const tbody = document.getElementById("admin-documents-list");
  try {
    const { documents } = await portal.request("admin.documents");
    if (!documents.length) {
      tbody.innerHTML = "<tr><td colspan=\"6\">No documents have been uploaded.</td></tr>";
      return;
    }
    tbody.replaceChildren(...documents.map((documentItem) => {
      const row = document.createElement("tr");
      row.innerHTML = `<td><strong>${portal.escape(documentItem.document_name)}</strong><br><small>${portal.escape(documentItem.reference)}</small></td>
        <td>${portal.escape(documentItem.owner_name)}</td><td>${portal.escape(documentItem.mime_type)}</td>
        <td>${portal.date(documentItem.uploaded_at)}</td><td>${portal.status(documentItem.review_status)}</td>
        <td class="table-actions"><a class="button button-small button-outline" href="../../includes/api.php?action=download&type=request&id=${documentItem.document_id}">Download</a>
        <button class="button button-small button-ghost" type="button" data-id="${documentItem.document_id}">Review</button></td>`;
      row.querySelector("button").addEventListener("click", async (event) => {
        const status = window.prompt("Enter review status: submitted, accepted, or needs correction", documentItem.review_status);
        if (!status) return;
        const note = window.prompt("Optional review note", documentItem.review_note || "") || "";
        try {
          await portal.request("admin.review-document", { method: "POST", body: { documentId: documentItem.document_id, status: status.toLowerCase(), note } });
          location.reload();
        } catch (error) {
          portal.message(row, error.message, true);
        }
      });
      return row;
    }));
  } catch (error) {
    tbody.innerHTML = "<tr><td colspan=\"6\">Documents could not be loaded.</td></tr>";
    portal.message(document.querySelector(".card"), error.message, true);
  }
});
