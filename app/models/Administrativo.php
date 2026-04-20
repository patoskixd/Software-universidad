<?php

class Administrativo
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function buscarPorCorreo(string $correo): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, nombre, correo, password_hash
               FROM administrativos
              WHERE correo = ?
              LIMIT 1'
        );
        $stmt->execute([strtolower(trim($correo))]);

        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function verificarCredenciales(string $correo, string $password): ?array
    {
        $admin = $this->buscarPorCorreo($correo);
        if ($admin === null) {
            return null;
        }

        if (!password_verify($password, $admin['password_hash'])) {
            return null;
        }

        unset($admin['password_hash']);
        return $admin;
    }
}
