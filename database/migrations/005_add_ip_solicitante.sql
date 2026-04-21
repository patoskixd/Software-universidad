-- Migracion 5 - Agregar IP del solicitante

ALTER TABLE solicitudes
    ADD COLUMN ip_solicitante VARCHAR(45) NULL AFTER descripcion;
