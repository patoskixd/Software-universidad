<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Sistema de Solicitudes') ?> – Universidad</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    >
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-dark bg-primary shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand mb-0 h1 text-decoration-none" href="index.php">
                <i class="bi bi-mortarboard-fill me-2"></i>
                Sistema de Solicitudes Administrativas
            </a>

            <div class="d-flex align-items-center text-white">
                <?php if (!empty($adminActual)): ?>
                    <span class="me-3 small d-none d-sm-inline">
                        <i class="bi bi-person-circle me-1"></i>
                        <?= htmlspecialchars($adminActual['nombre']) ?>
                    </span>
                    <a href="admin.php" class="btn btn-sm btn-outline-light me-2">
                        <i class="bi bi-speedometer2 me-1"></i> Panel
                    </a>
                    <a href="logout.php" class="btn btn-sm btn-light">
                        <i class="bi bi-box-arrow-right me-1"></i> Salir
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-sm btn-light">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Acceso admin
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="container my-4">
        <?= $content ?>
    </main>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"
    ></script>
    <?php if (!empty($pageScript)): ?>
        <script src="<?= htmlspecialchars($pageScript) ?>"></script>
    <?php endif; ?>
</body>
</html>
