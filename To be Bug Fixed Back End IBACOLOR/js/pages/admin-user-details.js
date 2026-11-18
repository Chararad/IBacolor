"use strict";

document.addEventListener("portal:ready", async () => {
  const portal = window.IBacolorPortal;
  const errorMessage = document.getElementById("resident-details-error");
  const content = document.getElementById("resident-details-content");
  const accountId = new URLSearchParams(location.search).get("accountId");
  if (!/^\d+$/.test(accountId || "")) {
    portal.message(errorMessage, "A valid resident account was not provided.", true);
    return;
  }

  const createText = (tagName, className, value) => {
    const element = document.createElement(tagName);
    if (className) element.className = className;
    element.textContent = value == null || value === "" ? "—" : String(value);
    return element;
  };

  const createTimelineEntry = ({ title, date, note, actor }) => {
    const entry = document.createElement("article");
    entry.className = "resident-timeline-entry";
    entry.append(createText("h3", "", title));
    entry.append(createText("time", "resident-timeline-date", portal.date(date)));
    if (actor) entry.append(createText("p", "resident-timeline-actor", `By ${actor}`));
    if (note) entry.append(createText("p", "resident-timeline-note", note));
    return entry;
  };

  const renderTimeline = (container, entries, emptyMessage) => {
    container.replaceChildren();
    if (!entries.length) {
      container.append(createText("p", "", emptyMessage));
      return;
    }
    entries.forEach((entry) => container.append(createTimelineEntry(entry)));
  };

  const renderHistory = (container, history, emptyMessage) => {
    renderTimeline(container, history.map((entry) => ({
      title: entry.status,
      date: entry.changed_at,
      note: entry.note,
      actor: entry.actor_name,
    })), emptyMessage);
  };

  const timestamp = (value) => {
    const normalized = String(value || "").replace(" ", "T");
    const zoned = /[zZ]|[+-]\d{2}:\d{2}$/.test(normalized) ? normalized : `${normalized}Z`;
    const parsed = Date.parse(zoned);
    return Number.isNaN(parsed) ? 0 : parsed;
  };

  const renderRequests = (container, requests) => {
    container.replaceChildren();
    if (!requests.length) {
      container.append(createText("p", "", "This resident has not submitted any service requests."));
      return;
    }
    requests.forEach((request) => {
      const record = document.createElement("article");
      record.className = "resident-record";
      const heading = document.createElement("div");
      heading.className = "resident-record-heading";
      heading.append(createText("h3", "", `${request.reference} · ${request.service_name || "Service request"}`));
      heading.insertAdjacentHTML("beforeend", portal.status(request.status));
      record.append(heading);
      record.append(createText("p", "resident-record-date", `Submitted ${portal.date(request.submitted_at)}`));
      record.append(createText("p", "resident-record-description", request.details));
      record.append(createText("h4", "", "Status history"));
      const timeline = document.createElement("div");
      timeline.className = "resident-timeline";
      renderHistory(timeline, request.history || [], "No status history is available.");
      record.append(timeline);
      container.append(record);
    });
  };

  const renderReports = (container, reports) => {
    container.replaceChildren();
    if (!reports.length) {
      container.append(createText("p", "", "This resident has not submitted any community reports."));
      return;
    }
    reports.forEach((report) => {
      const record = document.createElement("article");
      record.className = "resident-record";
      const heading = document.createElement("div");
      heading.className = "resident-record-heading";
      heading.append(createText("h3", "", `${report.reference} · ${report.category}`));
      heading.insertAdjacentHTML("beforeend", portal.status(report.status));
      record.append(heading);
      record.append(createText("p", "resident-record-date", `Submitted ${portal.date(report.submitted_at)}`));
      record.append(createText("p", "resident-record-description", report.description));
      const location = [report.barangay_name, report.location_details].filter(Boolean).join(" · ");
      record.append(createText("p", "", `Location: ${location || "—"}`));
      if (report.observed_at) record.append(createText("p", "", `Observed: ${portal.date(report.observed_at)}`));
      if (report.frequency) record.append(createText("p", "", `Frequency: ${report.frequency}`));
      record.append(createText("h4", "", "Status history"));
      const timeline = document.createElement("div");
      timeline.className = "resident-timeline";
      renderHistory(timeline, report.history || [], "No status history is available.");
      record.append(timeline);
      container.append(record);
    });
  };

  try {
    const data = await portal.request("admin.user-details", { query: { accountId } });
    const profile = data.profile;
    const fullName = [profile.first_name, profile.middle_name, profile.last_name]
      .filter((name) => typeof name === "string" && name.trim())
      .map((name) => name.trim())
      .join(" ");
    document.getElementById("resident-name").textContent = fullName || "Resident profile";

    const profileFields = document.getElementById("resident-profile-fields");
    const fields = [
      ["Account ID", profile.account_id],
      ["Email", profile.email],
      ["Mobile number", profile.mobile_number],
      ["Address", profile.address],
      ["Barangay", profile.barangay_name],
      ["Account status", profile.status],
      ["Verification status", profile.verification],
      ["Registered", portal.date(profile.registered_at)],
    ];
    profileFields.replaceChildren(...fields.map(([label, value]) => {
      const row = document.createElement("div");
      row.append(createText("dt", "", label), createText("dd", "", value));
      return row;
    }));

    renderHistory(
      document.getElementById("resident-verification-history"),
      data.verificationHistory,
      "No verification changes have been recorded."
    );
    renderRequests(document.getElementById("resident-service-requests"), data.requests);
    renderReports(document.getElementById("resident-community-reports"), data.reports);

    const activities = [{
      title: "Resident account registered",
      date: profile.registered_at,
    }];
    data.verificationHistory.forEach((entry) => {
      activities.push({
        title: `Verification status: ${entry.status}`,
        date: entry.changed_at,
        note: entry.note,
        actor: entry.actor_name,
      });
    });
    data.requests.forEach((request) => {
      request.history.forEach((entry) => {
        activities.push({
          title: `Service request ${request.reference}: ${entry.status}`,
          date: entry.changed_at,
          note: entry.note,
          actor: entry.actor_name,
        });
      });
    });
    data.reports.forEach((report) => {
      report.history.forEach((entry) => {
        activities.push({
          title: `Community report ${report.reference}: ${entry.status}`,
          date: entry.changed_at,
          note: entry.note,
          actor: entry.actor_name,
        });
      });
    });
    activities.sort((left, right) => timestamp(right.date) - timestamp(left.date));
    renderTimeline(
      document.getElementById("resident-activity-history"),
      activities,
      "No activity has been recorded."
    );
    content.hidden = false;
  } catch (error) {
    portal.message(errorMessage, error.message, true);
  }
});
