<?php

class Auth
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name('SID_UNI');
            session_start();
        }
    }

    public static function login(array $admin): void
    {
        self::start();
        session_regenerate_id(true);
        $_SESSION['admin'] = [
            'id'     => (int) $admin['id'],
            'nombre' => $admin['nombre'],
            'correo' => $admin['correo'],
        ];
    }

    public static function logout(): void
    {
        self::start();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
    }

    public static function check(): bool
    {
        self::start();
        return !empty($_SESSION['admin']['id']);
    }

    public static function user(): ?array
    {
        self::start();
        return $_SESSION['admin'] ?? null;
    }

    public static function requireWeb(string $loginUrl = 'login.php'): void
    {
        if (!self::check()) {
            header('Location: ' . $loginUrl);
            exit;
        }
    }

    public static function requireApi(): void
    {
        if (!self::check()) {
            require_once __DIR__ . '/Response.php';
            Response::error('No autorizado.', 401);
        }
    }
}
