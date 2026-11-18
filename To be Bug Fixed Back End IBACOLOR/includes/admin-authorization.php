<?php
declare(strict_types=1);

function adminAccountHasRole(PDO $connection, int $accountId, string $roleCode): bool
{
    $statement = $connection->prepare(
        "SELECT 1
         FROM accounts AS a
         JOIN admin_account_roles AS aar ON aar.account_id = a.account_id
         JOIN admin_roles AS r ON r.role_id = aar.role_id
         WHERE a.account_id = :account_id
           AND a.roles = 'admin'
           AND a.status = 'active'
           AND r.role_code = :role_code
         LIMIT 1"
    );
    $statement->execute(['account_id' => $accountId, 'role_code' => $roleCode]);
    return $statement->fetchColumn() !== false;
}

function adminIsMasterAdmin(PDO $connection, int $accountId): bool
{
    $statement = $connection->prepare(
        "SELECT 1
         FROM accounts AS a
         JOIN admin_account_roles AS aar ON aar.account_id = a.account_id
         JOIN admin_roles AS r ON r.role_id = aar.role_id
         WHERE a.account_id = :account_id
           AND a.roles = 'admin'
           AND a.status = 'active'
           AND r.role_code = 'master_admin'
         LIMIT 1"
    );
    $statement->execute(['account_id' => $accountId]);
    return $statement->fetchColumn() !== false;
}

function adminHasPermission(PDO $connection, int $accountId, string $permissionCode): bool
{
    if (adminIsMasterAdmin($connection, $accountId)) {
        return true;
    }

    $statement = $connection->prepare(
        'SELECT 1
         FROM admin_account_roles AS aar
         JOIN admin_role_permissions AS rp ON rp.role_id = aar.role_id
         JOIN admin_permissions AS p ON p.permission_id = rp.permission_id
         WHERE aar.account_id = :account_id
           AND p.permission_code = :permission_code
         LIMIT 1'
    );
    $statement->execute(['account_id' => $accountId, 'permission_code' => $permissionCode]);
    return $statement->fetchColumn() !== false;
}

function adminHasServicePermission(
    PDO $connection,
    int $accountId,
    string $permissionCode,
    int $serviceId
): bool {
    if (adminHasPermission($connection, $accountId, $permissionCode)) {
        return true;
    }

    $statement = $connection->prepare(
        'SELECT 1
         FROM admin_account_roles AS aar
         JOIN admin_role_service_permissions AS rsp ON rsp.role_id = aar.role_id
         JOIN admin_permissions AS p ON p.permission_id = rsp.permission_id
         WHERE aar.account_id = :account_id
           AND rsp.service_id = :service_id
           AND p.permission_code = :permission_code
         LIMIT 1'
    );
    $statement->execute([
        'account_id' => $accountId,
        'service_id' => $serviceId,
        'permission_code' => $permissionCode,
    ]);
    return $statement->fetchColumn() !== false;
}

function adminServiceIds(PDO $connection, int $accountId, string $permissionCode): array
{
    if (adminHasPermission($connection, $accountId, $permissionCode)) {
        return array_map(
            'intval',
            $connection->query('SELECT service_id FROM services')->fetchAll(PDO::FETCH_COLUMN)
        );
    }

    $statement = $connection->prepare(
        'SELECT DISTINCT rsp.service_id
         FROM admin_account_roles AS aar
         JOIN admin_role_service_permissions AS rsp ON rsp.role_id = aar.role_id
         JOIN admin_permissions AS p ON p.permission_id = rsp.permission_id
         WHERE aar.account_id = :account_id
           AND p.permission_code = :permission_code
         ORDER BY rsp.service_id'
    );
    $statement->execute(['account_id' => $accountId, 'permission_code' => $permissionCode]);
    return array_map('intval', $statement->fetchAll(PDO::FETCH_COLUMN));
}

function adminCanViewAnyReport(PDO $connection, int $accountId): bool
{
    if (adminHasPermission($connection, $accountId, 'community_reports.view_all')
        || adminHasPermission($connection, $accountId, 'community_reports.view_assigned')) {
        return true;
    }

    $statement = $connection->prepare(
        "SELECT 1
         FROM community_report_assignments AS cra
         JOIN admin_account_roles AS aar ON aar.role_id = cra.role_id
         JOIN admin_role_permissions AS rp ON rp.role_id = aar.role_id
         JOIN admin_permissions AS p ON p.permission_id = rp.permission_id
         WHERE aar.account_id = :account_id
           AND p.permission_code = 'community_reports.view_assigned'
         LIMIT 1"
    );
    $statement->execute(['account_id' => $accountId]);
    return $statement->fetchColumn() !== false;
}

function adminCanViewReport(PDO $connection, int $accountId, int $reportId): bool
{
    if (adminHasPermission($connection, $accountId, 'community_reports.view_all')) {
        return true;
    }

    $statement = $connection->prepare(
        "SELECT 1
         FROM community_report_assignments AS cra
         JOIN admin_account_roles AS aar ON aar.role_id = cra.role_id
         JOIN admin_role_permissions AS rp ON rp.role_id = aar.role_id
         JOIN admin_permissions AS p ON p.permission_id = rp.permission_id
         WHERE aar.account_id = :account_id
           AND cra.report_id = :report_id
           AND p.permission_code = 'community_reports.view_assigned'
         LIMIT 1"
    );
    $statement->execute(['account_id' => $accountId, 'report_id' => $reportId]);
    return $statement->fetchColumn() !== false;
}

function adminCanOpenPage(PDO $connection, int $accountId, string $page): bool
{
    if (adminIsMasterAdmin($connection, $accountId)) {
        return true;
    }

    $globalPermissions = [
        'dashboard.php' => 'dashboard.view',
        'news.php' => 'news.manage',
        'services.php' => 'services.manage',
        'hotlines.php' => 'hotlines.manage',
        'users.php' => 'users.manage',
        'user-details.php' => 'users.manage',
        'transparency-documents.php' => 'transparency_documents.manage',
    ];

    if ($page === 'authorization.php') {
        return adminIsMasterAdmin($connection, $accountId);
    }
    if ($page === 'history.php') {
        return adminHasPermission($connection, $accountId, 'authorization.manage');
    }
    if ($page === 'reports.php') {
        return adminCanViewAnyReport($connection, $accountId);
    }
    if ($page === 'requests.php' || $page === 'request-details.php') {
        return adminServiceIds($connection, $accountId, 'service_requests.view') !== [];
    }
    if ($page === 'documents.php') {
        return adminServiceIds($connection, $accountId, 'documents.review') !== [];
    }

    $permissionCode = $globalPermissions[$page] ?? null;
    return $permissionCode !== null && adminHasPermission($connection, $accountId, $permissionCode);
}
