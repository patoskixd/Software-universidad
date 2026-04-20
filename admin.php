<?php

require_once __DIR__ . '/app/core/Auth.php';

Auth::requireWeb();

$pageTitle   = 'Gestión de Solicitudes';
$pageScript  = 'assets/js/main.js';
$adminActual = Auth::user();

ob_start();
require __DIR__ . '/views/request/index.php';
$content = ob_get_clean();

require __DIR__ . '/views/layouts/main.php';
