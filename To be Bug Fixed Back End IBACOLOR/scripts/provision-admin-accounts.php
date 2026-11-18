<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/../includes/connection.php';
require_once __DIR__ . '/../includes/audit-history.php';

$accounts = [
    ['main.admin@gmail.com', ['main_website_admin'], 'IBACOLOR_ADMIN_MAIN_PASSWORD'],
    ['assessor.admin@gmail.com', ['municipal_assessor'], 'IBACOLOR_ADMIN_ASSESSOR_PASSWORD'],
    ['treasurer.admin@gmail.com', ['municipal_treasurer'], 'IBACOLOR_ADMIN_TREASURER_PASSWORD'],
    ['engineering.admin@gmail.com', ['municipal_engineering'], 'IBACOLOR_ADMIN_ENGINEERING_PASSWORD'],
    ['swdo.admin@gmail.com', ['municipal_social_welfare'], 'IBACOLOR_ADMIN_SWDO_PASSWORD'],
    ['PandD.admin@gmail.com', ['municipal_planning'], 'IBACOLOR_ADMIN_PLANNING_PASSWORD'],
    ['health.admin@gmail.com', ['municipal_health'], 'IBACOLOR_ADMIN_HEALTH_PASSWORD'],
];

$missingVariables = [];
foreach ($accounts as [$email, $roleCodes, $variable]) {
    $password = getenv($variable);
    if (!is_string($password) || $password === '') {
        $missingVariables[] = $variable;
        continue;
    }
    if (strlen($password) < 12) {
        fwrite(STDERR, "Password from {$variable} must be at least 12 characters.\n");
        exit(1);
    }
    unset($password);
}

if ($missingVariables) {
    fwrite(STDERR, 'Set all required password environment variables: ' . implode(', ', $missingVariables) . "\n");
    exit(1);
}

try {
    $iBacolorConnection->beginTransaction();
    $findAccount = $iBacolorConnection->prepare(
        'SELECT account_id, roles FROM accounts WHERE email = :email LIMIT 1 FOR UPDATE'
    );
    $insertAccount = $iBacolorConnection->prepare(
        "INSERT INTO accounts (email, password_hash, roles, status)
         VALUES (:email, :password_hash, 'admin', 'active')"
    );
    $updateAccount = $iBacolorConnection->prepare(
        "UPDATE accounts
         SET password_hash = :password_hash, status = 'active'
         WHERE account_id = :account_id"
    );
    $findRole = $iBacolorConnection->prepare(
        'SELECT role_id FROM admin_roles WHERE role_code = :role_code LIMIT 1'
    );
    $findAssignment = $iBacolorConnection->prepare(
        'SELECT 1 FROM admin_account_roles WHERE account_id = :account_id AND role_id = :role_id LIMIT 1'
    );
    $assignRole = $iBacolorConnection->prepare(
        'INSERT INTO admin_account_roles (account_id, role_id) VALUES (:account_id, :role_id)'
    );
    $recordRoleAssignment = $iBacolorConnection->prepare(
        "INSERT INTO admin_authorization_audit
            (actor_account_id, target_account_id, role_id, action, note)
         VALUES (NULL, :target_account_id, :role_id, 'role_assigned', 'Initial account provisioning')"
    );

    foreach ($accounts as [$email, $roleCodes, $variable]) {
        $password = getenv($variable);
        if (!is_string($password) || $password === '') {
            throw new RuntimeException("Password environment variable {$variable} is unavailable.");
        }
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        if ($passwordHash === false) {
            throw new RuntimeException('An account password could not be hashed.');
        }

        $findAccount->execute(['email' => $email]);
        $existing = $findAccount->fetch(PDO::FETCH_ASSOC);
        if ($existing && $existing['roles'] !== 'admin') {
            throw new RuntimeException("Refusing to change the role of the existing account {$email}.");
        }

        if ($existing) {
            $accountId = (int) $existing['account_id'];
            $updateAccount->execute([
                'password_hash' => $passwordHash,
                'account_id' => $accountId,
            ]);
        } else {
            $insertAccount->execute([
                'email' => $email,
                'password_hash' => $passwordHash,
            ]);
            $accountId = (int) $iBacolorConnection->lastInsertId();
        }

        foreach ($roleCodes as $roleCode) {
            $findRole->execute(['role_code' => $roleCode]);
            $roleId = $findRole->fetchColumn();
            if ($roleId === false) {
                throw new RuntimeException("Authorization role {$roleCode} is missing; apply Authorization-Table.sql first.");
            }
            $roleId = (int) $roleId;

            $findAssignment->execute(['account_id' => $accountId, 'role_id' => $roleId]);
            if ($findAssignment->fetchColumn() === false) {
                $assignRole->execute(['account_id' => $accountId, 'role_id' => $roleId]);
                $recordRoleAssignment->execute([
                    'target_account_id' => $accountId,
                    'role_id' => $roleId,
                ]);
            }
        }

        recordAdminAuditHistory(
            $iBacolorConnection,
            null,
            'system',
            $existing ? 'updated' : 'created',
            'account',
            $accountId,
            ['role' => 'admin', 'authorization_roles' => $roleCodes]
        );
        unset($password, $passwordHash);
    }

    $iBacolorConnection->commit();
    fwrite(STDOUT, 'Provisioned ' . count($accounts) . " administrator accounts.\n");
} catch (Throwable $exception) {
    if ($iBacolorConnection->inTransaction()) {
        $iBacolorConnection->rollBack();
    }
    fwrite(STDERR, 'Administrator provisioning failed: ' . $exception->getMessage() . "\n");
    exit(1);
}
