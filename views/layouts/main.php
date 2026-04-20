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

    <!--  Navbar  -->
    <nav class="navbar navbar-dark bg-primary shadow-sm">
        <div class="container-fluid px-4">
            <span class="navbar-brand mb-0 h1">
                <i class="bi bi-mortarboard-fill me-2"></i>
                Sistema de Solicitudes Administrativas
            </span>
        </div>
    </nav>

    <!--  Contenido inyectado desde la vista  -->
    <main class="container my-4">
        <?= $content ?>
    </main>

    <!--  Bootstrap y logica del frontend  -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"
    ></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
