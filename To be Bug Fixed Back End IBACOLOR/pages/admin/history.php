<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/page-auth.php';

$activityStatement = $iBacolorConnection->query(
    'SELECT h.history_id AS source_id, h.actor_role, h.event_type, h.entity_type,
            h.entity_id, h.details, h.created_at, a.email AS actor_email,
            \'Activity\' AS source
     FROM audit_history AS h
     LEFT JOIN accounts AS a ON a.account_id = h.actor_account_id
     ORDER BY h.created_at DESC, h.history_id DESC
     LIMIT 100'
);
$authorizationStatement = $iBacolorConnection->query(
    "SELECT h.audit_id AS source_id, 'admin' AS actor_role, h.action AS event_type,
            'Admin role' AS entity_type,
            CONCAT(target.email, ' / ', r.role_name) AS entity_id,
            h.note AS details, h.changed_at AS created_at, actor.email AS actor_email,
            'Authorization' AS source
     FROM admin_authorization_audit AS h
     JOIN accounts AS target ON target.account_id = h.target_account_id
     JOIN admin_roles AS r ON r.role_id = h.role_id
     LEFT JOIN accounts AS actor ON actor.account_id = h.actor_account_id
     ORDER BY h.changed_at DESC, h.audit_id DESC
     LIMIT 100"
);

