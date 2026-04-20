-- Migracion 003 – Login intentos para rate limiting


CREATE TABLE IF NOT EXISTS login_attempts (
    id           INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    ip           VARCHAR(45)     NOT NULL,
    attempted_at DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY  (id),
    INDEX        idx_ip_at (ip, attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
