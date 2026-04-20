<?php

class RateLimit
{
    private const MAX_ATTEMPTS = 5;
    private const WINDOW_SECS  = 600; // 10 minutos

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
        $stmt  = $pdo->prepare('DELETE FROM login_attempts WHERE attempted_at <= ?');
        $stmt->execute([$since]);
    }

    public static function clientIp(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}
