CREATE TABLE IF NOT EXISTS audit_history (
    history_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    actor_account_id int(11) DEFAULT NULL,
    actor_role varchar(20) NOT NULL,
    event_type varchar(64) NOT NULL,
    entity_type varchar(64) NOT NULL,
    entity_id varchar(64) NOT NULL,
    details JSON DEFAULT NULL,
    created_at datetime NOT NULL,
    PRIMARY KEY (history_id),
    KEY actor_created_at (actor_account_id, created_at),
    KEY entity_created_at (entity_type, entity_id, created_at),
    KEY event_created_at (event_type, created_at),
    CONSTRAINT audit_history_actor_fk
        FOREIGN KEY (actor_account_id) REFERENCES accounts (account_id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
