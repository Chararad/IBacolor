CREATE TABLE IF NOT EXISTS request_appointments (
    appointment_id int(11) NOT NULL AUTO_INCREMENT,
    request_id int(11) NOT NULL,
    changed_by int(11) NOT NULL,
    status enum('scheduled','rescheduled','cancelled','completed') NOT NULL DEFAULT 'scheduled',
    appointment_date date NOT NULL,
    appointment_time time NOT NULL,
    office varchar(255) NOT NULL,
    location varchar(255) NOT NULL,
    instructions text DEFAULT NULL,
    created_at timestamp NOT NULL DEFAULT current_timestamp(),
    updated_at timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (appointment_id),
    KEY request_id (request_id),
    KEY changed_by (changed_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS news_articles (
    article_id int(11) NOT NULL AUTO_INCREMENT,
    title varchar(255) NOT NULL,
    content longtext NOT NULL,
    category varchar(100) NOT NULL,
    published_at datetime DEFAULT NULL,
    status enum('draft','published') NOT NULL DEFAULT 'draft',
    image_storage_key char(48) DEFAULT NULL,
    image_mime_type varchar(127) DEFAULT NULL,
    created_by int(11) NOT NULL,
    created_at timestamp NOT NULL DEFAULT current_timestamp(),
    updated_at timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (article_id),
    KEY status_published_at (status, published_at),
    KEY created_by (created_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS resident_verification_history (
    history_id int(11) NOT NULL AUTO_INCREMENT,
    account_id int(11) NOT NULL,
    changed_by int(11) NOT NULL,
    status enum('pending','verified','rejected') NOT NULL,
    note text DEFAULT NULL,
    changed_at timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (history_id),
    KEY account_id (account_id),
    KEY changed_by (changed_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS resident_verification_documents (
    document_id int(11) NOT NULL AUTO_INCREMENT,
    account_id int(11) NOT NULL,
    uploaded_by int(11) NOT NULL,
    file_name varchar(255) NOT NULL,
    storage_key char(48) NOT NULL,
    mime_type varchar(127) NOT NULL,
    uploaded_at timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (document_id),
    UNIQUE KEY storage_key (storage_key),
    KEY account_id (account_id),
    KEY uploaded_by (uploaded_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
