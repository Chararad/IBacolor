"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const tbody = document.querySelector("table tbody");
  if (!tbody) return;
  try {
    const { users } = await portal.request("admin.users");
    tbody.replaceChildren(...users.map((user) => {
      const row = document.createElement("tr");
      row.dataset.accountStatus = user.status;
      row.dataset.verificationStatus = user.verification;
      row.innerHTML = `<td><strong>${portal.escape(`${user.first_name} ${user.last_name}`)}</strong>
          <details><summary>View details</summary><p>${portal.escape(user.email)}</p>
            <p>Barangay: ${portal.escape(user.barangay_name || "—")}</p>
            <p>Registered: ${portal.date(user.created_at)}</p>
            ${user.verification_note ? `<p>Latest verification remark: ${portal.escape(user.verification_note)}</p>` : ""}
          </details></td>
        <td>${portal.escape(user.email)}</td><td>${portal.escape(user.barangay_name || "—")}</td>
        <td>${portal.date(user.created_at)}</td>
        <td>${portal.status(user.status)} ${portal.status(user.verification)}</td>
        <td class="table-actions"><button class="button button-small button-outline" type="button">Account status</button>
          <button class="button button-small button-ghost" type="button">Review resident</button>
          <button class="button button-small button-outline" type="button">Verification documents</button></td>`;
      const [accountButton, verificationButton, documentsButton] = row.querySelectorAll("button");
      accountButton.addEventListener("click", async () => {
        const status = window.prompt("Account status: active, pending (for review), or suspended (inactive)", user.status);
        if (!status) return;
        accountButton.disabled = true;
        try {
          await portal.request("admin.update-user", {
            method: "POST",
            body: { accountId: user.account_id, status: status.toLowerCase() },
          });
          location.reload();
        } catch (error) {
          portal.message(row, error.message, true);
          accountButton.disabled = false;
        }
      });
      verificationButton.addEventListener("click", async () => {
        const verification = window.prompt("Resident verification: pending, verified, or rejected", user.verification);
        if (!verification) return;
        const normalized = verification.toLowerCase();
        if (!["pending", "verified", "rejected"].includes(normalized)) {
          portal.message(row, "Choose pending, verified, or rejected.", true);
          return;
        }
        const note = window.prompt(normalized === "rejected" ? "Rejection reason (required)" : "Verification remarks (optional)", user.verification_note || "");
        if (note === null) return;
        if (normalized === "rejected" && !note.trim()) {
          portal.message(row, "Enter a reason before rejecting resident verification.", true);
          return;
        }
        verificationButton.disabled = true;
        try {
          await portal.request("admin.update-user", {
            method: "POST",
            body: { accountId: user.account_id, verification: normalized, note },
          });
          location.reload();
        } catch (error) {
          portal.message(row, error.message, true);
          verificationButton.disabled = false;
        }
      });
      documentsButton.addEventListener("click", async () => {
        const existing = row.nextElementSibling;
        if (existing?.classList.contains("verification-documents-row")) {
          existing.remove();
          return;
        }
        documentsButton.disabled = true;
        try {
          const { documents } = await portal.request("admin.user-documents", {
            query: { accountId: user.account_id },
          });
          const detailRow = document.createElement("tr");
          detailRow.className = "verification-documents-row";
          const cell = document.createElement("td");
          cell.colSpan = 6;
          if (!documents.length) {
            cell.textContent = "No verification documents submitted.";
          } else {
            documents.forEach((documentInfo) => {
              const url = `../../includes/api.php?action=download&type=verification&id=${encodeURIComponent(documentInfo.document_id)}&inline=1`;
              if (documentInfo.mime_type.startsWith("image/")) {
                const link = document.createElement("a");
                link.href = url;
                link.target = "_blank";
                link.rel = "noopener";
                const image = document.createElement("img");
                image.className = "report-preview";
                image.src = url;
                image.alt = `Verification document: ${documentInfo.file_name}`;
                link.append(image);
                cell.append(link);
              } else {
                const link = document.createElement("a");
                link.className = "text-link";
                link.href = url;
                link.textContent = documentInfo.file_name;
                link.target = "_blank";
                link.rel = "noopener";
                cell.append(link, document.createElement("br"));
              }
            });
          }
          detailRow.append(cell);
          row.after(detailRow);
        } catch (error) {
          portal.message(row, error.message, true);
        } finally {
          documentsButton.disabled = false;
        }
      });
      return row;
    }));
    const search = document.getElementById("resident-search");
    const accountFilter = document.getElementById("account-status-filter");
    const verificationFilter = document.getElementById("verification-status-filter");
    const emptyMessage = document.getElementById("users-filter-empty");
    const applyFilters = () => {
      const query = search.value.trim().toLocaleLowerCase();
      const accountStatus = accountFilter.value;
      const verificationStatus = verificationFilter.value;
      let visibleCount = 0;
      Array.from(tbody.querySelectorAll("tr:not(.verification-documents-row)")).forEach((row) => {
        const matches = row.textContent.toLocaleLowerCase().includes(query)
          && (!accountStatus || row.dataset.accountStatus === accountStatus)
          && (!verificationStatus || row.dataset.verificationStatus === verificationStatus);
        row.hidden = !matches;
        if (row.nextElementSibling?.classList.contains("verification-documents-row")) {
          row.nextElementSibling.hidden = !matches;
        }
        if (matches) visibleCount += 1;
      });
      emptyMessage.hidden = visibleCount > 0;
    };
    search.addEventListener("input", applyFilters);
    accountFilter.addEventListener("change", applyFilters);
    verificationFilter.addEventListener("change", applyFilters);
    applyFilters();
  } catch (error) {
    tbody.innerHTML = "<tr><td colspan=\"6\">Resident accounts could not be loaded.</td></tr>";
    portal.message(document.querySelector(".card"), error.message, true);
  }
});
