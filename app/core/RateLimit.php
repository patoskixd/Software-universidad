<?php

class RateLimit
{
    // Login: 5 intentos fallidos en 10 minutos
    private const MAX_ATTEMPTS = 5;
    private const WINDOW_SECS = 600;

    // Solicitudes públicas máximo 10 por minuto por IP
    private const MAX_SOLICITUDES = 10;
    private const WINDOW_SOLICITUDES = 60;

    public static function check(PDO $pdo, string $ip): bool
    {
        $since = date('Y-m-d H:i:s', time() - self::WINDOW_SECS);

        $stmt = $pdo->prepare(
            'SELECT COUNT(*) FROM login_attempts WHERE ip = ? AND attempted_at > ?'
        );
        $stmt->execute([$ip, $since]);

        return (int) $stmt->fetchColumn() < self::MAX_ATTEMPTS;
    }

    public static function record(PDO $pdo, string $ip): void
    {
        $stmt = $pdo->prepare('INSERT INTO login_attempts (ip) VALUES (?)');
        $stmt->execute([$ip]);
    }

    public static function cleanup(PDO $pdo): void
    {
        $since = date('Y-m-d H:i:s', time() - self::WINDOW_SECS);
        $stmt = $pdo->prepare('DELETE FROM login_attempts WHERE attempted_at <= ?');
        $stmt->execute([$since]);
    }

    // Verifica si la IP puede crear más solicitudes públicas
    public static function checkSolicitud(PDO $pdo, string $ip): bool
    {
        $since = date('Y-m-d H:i:s', time() - self::WINDOW_SOLICITUDES);

        $stmt = $pdo->prepare(
            'SELECT COUNT(*) FROM solicitudes WHERE ip_solicitante = ? AND fecha_creacion > ?'
        );
        $stmt->execute([$ip, $since]);

        return (int) $stmt->fetchColumn() < self::MAX_SOLICITUDES;
    }

    public static function clientIp(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}
