<?php

$pageTitle = 'Gestión de Solicitudes';

// Datos de ejemplo para mostrar en la tabla
$solicitudes = [
    ['id' => 1, 'nombre_solicitante' => 'Ana García',    'tipo_solicitud' => 'certificado',        'estado' => 'pendiente',   'fecha_creacion' => '2025-04-10 09:00:00'],
    ['id' => 2, 'nombre_solicitante' => 'Carlos Muñoz',  'tipo_solicitud' => 'academica',           'estado' => 'en_revision', 'fecha_creacion' => '2025-04-11 10:30:00'],
    ['id' => 3, 'nombre_solicitante' => 'María López',   'tipo_solicitud' => 'actualizacion_datos', 'estado' => 'aprobada',    'fecha_creacion' => '2025-04-12 14:15:00'],
    ['id' => 4, 'nombre_solicitante' => 'Juan Pérez',    'tipo_solicitud' => 'otra',                'estado' => 'rechazada',   'fecha_creacion' => '2025-04-13 08:45:00'],
];

// Capturar el contenido de la vista en un buffer
ob_start();
require __DIR__ . '/views/request/index.php';
$content = ob_get_clean();

// Renderizar el layout con el contenido inyectado
require __DIR__ . '/views/layouts/main.php';
