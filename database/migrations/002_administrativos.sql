
-- Migracion 002 – Administrativos 

USE sistema_universidad;

CREATE TABLE IF NOT EXISTS administrativos (
    id             INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    nombre         VARCHAR(150)    NOT NULL,
    correo         VARCHAR(150)    NOT NULL UNIQUE,
    password_hash  VARCHAR(255)    NOT NULL,
    fecha_creacion DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO administrativos (nombre, correo, password_hash) VALUES
    ('Administrador', 'admin@universidad.cl', '$2y$10$TkJSZXf1QyszQQ19c9moO.jFgYdnUCc.Xm.aAElnO7hpJnzFP0mGu');
