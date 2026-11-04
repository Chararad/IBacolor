# iBacolor Frontend

Frontend-only municipal public service portal prototype built with HTML5, CSS3, and vanilla JavaScript.

## Run locally

Open the project in VS Code and launch `index.html` with the Live Server extension. All pages use relative links and require no build step.

## How the frontend is organized

- **HTML (`index.html`, `pages/`)** contains each page's semantic structure and visible content, including its header, navigation, main sections, forms or tables, and footer.
- **CSS (`css/`)** controls the shared visual system, component styling, and responsive layouts.
- **JavaScript (`js/`)** adds behavior to the existing HTML: mobile navigation, service search and filtering, request filtering, and form feedback.
- **`js/mock-data.js`** keeps the sample service, news, contact, resident, and administrative records separate from page structure.
- **`js/data-store.js`** provides the browser's local-storage data-store utility.

To explore a page, open its HTML file under `pages/`. To follow the styling, inspect the three linked CSS files; to find a page interaction, inspect `js/main.js` or `js/navigation.js`.

## Important

This phase intentionally contains mock data only. It has no database, backend, authentication, API calls, or Supabase configuration.
