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

Department administrator account setup:
1. Import `ibacolor1.0.sql` when restoring the backup, or apply the audit migration in `migrations/` to an existing database.
2. Run the project-root `Authorization-Table.sql` after the main schema so role,
   permission, service-scope, role-audit, and general history tables are present.
3. In a test database, run the project-root `Admin-Account-Setup.sql`. It
   inserts missing new-admin accounts as pending, assigns roles, restores the
   three existing admins' profiles/preferences from the backup, and adds
   synthetic profiles/preferences for the seven new accounts. It also removes
   Barangay Clearance and Civil Registry Certificate. Do not run this
   test-data script against production.
4. Set `IBACOLOR_ADMIN_MAIN_PASSWORD`, `IBACOLOR_ADMIN_ASSESSOR_PASSWORD`,
   `IBACOLOR_ADMIN_TREASURER_PASSWORD`, `IBACOLOR_ADMIN_ENGINEERING_PASSWORD`,
   `IBACOLOR_ADMIN_SWDO_PASSWORD`, `IBACOLOR_ADMIN_PLANNING_PASSWORD`, and
   `IBACOLOR_ADMIN_HEALTH_PASSWORD` in the local environment.
5. Run `php scripts/provision-admin-accounts.php` from this directory. It sets
   secure password hashes and activates the seven accounts inserted by the SQL.

The three retained admin accounts (`ralchad20067@gmail.com`,
`2024304413@pampangastateu.edu.ph`, and `cheskasitchon@gmail.com`) receive the
Master Admin role. The active `main.admin@gmail.com` account receives the Main
Website Admin role; the six department accounts receive their scoped roles.
Master Admins can use **Admin access** in the admin sidebar to create department
admins, assign or revoke department roles, suspend accounts, and edit global or
service-scoped permissions. Removing access suspends the account and revokes
its roles without deleting the account or its audit history. Master Admin
assignment itself cannot be changed from the console. Permission changes are
checked by the server-side admin APIs as well as by the page guards.
Admins with report-routing permission can route community reports to department
roles; department admins only receive reports assigned to one of their roles.

To inspect recent audit events:

```sql
SELECT history_id, actor_account_id, actor_role, event_type,
       entity_type, entity_id, details, created_at
FROM audit_history
ORDER BY created_at DESC, history_id DESC
LIMIT 100;
```

Database cleanup and system-data backup:
1. `system-data-backup.sql` contains only the existing barangay, service,
   requirement, public-contact, news, and transparency-document rows from the
   main dump, excluding Barangay Clearance and Civil Registry Certificate.
   It excludes accounts, submissions, and history. Run it only when restoring
   those rows into empty tables; it is not an incremental update.
2. Back up uploaded files separately from `storage/private/`; SQL contains
   their metadata and storage keys, not the file contents.
3. If the reset has already been run, do not run it again. Run
   `Admin-Account-Setup.sql` in a test database, then provision the seven
   accounts' passwords as described above.
4. For a future reset, run the reset preflight separately and continue only
   when it returns `1`.
5. Review and run `reset-user-data.sql` only after confirming the database
   backup. It removes resident accounts, submissions, verification data, and
   logs; keeps all current admins, their role assignments, and public/system
   content, admin profile records and admin notification preferences; and
   preserves the remaining service and hotline rows.
6. The reset script changes database rows only. It does not delete uploaded
   files from `storage/private/`.
