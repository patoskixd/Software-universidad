<?php

class Solicitud
{
    private PDO $pdo;

    public const TIPOS_VALIDOS   = ['academica', 'certificado', 'actualizacion_datos', 'otra'];
    public const ESTADOS_VALIDOS = ['pendiente', 'en_revision', 'aprobada', 'rechazada'];

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function buildConditions(array $filters, array &$params): array
    {
        $conditions = [];

        if (!empty($filters['estado']) && in_array($filters['estado'], self::ESTADOS_VALIDOS, true)) {
            $conditions[] = 'estado = ?';
            $params[]     = $filters['estado'];
        }

        if (!empty($filters['tipo_solicitud']) && in_array($filters['tipo_solicitud'], self::TIPOS_VALIDOS, true)) {
            $conditions[] = 'tipo_solicitud = ?';
            $params[]     = $filters['tipo_solicitud'];
        }

        if (!empty($filters['texto'])) {
            $like         = '%' . $filters['texto'] . '%';
            $conditions[] = '(nombre_solicitante LIKE ? OR correo_electronico LIKE ?)';
            $params[]     = $like;
            $params[]     = $like;
        }

        return $conditions;
    }

    public function getTotal(array $filters = []): int
    {
        $params     = [];
        $conditions = $this->buildConditions($filters, $params);

        $sql = 'SELECT COUNT(*) FROM solicitudes';

        if ($conditions) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }

    public function getAll(array $filters = [], int $limit = 10, int $offset = 0): array
    {
        $params     = [];
        $conditions = $this->buildConditions($filters, $params);

        $sql = 'SELECT id, nombre_solicitante, correo_electronico,
                       tipo_solicitud, descripcion, estado, fecha_creacion
                FROM solicitudes';

        if ($conditions) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY fecha_creacion DESC LIMIT ' . (int)$limit . ' OFFSET ' . (int)$offset;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM solicitudes WHERE id = ?');
        $stmt->execute([$id]);

        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO solicitudes
                 (nombre_solicitante, correo_electronico, tipo_solicitud, descripcion, estado)
             VALUES (?, ?, ?, ?, ?)'
        );

        $stmt->execute([
            trim($data['nombre_solicitante']),
            strtolower(trim($data['correo_electronico'])),
            $data['tipo_solicitud'],
            trim($data['descripcion']),
            'pendiente',
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function updateEstado(int $id, string $estado): bool
    {
        $stmt = $this->pdo->prepare('UPDATE solicitudes SET estado = ? WHERE id = ?');
        $stmt->execute([$estado, $id]);

        return $stmt->rowCount() > 0;
    }

    public function validate(?array $data): array
    {
        if ($data === null) {
            return ['El cuerpo de la solicitud no es un JSON válido.'];
        }

        $errors = [];

        // Nombre
        $nombre = trim($data['nombre_solicitante'] ?? '');
        if ($nombre === '' || mb_strlen($nombre) < 2) {
            $errors[] = 'El nombre completo es obligatorio (mínimo 2 caracteres).';
        } elseif (mb_strlen($nombre) > 150) {
            $errors[] = 'El nombre no puede superar los 150 caracteres.';
        }

        // Correo
        $correo = trim($data['correo_electronico'] ?? '');
        if ($correo === '') {
            $errors[] = 'El correo electrónico es obligatorio.';
        } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El formato del correo electrónico no es válido.';
        }

        // Tipo
        if (empty($data['tipo_solicitud']) || !in_array($data['tipo_solicitud'], self::TIPOS_VALIDOS, true)) {
            $errors[] = 'Debe seleccionar un tipo de solicitud válido.';
        }

        // Descripción
        $descripcion = trim($data['descripcion'] ?? '');
        if ($descripcion === '' || mb_strlen($descripcion) < 10) {
            $errors[] = 'La descripción es obligatoria (mínimo 10 caracteres).';
        }

        return $errors;
    }

    public function validateEstado(?array $data): array
    {
        if ($data === null) {
            return ['El cuerpo de la solicitud no es un JSON válido.'];
        }

        $errors = [];

        $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false || $id <= 0) {
            $errors[] = 'ID de solicitud inválido.';
        }

        $estado = $data['estado'] ?? '';
        if (!in_array($estado, self::ESTADOS_VALIDOS, true)) {
            $errors[] = 'Estado no válido. Valores permitidos: ' . implode(', ', self::ESTADOS_VALIDOS) . '.';
        }

        return $errors;
    }
}
