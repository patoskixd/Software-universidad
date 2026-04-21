-- Migración 4 - Agregar columna de observaciones 

ALTER TABLE solicitudes ADD COLUMN observaciones TEXT NULL AFTER estado;
