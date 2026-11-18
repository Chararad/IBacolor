-- Destructive data reset for an existing IBACOLOR database.
-- Back up the database first. This script keeps all current admin accounts,
-- public content, every service, hotlines, and barangay reference data.
-- It removes resident accounts, submissions, and logs.
-- Apply Authorization-Table.sql first so the admin role/audit tables exist.
-- Run scripts/provision-admin-accounts.php before this script so the main
-- website admin exists for the preflight and public-content attribution.
--
-- Run the preflight query and confirm the main admin account is active.
SELECT COUNT(*) AS active_main_admins_to_keep
FROM accounts
WHERE email = 'main.admin@gmail.com'
  AND roles = 'admin'
  AND status = 'active';

SET @retained_admin_id := (
    SELECT account_id
    FROM accounts
    WHERE email = 'main.admin@gmail.com'
      AND roles = 'admin'
      AND status = 'active'
    LIMIT 1
);

START TRANSACTION;

-- Retained public content must not refer to resident accounts being removed.
-- Reassign those attribution fields to the first retained admin account.
UPDATE news_articles AS n
JOIN accounts AS a ON a.account_id = n.created_by AND a.roles = 'resident'
SET n.created_by = @retained_admin_id;

UPDATE transparency_documents AS d
JOIN accounts AS a ON a.account_id = d.uploaded_by AND a.roles = 'resident'
SET d.uploaded_by = @retained_admin_id;

-- Clear submission-dependent records before their parent records.
DELETE FROM report_attachments;
DELETE FROM report_status_history;
DELETE FROM community_report_assignments;
DELETE FROM community_reports;

DELETE FROM request_appointments;
DELETE FROM request_documents;
DELETE FROM request_status_history;
DELETE FROM service_requests;

DELETE FROM resident_verification_documents;
DELETE FROM resident_verification_history;

-- Remove resident-only profile data while retaining the profile and
-- notification preferences attached to the three admin accounts.
DELETE np
FROM notification_preferences AS np
JOIN accounts AS a ON a.account_id = np.account_id
WHERE a.roles = 'resident';

DELETE rp
FROM resident_profiles AS rp
JOIN accounts AS a ON a.account_id = rp.account_id
WHERE a.roles = 'resident';

-- Clear audit history as requested.
DELETE FROM audit_history;
DELETE FROM admin_authorization_audit;

-- Remove resident accounts only; current admin identities and their role
-- assignments are retained.
DELETE FROM accounts
WHERE roles = 'resident';

COMMIT;
