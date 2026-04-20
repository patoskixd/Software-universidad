
-- Migracion 001 – Esquema inicial

CREATE DATABASE IF NOT EXISTS sistema_universidad
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE sistema_universidad;

-- Tabla de solicitudes

CREATE TABLE IF NOT EXISTS solicitudes (
    id                  INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    nombre_solicitante  VARCHAR(150)    NOT NULL,
    correo_electronico  VARCHAR(150)    NOT NULL,
    tipo_solicitud      ENUM(
                            'academica',
                            'certificado',
                            'actualizacion_datos',
                            'otra'
                        )               NOT NULL,
    descripcion         TEXT            NOT NULL,
    estado              ENUM(
                            'pendiente',
                            'en_revision',
                            'aprobada',
                            'rechazada'
                        )               NOT NULL DEFAULT 'pendiente',
    fecha_creacion      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
                                        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    INDEX idx_estado          (estado),
    INDEX idx_tipo_solicitud  (tipo_solicitud),
    INDEX idx_fecha_creacion  (fecha_creacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Datos de ejemplo para demostracion

INSERT INTO solicitudes
    (nombre_solicitante, correo_electronico, tipo_solicitud, descripcion, estado)
VALUES
    ('Juan Pérez',      'juan.perez@universidad.cl',      'certificado',          'Solicito certificado de alumno regular para trámite bancario.',                    'pendiente'),
    ('María González',  'maria.gonzalez@universidad.cl',  'academica',            'Necesito convalidación de asignatura cursada en programa de intercambio.',         'en_revision'),
    ('Carlos López',    'carlos.lopez@universidad.cl',    'actualizacion_datos',  'Actualización de dirección postal y teléfono de contacto en el sistema.',          'aprobada'),
    ('Ana Martínez',    'ana.martinez@universidad.cl',    'otra',                 'Consulta sobre disponibilidad de beca de alimentación para el segundo semestre.',  'rechazada'),
    ('Pedro Soto',      'pedro.soto@universidad.cl',      'certificado',          'Certificado de título para apostillar y presentar en universidad extranjera.',     'pendiente'),
    ('Valentina Ruiz',  'valentina.ruiz@universidad.cl',  'academica',            'Solicitud de cambio de carrera al programa de Ingeniería Civil Industrial.',       'pendiente');
