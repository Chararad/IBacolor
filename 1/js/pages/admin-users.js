"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const tbody = document.querySelector("table tbody");
  try {
    const { users } = await portal.request("admin.users");
    tbody.replaceChildren(...users.map((user) => {
      const row = document.createElement("tr");
      row.innerHTML = `<td><strong>${portal.escape(`${user.first_name} ${user.last_name}`)}</strong></td>
        <td>${portal.escape(user.email)}</td><td>${portal.escape(user.barangay_name || "—")}</td>
        <td>${portal.date(user.created_at)}</td><td>${portal.status(user.verification === "verified" ? user.status : `Pending verification · ${user.status}`)}</td>
        <td><button class="button button-small button-outline" type="button">Manage</button></td>`;
      row.querySelector("button").addEventListener("click", async () => {
        const status = window.prompt("Account status: active, pending, or suspended", user.status);
        if (!status) return;
        const verification = window.prompt("Verification status: pending or verified", user.verification);
        if (!verification) return;
        try {
          await portal.request("admin.update-user", {
            method: "POST",
            body: { accountId: user.account_id, status: status.toLowerCase(), verification: verification.toLowerCase() },
          });
          location.reload();
        } catch (error) {
          portal.message(row, error.message, true);
        }
      });
      return row;
    }));
  } catch (error) {
    portal.message(document.querySelector(".card"), error.message, true);
  }
});
