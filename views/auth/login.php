<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-shield-lock me-2"></i>
                    Acceso administrativo
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="login.php" novalidate>
                    <div class="mb-3">
                        <label for="inputCorreoLogin" class="form-label">Correo</label>
                        <input
                            type="email"
                            id="inputCorreoLogin"
                            name="correo"
                            class="form-control"
                            value="<?= htmlspecialchars($correoIngresado ?? '') ?>"
                            maxlength="150"
                            required
                            autofocus
                        >
                    </div>
                    <div class="mb-3">
                        <label for="inputPasswordLogin" class="form-label">Contraseña</label>
                        <input
                            type="password"
                            id="inputPasswordLogin"
                            name="password"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-box-arrow-in-right me-1"></i>
                            Ingresar
                        </button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <a href="index.php" class="text-muted small">
                        <i class="bi bi-arrow-left"></i> Volver al formulario público
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
