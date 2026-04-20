<?php

class Csrf
{
    private const KEY = '_csrf_token';

    public static function token(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION[self::KEY])) {
            $_SESSION[self::KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::KEY];
    }

    // hash_equals evita timing attacks (=== se detiene en el primer caracter distinto)
    public static function validate(string $token): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $stored = $_SESSION[self::KEY] ?? '';
        return $stored !== '' && hash_equals($stored, $token);
    }

    public static function validateForm(): void
    {
        $token = $_POST[self::KEY] ?? '';
        if (!self::validate($token)) {
            http_response_code(403);
            exit('Token CSRF inválido.');
        }
    }

    public static function validateApi(): void
    {
        $header = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!self::validate($header)) {
            require_once __DIR__ . '/Response.php';
            Response::error('Token CSRF inválido.', 403);
        }
    }
}
