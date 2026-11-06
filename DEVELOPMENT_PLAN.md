# iBacolor Frontend Development Plan

## Scope

This phase is a frontend-only prototype. It uses PHP page files containing HTML5 markup, CSS3, and vanilla JavaScript with realistic mock data. There is no database, server-side authentication, API, file processing, or Supabase configuration.

## Frontend pages

### Public

- **Home** (`index.php`): introduces iBacolor, highlights services and news, and provides contact information.
- **Services** (`pages/services.php`): browses municipal services, requirements, availability, and request actions.
- **News** (`pages/news.php`): displays municipal announcements.
- **Emergency Hotlines** (`pages/hotlines.php`): displays emergency and office contact information.
- **Login** (`pages/login.php`): visual sign-in form only.
- **Registration** (`pages/register.php`): visual resident registration form only.

### Resident

- **Dashboard**: summary cards, recent requests, updates, and quick actions.
- **My Requests**: request list with status and tracking links.
- **Request Details**: request timeline, requirements, reference number, and document UI.
- **Community Reports**: report issue form and existing report statuses.
- **Profile**: visual resident profile and account preferences.

### Administrator

- **Dashboard**: operational summaries and recent activity.
- **Requests**: request management table.
- **Request Details**: review, document, and status update interface.
- **Documents**: document review table.
- **Reports**: community report management.
- **News Management**: announcement management table and form.
- **Services Management**: service catalog management table and form.
- **Hotlines Management**: contact management table and form.
- **Users**: resident account management table.

## Shared components

Each PHP page contains its own semantic page structure, including the shared header and footer, navigation, page content, and page-specific forms or tables. Shared component appearance is defined in `css/style.css`, `css/components.css`, and `css/responsive.css`; page-specific styles in `css/pages/` import those shared styles. JavaScript entry points in `js/pages/` initialize each page's interactions, with reusable behavior in `js/modules/` and navigation highlighting in `js/navigation.js`.

## Navigation structure

- Public navigation: Home, Services, News, Emergency Hotlines, Sign in.
- Resident navigation: Dashboard, My Requests, Community Reports, Profile, Sign out.
- Admin navigation: Dashboard, Requests, Reports, Documents, Content Management, Users, Sign out.
- Links remain local and use mock route pages so the site works with XAMPP's Apache server.

## Development order

1. Establish the visual system and responsive shell.
2. Add centralized mock data.
3. Structure public discovery pages and forms in PHP page files.
4. Structure resident tracking and reporting pages in PHP page files.
5. Structure administrator tables and management views in PHP page files.
6. Add behavior in JavaScript and validate links, responsive layouts, and console behavior.

## Temporary/mock data

`js/mock-data.js` contains sample services, news, hotlines, requests, reports, users, and documents. It is deliberately separate from rendering logic and is intended to be replaced by backend data in a later phase.

## Intentionally postponed

- Database schema and persistence
- Supabase setup or API calls
- Authentication, authorization, sessions, and password handling
- Actual request/report submission
- Real file upload and document storage
- Email/SMS notifications
- Server-side validation and audit logging
- Production deployment and security hardening
