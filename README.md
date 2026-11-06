# iBacolor Frontend

Frontend-only municipal public service portal prototype built with PHP page files containing HTML5 markup, CSS3, and vanilla JavaScript.

## Run locally

Place the project folder under XAMPP's `htdocs`, start Apache, and open `http://localhost/<project-folder>/`. The PHP files contain static page markup; no database or backend setup is required.

## How the frontend is organized

- **PHP (`index.php`, `pages/`)** contains each page's semantic structure and visible content, including its header, navigation, main sections, forms or tables, and footer.
- **CSS (`css/`)** controls the shared visual system, component styling, and responsive layouts.
- **JavaScript (`js/`)** adds behavior to the existing HTML: mobile navigation, service search and filtering, request filtering, and form feedback.
- **`js/mock-data.js`** keeps the sample service, news, contact, resident, and administrative records separate from page structure.
- **`js/data-store.js`** provides the browser's local-storage data-store utility.
- The resident barangay selectors follow the current PSGC Bacolor list: [PSGC barangay endpoint](https://psgc.gitlab.io/api/municipalities/035404000/barangays/).
- Resident registration, sign-in, service/report submissions, and filters are browser-only demonstrations. The mock sign-in is not real authentication or authorization and must not be used to protect real personal data or municipal services.
- The transparency page intentionally indicates that official records are pending publication. Office and barangay contact details that have not been verified are marked rather than invented.

To explore a page, open its PHP file under `pages/`. Each page links to its own stylesheet in `css/pages/`, which imports the shared styles. Page interactions start in `js/pages/`; reusable behavior lives in `js/modules/`, with shared navigation highlighting in `js/navigation.js`.

## Important

This phase intentionally contains mock data only. It has no database, backend, authentication, API calls, or Supabase configuration.
