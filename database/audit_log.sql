-- Adds an audit_log table to track state-changing actions across the app.
-- Run this once against an existing schoolproject database:
--   mysql -u root -p schoolproject < database/audit_log.sql

USE schoolproject;

CREATE TABLE IF NOT EXISTS audit_log (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    actor_id      INT NULL,              -- id of the user who performed the action (NULL if unknown/system)
    actor_username VARCHAR(100) NULL,    -- snapshot of the username at the time of the action
    action        VARCHAR(50) NOT NULL,  -- e.g. 'create', 'update', 'delete', 'login', 'login_failed'
    entity_type   VARCHAR(50) NOT NULL,  -- e.g. 'student', 'teacher', 'user'
    entity_id     INT NULL,              -- id of the row affected, if applicable
    details       TEXT NULL,             -- human-readable summary of what changed
    ip_address    VARCHAR(45) NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_entity (entity_type, entity_id),
    INDEX idx_actor (actor_id),
    INDEX idx_created_at (created_at)
);
