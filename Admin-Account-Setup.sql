-- Backup recovery only: restore the three existing admins' resident profiles
-- and notification preferences from ibacolor1.0.sql.
-- This file does not create accounts, assign roles, change passwords, or reset
-- other data. Account and authorization setup has already been run.

START TRANSACTION;

INSERT INTO resident_profiles (
    account_id,
    first_name,
    middle_name,
    last_name,
    mobile_number,
    barangay_id,
    address,
    profile_image_key,
    verification,
    privacy_policy_version,
    consented_at
)
SELECT
    a.account_id,
    restored.first_name,
    restored.middle_name,
    restored.last_name,
    restored.mobile_number,
    restored.barangay_id,
    restored.address,
    NULL,
    restored.verification,
    'v1',
    restored.consented_at
FROM (
    SELECT
        'ralchad20067@gmail.com' AS email,
        'Richard' AS first_name,
        'Miranda' AS middle_name,
        'Lacanlale' AS last_name,
        '09942518011' AS mobile_number,
        16 AS barangay_id,
        'I dont Know' AS address,
        'pending' AS verification,
        '2026-11-06 03:07:02' AS consented_at
    UNION ALL SELECT
        '2024304413@pampangastateu.edu.ph',
        'Jester',
        NULL,
        'Dayao',
        '09850411895',
        3,
        '32432 eme eme',
        'verified',
        '2026-11-13 01:20:34'
    UNION ALL SELECT
        'cheskasitchon@gmail.com',
        'Cheska',
        NULL,
        'Sitchon',
        '09455432378',
        12,
        'No. 123, 123 street',
        'pending',
        '2026-11-13 02:43:05'
) AS restored
JOIN accounts AS a
    ON a.email = restored.email
    AND a.roles = 'admin'
    AND a.status = 'active'
JOIN barangays AS b ON b.barangay_id = restored.barangay_id
ON DUPLICATE KEY UPDATE
    first_name = VALUES(first_name),
    middle_name = VALUES(middle_name),
    last_name = VALUES(last_name),
    mobile_number = VALUES(mobile_number),
    barangay_id = VALUES(barangay_id),
    address = VALUES(address),
    profile_image_key = VALUES(profile_image_key),
    verification = VALUES(verification),
    privacy_policy_version = VALUES(privacy_policy_version),
    consented_at = VALUES(consented_at);

INSERT INTO notification_preferences (
    account_id,
    request_updates,
    announcement_updates
)
SELECT a.account_id, 1, 1
FROM (
    SELECT 'ralchad20067@gmail.com' AS email
    UNION ALL SELECT '2024304413@pampangastateu.edu.ph'
    UNION ALL SELECT 'cheskasitchon@gmail.com'
) AS restored
JOIN accounts AS a
    ON a.email = restored.email
    AND a.roles = 'admin'
    AND a.status = 'active'
ON DUPLICATE KEY UPDATE
    request_updates = VALUES(request_updates),
    announcement_updates = VALUES(announcement_updates);

COMMIT;
