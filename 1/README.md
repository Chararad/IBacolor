# iBacolor database-backed portal

This copy contains the resident and admin pages and their JavaScript under
`1/`. Open `1/main.php` through XAMPP after starting Apache and MySQL and
importing the `ibacolor` database schema.

Portal pages require an active session and the matching database role. Their
page-specific scripts load from `1/js/pages/` and use `1/includes/api.php`; they do not
load JavaScript from the project root or use browser storage for portal data.
Resident requests and community reports, attached-file metadata, status
histories, resident profile/preferences, admin reviews, service records,
hotlines, and account management use the relational database.

Uploaded files are stored under `1/storage/private/` and served only through
the authenticated API. News content is intentionally not stored in the
relational database schema, so its publishing controls remain unavailable
until the separate publishing workflow is configured.