$history = array_merge(
    $activityStatement->fetchAll(PDO::FETCH_ASSOC),
    $authorizationStatement->fetchAll(PDO::FETCH_ASSOC)
);
usort($history, static function (array $left, array $right): int {
    $dateOrder = strcmp((string) $right['created_at'], (string) $left['created_at']);
    return $dateOrder !== 0
        ? $dateOrder
        : ((int) $right['source_id'] <=> (int) $left['source_id']);
});
$history = array_slice($history, 0, 100);
$escape = static fn (mixed $value): string => htmlspecialchars(
    (string) $value,
    ENT_QUOTES | ENT_SUBSTITUTE,
    'UTF-8'
);
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>History Logs | iBacolor</title>
        <link rel="stylesheet" href="../../css/pages/admin-history.css?v=admin-history-1" />
    </head>
    <body data-page="admin/history" class="admin-view">
        <div id="app">
            <aside class="admin-sidebar" id="admin-sidebar" aria-label="Admin menu" aria-hidden="true" hidden>
                <div class="admin-sidebar-header">
                    <button class="admin-menu-toggle admin-menu-close" type="button" aria-label="Close admin menu" aria-expanded="false" aria-controls="admin-sidebar"><span class="menu-icon" aria-hidden="true"><span></span><span></span><span></span></span></button>
                    <a class="admin-sidebar-brand" href="../admin/dashboard.php"><img class="brand-logo" src="../../assets/bacolor-seal.webp" alt="" /><span><strong>iBacolor</strong><small>Admin portal</small></span></a>
                </div>
                <p class="admin-label">Workspace</p>
                <a href="../admin/dashboard.php"><?= adminSidebarIcon('dashboard') ?>Dashboard</a><a href="../admin/requests.php"><?= adminSidebarIcon('clipboard-list') ?>Service requests</a><a href="../admin/documents.php"><?= adminSidebarIcon('file-text') ?>Documents</a><a href="../admin/reports.php"><?= adminSidebarIcon('alert-circle') ?>Community reports</a>
                <p class="admin-label">Content</p>
                <a href="../admin/news.php"><?= adminSidebarIcon('newspaper') ?>News</a><a href="../admin/transparency-documents.php"><?= adminSidebarIcon('file-search') ?>Transparency Documents</a><a href="../admin/services.php"><?= adminSidebarIcon('briefcase') ?>Services</a><a href="../admin/hotlines.php"><?= adminSidebarIcon('phone') ?>Hotlines</a><a href="../admin/users.php"><?= adminSidebarIcon('users') ?>Users</a>
                <p class="admin-label">System</p>
                <a href="../admin/history.php" aria-current="page"><?= adminSidebarIcon('history') ?>History logs</a>
                <?php if (adminIsMasterAdmin($iBacolorConnection, (int) $accountId)): ?><a href="../admin/authorization.php"><?= adminSidebarIcon('shield-check') ?>Admin access</a><?php endif; ?>
                <div class="admin-sidebar-footer">
                    <a href="../logout.php"><?= adminSidebarIcon('logout') ?>Sign out</a>
                </div>
            </aside>
            <?php require __DIR__ . '/../../includes/site-header.php'; ?>
            <main>
                <section class="page-header">
                    <div class="container">
                        <div class="breadcrumbs">Administration <span>/</span>History logs</div>
                        <p class="eyebrow">Administration</p>
                        <h1>History logs</h1>
                        <p class="lead">Review recent admin activity and authorization changes.</p>
                    </div>
                </section>
                <section class="section">
                    <div class="container">
                        <article class="card history-card">
                            <div class="section-heading compact">
                                <div>
                                    <p class="eyebrow">Audit trail</p>
                                    <h2>Latest activity</h2>
                                </div>
                                <span class="history-count"><?= count($history) ?> records</span>
                            </div>
                            <?php if ($history === []): ?>
                                <p class="history-empty">No history has been recorded yet.</p>
                            <?php else: ?>
                                <div class="table-wrap">
                                    <table class="history-table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Date and time</th>
                                                <th scope="col">Actor</th>
                                                <th scope="col">Event</th>
                                                <th scope="col">Record</th>
                                                <th scope="col">Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($history as $row): ?>
                                                <?php
                                                $detailsText = (string) ($row['details'] ?? '');
                                                if ($row['source'] === 'Activity' && $detailsText !== '') {
                                                    $decodedDetails = json_decode($detailsText, true);
                                                    if (json_last_error() === JSON_ERROR_NONE) {
                                                        $prettyDetails = json_encode(
                                                            $decodedDetails,
                                                            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                                                        );
                                                        if ($prettyDetails !== false) {
                                                            $detailsText = $prettyDetails;
                                                        }
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td><?= $escape($row['created_at']) ?></td>
                                                    <td>
                                                        <strong><?= $escape($row['actor_email'] ?? 'System') ?></strong>
                                                        <small><?= $escape($row['actor_role']) ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="history-source"><?= $escape($row['source']) ?></span>
                                                        <strong><?= $escape(ucfirst(str_replace(['_', '.'], ' ', (string) $row['event_type']))) ?></strong>
                                                    </td>
                                                    <td>
                                                        <strong><?= $escape($row['entity_type']) ?></strong>
                                                        <small><?= $escape($row['entity_id']) ?></small>
                                                    </td>
                                                    <td>
                                                        <?php if ($detailsText !== ''): ?>
                                                            <details class="history-details">
                                                                <summary>View details</summary>
                                                                <pre><?= $escape($detailsText) ?></pre>
                                                            </details>
                                                        <?php else: ?>
                                                            <span class="history-muted">—</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </article>
                    </div>
                </section>
            </main>
            <footer class="site-footer">
                <div class="container footer-grid">
                    <div>
                        <div class="brand brand-footer"><img class="brand-logo" src="../../assets/bacolor-seal.webp" alt="" /><span><strong>iBacolor</strong><small>Administration portal</small></span></div>
                        <p>Administrative workspace for municipal operations.</p>
                    </div>
                    <div>
                        <h3>Explore</h3>
                        <a href="../services.php">Services</a>
                        <a href="../news.php">News and announcements</a>
                        <a href="../hotlines.php">Emergency hotlines</a>
                        <a href="../transparency.php">Transparency</a>
                        <a href="../resident/reports.php">Community Report</a>
                    </div>
                    <div>
                        <h3>Need assistance?</h3>
                        <p>Municipal Hall<br />(045) 900-1000<br />Mon–Fri, 8:00 AM–5:00 PM</p>
                    </div>
                </div>
                <div class="container footer-bottom">
                    <span>© 2026 Municipality of Bacolor</span>
                </div>
            </footer>
        </div>
        <script src="../../js/portal.js?v=admin-sidebar-6"></script>
    </body>
</html>
