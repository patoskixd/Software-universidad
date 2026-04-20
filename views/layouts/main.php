<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Sistema de Solicitudes') ?> – Universidad</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
    <?php if (class_exists('Csrf')): ?>
        <meta name="csrf-token" content="<?= Csrf::token() ?>">
    <?php endif; ?>
</head>
<body>

    <nav class="navbar navbar-dark shadow" style="position:relative;overflow:hidden;">
        <!-- SVG decorativo fondo navbar -->
        <svg style="position:absolute;top:0;right:0;height:100%;opacity:.07;pointer-events:none;" viewBox="0 0 300 60" preserveAspectRatio="xMaxYMid meet" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="280" cy="30" r="80" fill="white"/>
            <circle cx="220" cy="0"  r="50" fill="white"/>
            <circle cx="250" cy="60" r="40" fill="white"/>
        </svg>

        <div class="container-fluid px-4" style="position:relative;z-index:1;">
            <a class="navbar-brand mb-0 h1 text-decoration-none d-flex align-items-center gap-2" href="index.php">
                <!-- SVG mortarboard inline -->
                <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" style="flex-shrink:0;">
                    <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
                </svg>
                <span>
                    Sistema de Solicitudes
                    <span class="d-none d-md-inline fw-light" style="opacity:.6;"> · Universidad</span>
                </span>
            </a>

            <div class="d-flex align-items-center gap-2">
                <?php if (!empty($adminActual)): ?>
                    <span class="text-white-50 small d-none d-sm-inline">
                        <i class="bi bi-person-circle me-1"></i>
                        <?= htmlspecialchars($adminActual['nombre']) ?>
                    </span>
                    <a href="admin.php" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-speedometer2 me-1"></i>
                        <span class="d-none d-sm-inline">Panel</span>
                    </a>
                    <a href="logout.php" class="btn btn-light btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i>
                        <span class="d-none d-sm-inline">Salir</span>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-light btn-sm">
                        <i class="bi bi-shield-lock me-1"></i>
                        <span class="d-none d-sm-inline">Acceso admin</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="container my-4">
        <?= $content ?>
    </main>

    <div id="toastContainer" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:1090;"></div>

    <footer class="site-footer mt-auto">
        <div class="container-fluid px-4 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-1">
            <span>
                <i class="bi bi-mortarboard-fill me-1"></i>
                Sistema de Solicitudes Administrativas
            </span>
            <span><?= date('Y') ?> · Desarrollado para uso institucional</span>
        </div>
    </footer>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"
    ></script>
    <?php foreach ($pageScripts ?? [] as $script): ?>
        <script src="<?= htmlspecialchars($script) ?>?v=<?= time() ?>"></script>
    <?php endforeach; ?>
</body>
</html>
