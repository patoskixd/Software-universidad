<?php

$pageTitle = 'Gestión de Solicitudes';

// Capturar el contenido de la vista en un buffer
ob_start();
require __DIR__ . '/views/request/index.php';
$content = ob_get_clean();

// Renderizar el layout con el contenido inyectado
require __DIR__ . '/views/layouts/main.php';
