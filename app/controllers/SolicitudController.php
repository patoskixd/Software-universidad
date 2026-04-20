<?php

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Solicitud.php';

class SolicitudController
{
    private Solicitud $model;

    public function __construct()
    {
        $pdo = Database::getInstance();
        $this->model = new Solicitud($pdo);
    }

    public function handleRequest(): void
    {
        Response::setCorsHeaders();

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }

        try {
            match ($_SERVER['REQUEST_METHOD']) {
                'GET'   => $this->index(),
                'POST'  => $this->store(),
                'PATCH' => $this->updateEstado(),
                default => Response::error('Método no permitido.', 405),
            };
        } catch (PDOException $e) {
            error_log('[SolicitudController] PDOException: ' . $e->getMessage());
            Response::error('Error interno del servidor. Intente más tarde.', 500);
        }
    }

    private function index(): void
    {
        Auth::requireApi();

        if (!empty($_GET['id'])) {
            $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
            if ($id === false || $id <= 0) {
                Response::error('ID inválido.', 400);
            }

            $solicitud = $this->model->getById($id);
            if ($solicitud === null) {
                Response::error('Solicitud no encontrada.', 404);
            }

            Response::json($solicitud);
        }

        $filters = [
            'estado'         => $_GET['estado']         ?? '',
            'tipo_solicitud' => $_GET['tipo_solicitud'] ?? '',
            'texto'          => $_GET['texto']          ?? '',
        ];

        $page  = max(1, (int)($_GET['page'] ?? 1));
        $limit = max(1, (int)($_GET['limit'] ?? 10)); // Default 10 rows
        $offset = ($page - 1) * $limit;

        $total = $this->model->getTotal($filters);
        $data  = $this->model->getAll($filters, $limit, $offset);

        Response::json([
            'data'      => $data,
            'total'     => $total,
            'page'      => $page,
            'limit'     => $limit,
            'last_page' => max(1, ceil($total / $limit)),
        ]);
    }

    private function store(): void
    {
        $data   = json_decode(file_get_contents('php://input'), true);
        $errors = $this->model->validate($data);

        if ($errors) {
            Response::validationError($errors);
        }

        $id = $this->model->create($data);

        Response::json([
            'id'      => $id,
            'message' => 'Solicitud registrada exitosamente.',
        ], 201);
    }

    private function updateEstado(): void
    {
        Auth::requireApi();

        $data   = json_decode(file_get_contents('php://input'), true);
        $errors = $this->model->validateEstado($data);

        if ($errors) {
            Response::validationError($errors);
        }

        $id      = (int) $data['id'];
        $estado  = $data['estado'];
        $updated = $this->model->updateEstado($id, $estado);

        if (!$updated) {
            Response::error('Solicitud no encontrada.', 404);
        }

        Response::json(['message' => 'Estado actualizado exitosamente.']);
    }
}
