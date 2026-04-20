<?php

require_once __DIR__ . '/app/core/Auth.php';

Auth::start();

$pageTitle   = 'Nueva solicitud';
$pageScripts = ['assets/js/utils.js', 'assets/js/public.js'];
$adminActual = Auth::user();

ob_start();
require __DIR__ . '/views/public/index.php';
$content = ob_get_clean();

require __DIR__ . '/views/layouts/main.php';
