<div id="alertContainer" role="alert" aria-live="polite"></div>

<div class="row justify-content-center g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm overflow-hidden">

            <!-- Hero -->
            <div class="form-hero" style="position:relative;overflow:hidden;">
                <!-- SVG decorativo -->
                <svg style="position:absolute;top:0;right:0;height:100%;opacity:.08;pointer-events:none;"
                    viewBox="0 0 220 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="200" cy="45" r="90" fill="white" />
                    <circle cx="145" cy="0" r="55" fill="white" />
                    <circle cx="185" cy="90" r="40" fill="white" />
                </svg>
                <div style="position:relative;z-index:1;">
                    <div class="form-hero-icon">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm4 18H6V4h7v5h5v11zM8 15h8v2H8zm0-4h8v2H8z" />
                        </svg>
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
                    <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Corrija los siguientes
                        errores:</strong>
                    <ul class="lista-errores mb-0 mt-1"></ul>
                </div>

                <form id="formPublica" novalidate>
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="inputNombre" class="form-label">
                                <i class="bi bi-person me-1 text-primary"></i>
                                Nombre completo <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="inputNombre" name="nombre_solicitante" class="form-control"
                                placeholder="Ej: Juan Pérez González" maxlength="150" required>
                        </div>

                        <div class="col-md-6">
                            <label for="inputCorreo" class="form-label">
                                <i class="bi bi-envelope me-1 text-primary"></i>
                                Correo electrónico <span class="text-danger">*</span>
                            </label>
                            <input type="email" id="inputCorreo" name="correo_electronico" class="form-control"
                                placeholder="Ej: juan@correo.cl" maxlength="150" required>
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
                            <textarea id="inputDescripcion" name="descripcion" class="form-control" rows="5"
                                placeholder="Describa su solicitud con el mayor detalle posible…" required></textarea>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Mínimo 10 caracteres. Sea lo más específico posible.
                            </div>
                        </div>

                    </div>

                    <div
                        class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-4 pt-3 border-top">
                        <span class="text-muted small">
                            <span class="text-danger">*</span> Campos obligatorios
                        </span>
                        <button type="submit" id="btnEnviarSolicitud" class="btn btn-primary px-4 w-100 w-md-auto">
                            <i class="bi bi-send me-1"></i> Enviar solicitud
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- Consultar estado -->
<div class="row justify-content-center mt-2">
    <div class="col-lg-8">
        <div class="card shadow-sm overflow-hidden">

            <div class="form-hero" style="position:relative;overflow:hidden;padding:1.75rem 2rem;">
                <svg style="position:absolute;top:0;right:0;height:100%;opacity:.08;pointer-events:none;"
                    viewBox="0 0 220 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="200" cy="45" r="90" fill="white" />
                    <circle cx="145" cy="0" r="55" fill="white" />
                    <circle cx="185" cy="90" r="40" fill="white" />
                </svg>
                <div style="position:relative;z-index:1;display:flex;align-items:center;gap:.85rem;">
                    <div class="form-hero-icon" style="margin-bottom:0;flex-shrink:0;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z" />
                        </svg>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">Consultar mis solicitudes</h5>
                        <p class="mb-0 small" style="color:rgba(255,255,255,.65);">Ingrese su correo para ver el estado
                            de sus solicitudes.</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <form id="formConsulta" novalidate>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <input type="email" id="inputConsultaCorreo" class="form-control"
                            placeholder="Ej: juan@correo.cl" maxlength="150" required>
                        <button type="submit" id="btnConsultar" class="btn btn-primary px-4 text-nowrap">
                            <i class="bi bi-search me-1"></i> Buscar
                        </button>
                    </div>
                </form>
            </div>

            <div id="consultaResultados"></div>

        </div>
    </div>
</div>

<!-- Modal ver descripcion -->
<div class="modal fade" id="modalVerDescripcion" tabindex="-1" aria-labelledby="modalVerDescripcionLabel">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title d-flex align-items-center gap-2 fw-semibold text-primary"
                    id="modalVerDescripcionLabel">
                    <i class="bi bi-file-text bg-primary text-white rounded p-1 pb-0 fs-6"></i>
                    Detalle de Solicitud
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick="this.blur()"></button>
            </div>
            <div class="modal-body p-4 text-start bg-light">
                <div class="row g-4">

                    <div class="col-md-6">
                        <label class="text-muted small fw-bold text-uppercase d-flex align-items-center gap-2 mb-1"
                            style="font-size: 0.75rem;">
                            <i class="bi bi-tag text-secondary"></i> Tipo
                        </label>
                        <div id="modalDetalleTipo" class="text-dark fs-6"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small fw-bold text-uppercase d-flex align-items-center gap-2 mb-1"
                            style="font-size: 0.75rem;">
                            <i class="bi bi-person text-secondary"></i> Solicitante
                        </label>
                        <div id="modalDetalleSolicitante" class="text-dark fw-medium fs-6"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small fw-bold text-uppercase d-flex align-items-center gap-2 mb-1"
                            style="font-size: 0.75rem;">
                            <i class="bi bi-envelope text-secondary"></i> Correo
                        </label>
                        <div id="modalDetalleCorreo" class="text-primary fs-6 text-break"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small fw-bold text-uppercase d-flex align-items-center gap-2 mb-1"
                            style="font-size: 0.75rem;">
                            <i class="bi bi-circle-fill text-secondary"></i> Estado
                        </label>
                        <div id="modalDetalleEstado" class="fs-6"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small fw-bold text-uppercase d-flex align-items-center gap-2 mb-1"
                            style="font-size: 0.75rem;">
                            <i class="bi bi-calendar3 text-secondary"></i> Fecha de Creación
                        </label>
                        <div id="modalDetalleFecha" class="text-dark fs-6" style="letter-spacing: 0.3px;"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small fw-bold text-uppercase d-flex align-items-center gap-2 mb-1"
                            style="font-size: 0.75rem;">
                            <i class="bi bi-pencil-square text-secondary"></i> Última Modificación
                        </label>
                        <div id="modalDetalleFechaMod" class="text-dark fs-6" style="letter-spacing: 0.3px;"></div>
                    </div>

                    <div class="col-12">
                        <label class="text-muted small fw-bold text-uppercase d-flex align-items-center gap-2 mb-2"
                            style="font-size: 0.75rem;">
                            <i class="bi bi-chat-left-text text-secondary"></i> Descripción
                        </label>
                        <div class="p-3 bg-white border border-light-subtle rounded-3 shadow-sm">
                            <p id="descripcionCompletaTexto" class="text-break mb-0 text-secondary"
                                style="white-space: pre-wrap; font-size: 0.95rem; line-height: 1.6;"></p>
                        </div>
                    </div>

                    <!-- obsevaciones (visible solo si existe) -->
                    <div id="cajaFeedbackUsuario" class="col-12 d-none">
                        <div class="p-3 rounded-3 border"
                            style="background: linear-gradient(135deg, #fff8f0 0%, #fff3cd 100%); border-color: #ffc107 !important;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-chat-left-dots-fill text-warning fs-5"></i>
                                <span class="fw-bold text-dark"
                                    style="font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                    Observaciones
                                </span>
                            </div>
                            <p id="feedbackTextoUsuario" class="mb-0 text-dark"
                                style="white-space: pre-wrap; word-break: break-word; overflow-wrap: anywhere; font-size: 0.95rem; line-height: 1.6;">
                            </p>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 bg-light rounded-bottom-4">
                <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal"
                    onclick="this.blur()">Cerrar</button>
            </div>
        </div>
    </div>
</div>