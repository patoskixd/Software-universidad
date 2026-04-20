<?php

require_once __DIR__ . '/app/core/Auth.php';
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/Csrf.php';
require_once __DIR__ . '/app/core/RateLimit.php';
require_once __DIR__ . '/app/models/Administrativo.php';

Auth::start();

if (Auth::check()) {
    header('Location: admin.php');
    exit;
}

$error           = null;
$correoIngresado = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Csrf::validateForm();

    $ip = RateLimit::clientIp();

    try {
        $pdo = Database::getInstance();

        if (!RateLimit::check($pdo, $ip)) {
            $error = 'Demasiados intentos fallidos. Espere 10 minutos e intente nuevamente.';
        } else {
            $correoIngresado = trim($_POST['correo'] ?? '');
            $password        = $_POST['password'] ?? '';

            if ($correoIngresado === '' || $password === '') {
                $error = 'Debe completar ambos campos.';
            } else {
                $model = new Administrativo($pdo);
                $admin = $model->verificarCredenciales($correoIngresado, $password);

                if ($admin === null) {
                    RateLimit::record($pdo, $ip);
                    $error = 'Credenciales inválidas.';
                } else {
                    Auth::login($admin);
                    header('Location: admin.php');
                    exit;
                }
            }
        }
    } catch (PDOException $e) {
        error_log('[login] ' . $e->getMessage());
        $error = 'Error interno. Intente más tarde.';
    }
}

$pageTitle = 'Iniciar sesión';

ob_start();
require __DIR__ . '/views/auth/login.php';
$content = ob_get_clean();

require __DIR__ . '/views/layouts/main.php';
