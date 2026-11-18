<?php
declare(strict_types=1);

function recordAdminAuditHistory(
    PDO $connection,
    ?int $actorAccountId,
    string $actorRole,
    string $eventType,
    string $entityType,
    int|string $entityId,
    array $details = []
): void {
    $detailsJson = json_encode(
        $details,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
    );
    $statement = $connection->prepare(
        'INSERT INTO audit_history
            (actor_account_id, actor_role, event_type, entity_type, entity_id, details, created_at)
         VALUES
            (:actor_account_id, :actor_role, :event_type, :entity_type, :entity_id, :details, UTC_TIMESTAMP())'
    );
    $statement->execute([
        'actor_account_id' => $actorAccountId,
        'actor_role' => $actorRole,
        'event_type' => $eventType,
        'entity_type' => $entityType,
        'entity_id' => (string) $entityId,
        'details' => $detailsJson,
    ]);
}
