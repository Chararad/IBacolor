-- IBACOLOR ADMIN AUTHORIZATION TABLES
-- Requires the existing accounts, services, and community_reports tables.
-- Apply this script after importing the main IBacolor schema.

CREATE TABLE IF NOT EXISTS admin_roles (
    role_id int(11) NOT NULL AUTO_INCREMENT,
    role_code varchar(64) NOT NULL,
    role_name varchar(150) NOT NULL,
    role_description varchar(500) DEFAULT NULL,
    PRIMARY KEY (role_id),
    UNIQUE KEY uq_admin_roles_code (role_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS admin_permissions (
    permission_id int(11) NOT NULL AUTO_INCREMENT,
    permission_code varchar(100) NOT NULL,
    permission_description varchar(255) NOT NULL,
    PRIMARY KEY (permission_id),
    UNIQUE KEY uq_admin_permissions_code (permission_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Each admin account can be assigned one or more roles.
CREATE TABLE IF NOT EXISTS admin_account_roles (
    account_id int(11) NOT NULL,
    role_id int(11) NOT NULL,
    granted_by int(11) DEFAULT NULL,
    granted_at timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (account_id, role_id),
    KEY idx_admin_account_roles_role (role_id),
    CONSTRAINT fk_admin_account_roles_account
        FOREIGN KEY (account_id) REFERENCES accounts (account_id) ON DELETE CASCADE,
    CONSTRAINT fk_admin_account_roles_role
        FOREIGN KEY (role_id) REFERENCES admin_roles (role_id) ON DELETE CASCADE,
    CONSTRAINT fk_admin_account_roles_grantor
        FOREIGN KEY (granted_by) REFERENCES accounts (account_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Global module permissions granted to a role.
CREATE TABLE IF NOT EXISTS admin_role_permissions (
    role_id int(11) NOT NULL,
    permission_id int(11) NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    CONSTRAINT fk_admin_role_permissions_role
        FOREIGN KEY (role_id) REFERENCES admin_roles (role_id) ON DELETE CASCADE,
    CONSTRAINT fk_admin_role_permissions_permission
        FOREIGN KEY (permission_id) REFERENCES admin_permissions (permission_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Service-scoped request and document permissions.
CREATE TABLE IF NOT EXISTS admin_role_service_permissions (
    role_id int(11) NOT NULL,
    permission_id int(11) NOT NULL,
    service_id int(11) NOT NULL,
    PRIMARY KEY (role_id, permission_id, service_id),
    KEY idx_admin_role_service_permissions_service (service_id),
    CONSTRAINT fk_admin_role_service_permissions_role
        FOREIGN KEY (role_id) REFERENCES admin_roles (role_id) ON DELETE CASCADE,
    CONSTRAINT fk_admin_role_service_permissions_permission
        FOREIGN KEY (permission_id) REFERENCES admin_permissions (permission_id) ON DELETE CASCADE,
    CONSTRAINT fk_admin_role_service_permissions_service
        FOREIGN KEY (service_id) REFERENCES services (service_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Community reports can be routed to one or more department roles.
CREATE TABLE IF NOT EXISTS community_report_assignments (
    assignment_id int(11) NOT NULL AUTO_INCREMENT,
    report_id int(11) NOT NULL,
    role_id int(11) NOT NULL,
    service_id int(11) DEFAULT NULL,
    assigned_by int(11) NOT NULL,
    assigned_at timestamp NOT NULL DEFAULT current_timestamp(),
    assignment_note varchar(500) DEFAULT NULL,
    PRIMARY KEY (assignment_id),
    UNIQUE KEY uq_community_report_role (report_id, role_id),
    KEY idx_community_report_assignments_role (role_id),
    KEY idx_community_report_assignments_service (service_id),
    CONSTRAINT fk_community_report_assignments_report
        FOREIGN KEY (report_id) REFERENCES community_reports (report_id) ON DELETE CASCADE,
    CONSTRAINT fk_community_report_assignments_role
        FOREIGN KEY (role_id) REFERENCES admin_roles (role_id) ON DELETE CASCADE,
    CONSTRAINT fk_community_report_assignments_service
        FOREIGN KEY (service_id) REFERENCES services (service_id) ON DELETE SET NULL,
    CONSTRAINT fk_community_report_assignments_grantor
        FOREIGN KEY (assigned_by) REFERENCES accounts (account_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Audit role grants and revocations made by a Master Admin.
CREATE TABLE IF NOT EXISTS admin_authorization_audit (
    audit_id bigint(20) NOT NULL AUTO_INCREMENT,
    actor_account_id int(11) DEFAULT NULL,
    target_account_id int(11) NOT NULL,
    role_id int(11) NOT NULL,
    action enum('role_assigned','role_revoked') NOT NULL,
    changed_at timestamp NOT NULL DEFAULT current_timestamp(),
    note varchar(500) DEFAULT NULL,
    PRIMARY KEY (audit_id),
    KEY idx_admin_authorization_audit_target (target_account_id, changed_at),
    KEY idx_admin_authorization_audit_actor (actor_account_id, changed_at),
    CONSTRAINT fk_admin_authorization_audit_actor
        FOREIGN KEY (actor_account_id) REFERENCES accounts (account_id) ON DELETE SET NULL,
    CONSTRAINT fk_admin_authorization_audit_target
        FOREIGN KEY (target_account_id) REFERENCES accounts (account_id),
    CONSTRAINT fk_admin_authorization_audit_role
        FOREIGN KEY (role_id) REFERENCES admin_roles (role_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO admin_roles (role_code, role_name, role_description) VALUES
    ('master_admin', 'Master Admin', 'Manages admin role assignments and authorizations.'),
    ('main_website_admin', 'Main Website Admin', 'Manages shared website administration modules.'),
    ('municipal_assessor', 'Municipal Assessor''s Office', 'Access limited to assigned property tax and tax declaration requests.'),
    ('municipal_treasurer', 'Municipal Treasurer''s Office', 'Access limited to assigned treasurer service requests.'),
    ('municipal_engineering', 'Municipal Engineering Office', 'Access limited to building and occupancy permit requests.'),
    ('municipal_social_welfare', 'Municipal Social Welfare and Development Office', 'Access limited to assigned social assistance requests.'),
    ('municipal_planning', 'Municipal Planning and Development Office', 'Access limited to locational and zoning clearance requests.'),
    ('municipal_health', 'Municipal Health Office', 'Access limited to health certificate requests.')
ON DUPLICATE KEY UPDATE
    role_name = VALUES(role_name),
    role_description = VALUES(role_description);

INSERT INTO admin_permissions (permission_code, permission_description) VALUES
    ('dashboard.view', 'View the admin dashboard.'),
    ('news.manage', 'Create and manage news.'),
    ('services.manage', 'Manage municipal services.'),
    ('hotlines.manage', 'Manage emergency hotlines.'),
    ('users.manage', 'Manage registered user accounts.'),
    ('community_reports.view_all', 'View all community reports.'),
    ('community_reports.manage', 'Manage community reports.'),
    ('community_reports.route', 'Assign community reports to departments or services.'),
    ('community_reports.view_assigned', 'View community reports assigned to the admin''s department.'),
    ('service_requests.view', 'View service requests in the permitted service scope.'),
    ('service_requests.update', 'Update service requests in the permitted service scope.'),
    ('documents.review', 'Review documents in the permitted service scope.'),
    ('authorization.manage', 'Grant and revoke admin role assignments.')
ON DUPLICATE KEY UPDATE
    permission_description = VALUES(permission_description);

-- Shared modules for the Main Website Admin.
-- The Master Admin can manage authorizations but receives no service-record
-- access unless another role is assigned.
INSERT INTO admin_role_permissions (role_id, permission_id)
SELECT r.role_id, p.permission_id
FROM admin_roles AS r
JOIN admin_permissions AS p
WHERE
    (r.role_code = 'main_website_admin' AND p.permission_code IN (
        'dashboard.view',
        'news.manage',
        'services.manage',
        'hotlines.manage',
        'users.manage',
        'community_reports.view_all',
        'community_reports.manage',
        'community_reports.route'
    ))
    OR
    (r.role_code = 'master_admin' AND p.permission_code IN (
        'dashboard.view',
        'authorization.manage'
    ))
ON DUPLICATE KEY UPDATE role_id = VALUES(role_id);

-- Department admins may view reports routed to their assigned department.
INSERT INTO admin_role_permissions (role_id, permission_id)
SELECT r.role_id, p.permission_id
FROM admin_roles AS r
JOIN admin_permissions AS p
WHERE r.role_code IN (
    'municipal_assessor',
    'municipal_treasurer',
    'municipal_engineering',
    'municipal_social_welfare',
    'municipal_planning',
    'municipal_health'
)
AND p.permission_code = 'community_reports.view_assigned'
ON DUPLICATE KEY UPDATE role_id = VALUES(role_id);

-- These service codes must exist in the services table for their scopes to be
-- granted. Review this result before applying service-scoped grants; it should
-- return no rows.
SELECT expected.service_code AS missing_service_code
FROM (
    SELECT 'real-property-tax' AS service_code
    UNION ALL SELECT 'business-permit'
    UNION ALL SELECT 'community-tax-certificate'
    UNION ALL SELECT 'building-permit'
    UNION ALL SELECT 'occupancy-permit'
    UNION ALL SELECT 'burial-assistance'
    UNION ALL SELECT 'senior-pwd-assistance'
    UNION ALL SELECT 'social-assistance'
    UNION ALL SELECT 'zoning-clearance'
    UNION ALL SELECT 'health-certificate'
) AS expected
LEFT JOIN services AS s ON s.code = expected.service_code
WHERE s.service_id IS NULL;

-- Department service scopes from the authorization matrix.
INSERT INTO admin_role_service_permissions (role_id, permission_id, service_id)
SELECT r.role_id, p.permission_id, s.service_id
FROM (
    SELECT 'municipal_assessor' AS role_code, 'real-property-tax' AS service_code
    UNION ALL SELECT 'municipal_treasurer', 'business-permit'
    UNION ALL SELECT 'municipal_treasurer', 'community-tax-certificate'
    UNION ALL SELECT 'municipal_treasurer', 'real-property-tax'
    UNION ALL SELECT 'municipal_engineering', 'building-permit'
    UNION ALL SELECT 'municipal_engineering', 'occupancy-permit'
    UNION ALL SELECT 'municipal_social_welfare', 'burial-assistance'
    UNION ALL SELECT 'municipal_social_welfare', 'senior-pwd-assistance'
    UNION ALL SELECT 'municipal_social_welfare', 'social-assistance'
    UNION ALL SELECT 'municipal_planning', 'zoning-clearance'
    UNION ALL SELECT 'municipal_health', 'health-certificate'
) AS service_scope_map
JOIN admin_roles AS r ON r.role_code = service_scope_map.role_code
JOIN services AS s ON s.code = service_scope_map.service_code
JOIN admin_permissions AS p ON p.permission_code IN (
    'service_requests.view',
    'service_requests.update',
    'documents.review'
)
ON DUPLICATE KEY UPDATE role_id = VALUES(role_id);

-- Assign the Master Admin role only after selecting the intended active admin.
-- Replace the email below, then execute this statement:
--
-- INSERT INTO admin_account_roles (account_id, role_id, granted_by)
-- SELECT target.account_id, r.role_id, target.account_id
-- FROM accounts AS target
-- JOIN admin_roles AS r ON r.role_code = 'master_admin'
-- WHERE target.email = 'REPLACE_WITH_MASTER_ADMIN_EMAIL'
--   AND target.roles = 'admin'
--   AND target.status = 'active';
--
-- Account-role changes made by the Authorization Management page should also
-- be written to admin_authorization_audit. Only active admin accounts should
-- receive admin roles; enforce this in the application when granting access.
