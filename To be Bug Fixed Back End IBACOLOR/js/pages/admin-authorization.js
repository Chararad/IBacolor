"use strict";

(() => {
  const portal = window.IBacolorPortal;
  if (!portal) return;

  const message = document.getElementById("authorization-message");
  const adminRows = document.getElementById("admin-accounts");
  const createRoles = document.getElementById("create-role-options");
  const roleSelect = document.getElementById("permission-role");
  const globalPermissions = document.getElementById("global-permissions");
  const servicePermissions = document.getElementById("service-permissions");
  const scopedCodes = new Set(["service_requests.view", "service_requests.update", "documents.review"]);
  const state = { data: null, busy: false };

  const showMessage = (text, error = false) => {
    message.textContent = text;
    message.classList.toggle("is-error", error);
    message.hidden = false;
  };
  const roleLabel = (role) => role.role_name || role.role_code;
  const findPermission = (code) => state.data.permissions.find((permission) => permission.permission_code === code);
  const hasGlobalPermission = (roleId, permissionId) => state.data.rolePermissions.some(
    (item) => Number(item.role_id) === Number(roleId) && Number(item.permission_id) === Number(permissionId),
  );
  const hasServicePermission = (roleId, permissionId, serviceId) => state.data.servicePermissions.some(
    (item) => Number(item.role_id) === Number(roleId)
      && Number(item.permission_id) === Number(permissionId)
      && Number(item.service_id) === Number(serviceId),
  );
  const selectedRole = () => state.data.roles.find((role) => Number(role.role_id) === Number(roleSelect.value));

  async function load() {
    const data = await portal.request("admin.authorization.dashboard");
    state.data = data;
    renderRoleOptions();
    renderAdmins();
    renderPermissions();
  }

  function renderRoleOptions() {
    const assignable = state.data.roles.filter((role) => !["master_admin", "main_website_admin"].includes(role.role_code));
    createRoles.innerHTML = assignable.map((role) => `
      <label><input type="checkbox" name="roleIds" value="${portal.escape(role.role_id)}" />
        <span>${portal.escape(roleLabel(role))}</span></label>`).join("");
    const editable = state.data.roles.filter((role) => role.role_code !== "master_admin");
    roleSelect.innerHTML = editable.map((role) => `
      <option value="${portal.escape(role.role_id)}">${portal.escape(roleLabel(role))}</option>`).join("");
    roleSelect.value = String(editable.find((role) => role.role_code === "main_website_admin")?.role_id || editable[0]?.role_id || "");
  }

  function renderAdmins() {
    const assignable = state.data.roles.filter((role) => !["master_admin", "main_website_admin"].includes(role.role_code));
    if (!state.data.admins.length) {
      adminRows.innerHTML = '<tr><td colspan="4">No administrator accounts found.</td></tr>';
      return;
    }
    adminRows.innerHTML = state.data.admins.map((admin) => {
      const protectedAccount = admin.email.toLowerCase() === "main.admin@gmail.com" || admin.roles.includes("master_admin");
      const roleChecks = assignable.map((role) => `
        <label><input type="checkbox" data-admin-role="${portal.escape(role.role_id)}"
          ${admin.roles.includes(role.role_code) ? "checked" : ""}
          ${admin.status !== "active" || protectedAccount ? "disabled" : ""} />
          <span>${portal.escape(roleLabel(role))}</span></label>`).join("");
      const roleText = admin.roles.map((code) => {
        const role = state.data.roles.find((item) => item.role_code === code);
        return role ? roleLabel(role) : code;
      }).join(", ") || "No roles assigned";
      const roleControls = protectedAccount
        ? ""
        : `<div class="account-role-controls" aria-label="Assigned roles for ${portal.escape(admin.email)}">${roleChecks}</div>`;
      const statusAction = admin.status === "active" ? "suspended" : "active";
      return `<tr data-account-id="${portal.escape(admin.account_id)}">
        <td class="admin-account-cell"><strong>${portal.escape(admin.email)}</strong><small>Created ${portal.date(admin.created_at)}</small></td>
        <td>${portal.status(admin.status)}</td>
        <td>${roleControls}
          ${!protectedAccount ? '<button class="button button-outline" type="button" data-save-roles>Save roles</button>' : `<small>${portal.escape(roleText)}</small>`}
        </td>
        <td><div class="account-actions">
          ${!protectedAccount ? `<button class="button button-outline" type="button" data-set-status="${statusAction}">${statusAction === "active" ? "Activate" : "Suspend"}</button>
          <button class="button button-danger" type="button" data-remove-admin>Remove access</button>` : '<span class="permission-locked">Protected account</span>'}
        </div></td>
      </tr>`;
    }).join("");
  }

  function renderPermissions() {
    const role = selectedRole();
    if (!role) return;
    const global = state.data.permissions.filter((permission) => !scopedCodes.has(permission.permission_code));
    globalPermissions.innerHTML = global.map((permission) => {
      const locked = permission.permission_code === "authorization.manage";
      return `<div class="global-permission">
        <label class="permission-toggle ${locked ? "permission-locked" : ""}">
          <input type="checkbox" data-global-permission="${portal.escape(permission.permission_id)}"
            ${hasGlobalPermission(role.role_id, permission.permission_id) ? "checked" : ""}
            ${locked ? "disabled" : ""} />
          <span>${portal.escape(permission.permission_code)}</span>
        </label>
        <small>${portal.escape(permission.permission_description)}</small>
      </div>`;
    }).join("");

    const serviceCodes = [...scopedCodes];
    servicePermissions.innerHTML = state.data.services.map((service) => `
      <tr>
        <th scope="row">${portal.escape(service.name)}</th>
        ${serviceCodes.map((code) => {
          const permission = findPermission(code);
          const checked = hasServicePermission(role.role_id, permission.permission_id, service.service_id);
          return `<td><label>
            <input type="checkbox" data-service-permission="${portal.escape(permission.permission_id)}"
              data-service-id="${portal.escape(service.service_id)}" ${checked ? "checked" : ""} />
            <span class="visually-hidden">${portal.escape(code)} for ${portal.escape(service.name)}</span>
          </label></td>`;
        }).join("")}
      </tr>`).join("");
  }

  async function savePermission(input, serviceId = null) {
    const role = selectedRole();
    const body = {
      roleId: Number(role.role_id),
      permissionId: Number(input.dataset.globalPermission || input.dataset.servicePermission),
      enabled: input.checked,
    };
    if (serviceId !== null) body.serviceId = Number(serviceId);
    input.disabled = true;
    try {
      await portal.request("admin.authorization.set-permission", { method: "POST", body });
      await load();
      showMessage("Permission updated.");
    } catch (error) {
      input.checked = !input.checked;
      showMessage(error.message, true);
    } finally {
      input.disabled = false;
    }
  }

  roleSelect.addEventListener("change", renderPermissions);
  globalPermissions.addEventListener("change", (event) => {
    const input = event.target.closest("[data-global-permission]");
    if (input) savePermission(input);
  });
  servicePermissions.addEventListener("change", (event) => {
    const input = event.target.closest("[data-service-permission]");
    if (input) savePermission(input, input.dataset.serviceId);
  });

  adminRows.addEventListener("click", async (event) => {
    const row = event.target.closest("tr[data-account-id]");
    if (!row || state.busy) return;
    const accountId = Number(row.dataset.accountId);
    const roleButton = event.target.closest("[data-save-roles]");
    const statusButton = event.target.closest("[data-set-status]");
    const removeButton = event.target.closest("[data-remove-admin]");
    try {
      state.busy = true;
      if (roleButton) {
        const checkedIds = new Set(Array.from(row.querySelectorAll("[data-admin-role]:checked"), (input) => Number(input.dataset.adminRole)));
        const currentAdmin = state.data.admins.find((admin) => Number(admin.account_id) === accountId);
        for (const role of state.data.roles.filter((item) => !["master_admin", "main_website_admin"].includes(item.role_code))) {
          const currentlyAssigned = currentAdmin.roles.includes(role.role_code);
          const shouldAssign = checkedIds.has(Number(role.role_id));
          if (currentlyAssigned !== shouldAssign) {
            await portal.request("admin.authorization.assign-role", {
              method: "POST",
              body: { accountId, roleId: Number(role.role_id), operation: shouldAssign ? "assign" : "revoke" },
            });
          }
        }
        showMessage("Admin roles updated.");
      } else if (statusButton) {
        const status = statusButton.dataset.setStatus;
        await portal.request("admin.authorization.set-status", { method: "POST", body: { accountId, status } });
        showMessage(`Admin account ${status}.`);
      } else if (removeButton) {
        const admin = state.data.admins.find((item) => Number(item.account_id) === accountId);
        if (!window.confirm(`Suspend ${admin.email} and revoke all assigned roles? History will be preserved.`)) return;
        await portal.request("admin.authorization.remove-admin", { method: "POST", body: { accountId } });
        showMessage("Admin access removed. Its account and history were preserved.");
      } else {
        return;
      }
      await load();
    } catch (error) {
      showMessage(error.message, true);
      await load();
    } finally {
      state.busy = false;
    }
  });

  document.getElementById("admin-create-form").addEventListener("submit", async (event) => {
    event.preventDefault();
    const form = event.currentTarget;
    const submit = form.querySelector('button[type="submit"]');
    const data = new FormData(form);
    const roleIds = data.getAll("roleIds").map(Number);
    if (!roleIds.length) {
      showMessage("Choose at least one department role.", true);
      return;
    }
    submit.disabled = true;
    try {
      await portal.request("admin.authorization.create-admin", {
        method: "POST",
        body: { email: data.get("email"), password: data.get("password"), roleIds },
      });
      form.reset();
      await load();
      showMessage("Admin account created. The password is not displayed again.");
    } catch (error) {
      showMessage(error.message, true);
    } finally {
      submit.disabled = false;
    }
  });

  document.addEventListener("portal:ready", () => {
    load().catch((error) => showMessage(error.message, true));
  }, { once: true });
})();
