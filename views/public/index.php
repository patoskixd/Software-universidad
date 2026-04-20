<div id="alertContainer" role="alert" aria-live="polite"></div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm overflow-hidden">

            <!-- Hero -->
            <div class="form-hero" style="position:relative;overflow:hidden;">
                <!-- SVG decorativo -->
                <svg style="position:absolute;top:0;right:0;height:100%;opacity:.08;pointer-events:none;" viewBox="0 0 220 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="200" cy="45" r="90" fill="white"/>
                    <circle cx="145" cy="0"  r="55" fill="white"/>
                    <circle cx="185" cy="90" r="40" fill="white"/>
                </svg>
                <div style="position:relative;z-index:1;">
                    <div class="form-hero-icon">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm4 18H6V4h7v5h5v11zM8 15h8v2H8zm0-4h8v2H8z"/></svg>
                    </div>
                    <h4 class="mb-1 fw-bold">Nueva Solicitud Administrativa</h4>
                    <p class="mb-0 text-white-50 small">
                        Complete el formulario y recibirá una respuesta en su correo electrónico.
                    </p>
                </div>
            </div>

            <!-- Form body -->
            <div class="card-body p-4">

                <div class="alert alert-danger d-none" id="erroresNueva">
                    <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Corrija los siguientes errores:</strong>
                    <ul class="lista-errores mb-0 mt-1"></ul>
                </div>

                <form id="formPublica" novalidate>
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="inputNombre" class="form-label">
                                <i class="bi bi-person me-1 text-primary"></i>
                                Nombre completo <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="inputNombre"
                                name="nombre_solicitante"
                                class="form-control"
                                placeholder="Ej: Juan Pérez González"
                                maxlength="150"
                                required
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="inputCorreo" class="form-label">
                                <i class="bi bi-envelope me-1 text-primary"></i>
                                Correo electrónico <span class="text-danger">*</span>
                            </label>
                            <input
                                type="email"
                                id="inputCorreo"
                                name="correo_electronico"
                                class="form-control"
                                placeholder="Ej: juan@correo.cl"
                                maxlength="150"
                                required
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="inputTipo" class="form-label">
                                <i class="bi bi-tag me-1 text-primary"></i>
                                Tipo de solicitud <span class="text-danger">*</span>
                            </label>
                            <select id="inputTipo" name="tipo_solicitud" class="form-select" required>
                                <option value="">Seleccione una opción…</option>
                                <option value="academica">Académica</option>
                                <option value="certificado">Certificado</option>
                                <option value="actualizacion_datos">Actualización de Datos</option>
                                <option value="otra">Otra</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="inputDescripcion" class="form-label">
                                <i class="bi bi-chat-left-text me-1 text-primary"></i>
                                Descripción <span class="text-danger">*</span>
                            </label>
                            <textarea
                                id="inputDescripcion"
                                name="descripcion"
                                class="form-control"
                                rows="5"
                                placeholder="Describa su solicitud con el mayor detalle posible…"
                                required
                            ></textarea>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Mínimo 10 caracteres. Sea lo más específico posible.
                            </div>
                        </div>

                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-2 border-top">
                        <span class="text-muted small">
                            <span class="text-danger">*</span> Campos obligatorios
                        </span>
                        <button type="submit" id="btnEnviarSolicitud" class="btn btn-primary px-4">
                            <i class="bi bi-send me-1"></i> Enviar solicitud
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
