<div class="login-wrapper">
    <div class="login-card card">

        <div class="login-header">
            <!-- SVG decorativo fondo -->
            <svg style="position:absolute;top:0;right:0;height:100%;opacity:.09;pointer-events:none;" viewBox="0 0 200 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="180" cy="60" r="100" fill="white"/>
                <circle cx="120" cy="0"  r="60"  fill="white"/>
                <circle cx="160" cy="120" r="45" fill="white"/>
            </svg>
            <div class="login-icon">
                <!-- SVG escudo -->
                <svg width="32" height="32" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 4l6 2.18V11c0 3.72-2.55 7.22-6 8.36C8.55 18.22 6 14.72 6 11V7.18L12 5zm-1 7h2v2h-2zm0-4h2v3h-2z"/>
                </svg>
            </div>
            <h4 class="mb-1 fw-bold">Acceso administrativo</h4>
            <p class="mb-0 small" style="color:rgba(255,255,255,.65);">Ingrese sus credenciales para continuar</p>
        </div>

        <div class="login-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="login.php" novalidate>
                <input type="hidden" name="_csrf_token" value="<?= Csrf::token() ?>">
                <div class="mb-3">
                    <label for="inputCorreoLogin" class="form-label">
                        <i class="bi bi-envelope me-1 text-primary"></i> Correo electrónico
                    </label>
                    <input
                        type="email"
                        id="inputCorreoLogin"
                        name="correo"
                        class="form-control"
                        value="<?= htmlspecialchars($correoIngresado ?? '') ?>"
                        placeholder="admin@universidad.cl"
                        maxlength="150"
                        required
                        autofocus
                    >
                </div>

                <div class="mb-4">
                    <label for="inputPasswordLogin" class="form-label">
                        <i class="bi bi-lock me-1 text-primary"></i> Contraseña
                    </label>
                    <div class="input-group">
                        <input
                            type="password"
                            id="inputPasswordLogin"
                            name="password"
                            class="form-control"
                            placeholder="••••••••"
                            required
                        >
                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            id="togglePassword"
                            title="Mostrar / ocultar contraseña"
                            tabindex="-1"
                        >
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Ingresar
                    </button>
                </div>
            </form>

            <div class="text-center mt-3 pt-2 border-top">
                <a href="index.php" class="text-muted small text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i> Volver al formulario público
                </a>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const btn = document.getElementById('togglePassword');
    const input = document.getElementById('inputPasswordLogin');
    const icon = document.getElementById('toggleIcon');
    if (btn && input) {
        btn.addEventListener('click', function () {
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    }
})();
</script>
