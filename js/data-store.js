/* Beginner-friendly shared frontend data store.
 * It uses localStorage so every page in this prototype sees the same data.
 * There is intentionally no server, authentication, or API involved.
 */
(function () {
  "use strict";
  const KEY = "ibacolor.mock-data.v1";
  const clone = (value) => JSON.parse(JSON.stringify(value));
  const seed = () => clone(window.MOCK_DATA || {});

  function read() {
    try {
      const saved = localStorage.getItem(KEY);
      const data = saved ? Object.assign(seed(), JSON.parse(saved)) : seed();
      // Keep the existing beginner-friendly MOCK_DATA references working.
      if (window.MOCK_DATA) Object.keys(data).forEach((key) => { window.MOCK_DATA[key] = data[key]; });
      return data;
    } catch (error) {
      return seed();
    }
  }
  function write(data) {
    localStorage.setItem(KEY, JSON.stringify(data));
    window.dispatchEvent(new CustomEvent("ibacolor:data-changed", { detail: data }));
    return data;
  }
  window.IBacolorStore = {
    key: KEY,
    get: (collection) => {
      const data = read();
      return collection ? clone(data[collection] || []) : clone(data);
    },
    set: (collection, value) => {
      const data = read();
      data[collection] = clone(value);
      return write(data);
    },
    update: (collection, id, changes) => {
      const data = read();
      const list = data[collection] || [];
      const item = list.find((entry) => String(entry.id || entry.reference) === String(id));
      if (!item) return null;
      Object.assign(item, changes);
      write(data);
      return clone(item);
    },
    add: (collection, value) => {
      const data = read();
      data[collection] = data[collection] || [];
      data[collection].push(clone(value));
      write(data);
      return clone(value);
    },
    remove: (collection, id) => {
      const data = read();
      data[collection] = (data[collection] || []).filter((entry) => String(entry.id || entry.reference) !== String(id));
      return write(data);
    },
    reset: () => write(seed())
  };
})();
