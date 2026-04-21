<!-- Formulario nueva solicitud -->
<div class="card overflow-hidden">
    <div class="form-hero" style="position:relative;overflow:hidden;">
        <svg style="position:absolute;top:0;right:0;height:100%;opacity:.08;pointer-events:none;" viewBox="0 0 220 90"
            fill="none">
            <circle cx="200" cy="45" r="90" fill="white" />
            <circle cx="145" cy="0" r="55" fill="white" />
            <circle cx="185" cy="90" r="40" fill="white" />
        </svg>
        <div style="position:relative;z-index:1;display:flex;align-items:center;gap:.9rem;">
            <div class="form-hero-icon" style="margin-bottom:0;flex-shrink:0;">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm4 18H6V4h7v5h5v11zM8 15h8v2H8zm0-4h8v2H8z" />
                </svg>
            </div>
            <div>
                <h4 class="mb-1 fw-bold">Nueva Solicitud Administrativa</h4>
                <p class="mb-0 small" style="color:rgba(255,255,255,.65);">Complete los datos solicitados a continuación
                    para iniciar la gestión de su requerimiento.</p>
            </div>
        </div>
    </div>

    <div class="card-body p-3">
        <div class="alert alert-danger d-none" id="erroresNueva">
            <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Corrija los siguientes errores:</strong>
            <ul class="lista-errores mb-0 mt-1"></ul>
        </div>

        <form id="formPublica" novalidate>
            <div class="row g-3">

                <div class="col-md-6">
                    <label for="inputNombre" class="form-label">
                        <i class="bi bi-person me-1 text-primary"></i>Nombre completo <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="inputNombre" name="nombre_solicitante" class="form-control"
                        placeholder="Juan Pérez González" maxlength="150" required>
                </div>

                <div class="col-md-6">
                    <label for="inputCorreo" class="form-label">
                        <i class="bi bi-envelope me-1 text-primary"></i>Correo electrónico <span
                            class="text-danger">*</span>
                    </label>
                    <input type="email" id="inputCorreo" name="correo_electronico" class="form-control"
                        placeholder="juan@correo.cl" maxlength="150" required>
                </div>

                <div class="col-12">
                    <label for="inputTipo" class="form-label">
                        <i class="bi bi-tag me-1 text-primary"></i>Tipo de solicitud <span class="text-danger">*</span>
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
                        <i class="bi bi-chat-left-text me-1 text-primary"></i>Descripción <span
                            class="text-danger">*</span>
                    </label>
                    <textarea id="inputDescripcion" name="descripcion" class="form-control" rows="3"
                        placeholder="Describa su solicitud con el mayor detalle posible…" required></textarea>
                    <div class="form-text"><i class="bi bi-info-circle me-1"></i>Mínimo 10 caracteres.</div>
                </div>

            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <span class="text-muted small"><span class="text-danger">*</span> Campos obligatorios</span>
                <button type="submit" id="btnEnviarSolicitud" class="btn btn-primary px-4">
                    <i class="bi bi-send me-1"></i> Enviar solicitud
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Consultar estado -->
<div class="card overflow-hidden mt-3">
    <div class="form-hero" style="position:relative;overflow:hidden;padding:1.6rem 2rem;">
        <svg style="position:absolute;top:0;right:0;height:100%;opacity:.08;pointer-events:none;" viewBox="0 0 220 90"
            fill="none">
            <circle cx="200" cy="45" r="90" fill="white" />
            <circle cx="145" cy="0" r="55" fill="white" />
            <circle cx="185" cy="90" r="40" fill="white" />
        </svg>
        <div style="position:relative;z-index:1;display:flex;align-items:center;gap:.9rem;">
            <div class="form-hero-icon" style="margin-bottom:0;flex-shrink:0;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z" />
                </svg>
            </div>
            <div>
                <h5 class="mb-0 fw-bold">Consultar mis solicitudes</h5>
                <p class="mb-0 small" style="color:rgba(255,255,255,.65);">Ingrese su correo para ver el estado de sus
                    solicitudes.</p>
            </div>
        </div>
    </div>

    <div class="card-body p-3">
        <form id="formConsulta" novalidate>
            <div class="d-flex gap-2">
                <input type="email" id="inputConsultaCorreo" class="form-control" placeholder="juan@correo.cl"
                    maxlength="150" required>
                <button type="submit" id="btnConsultar" class="btn btn-primary px-4 flex-shrink-0">
                    <i class="bi bi-search me-1"></i> Buscar
                </button>
            </div>
        </form>
    </div>

    <div id="consultaResultados"></div>
</div>


<!-- Modal: Ver detalle de solicitud -->
<div class="modal fade" id="modalVerDescripcion" tabindex="-1" aria-labelledby="modalVerDescripcionLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="modalVerDescripcionLabel">
                    <i class="bi bi-file-text me-2"></i>Detalle de Solicitud
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">

                    <div class="col-sm-6">
                        <div class="detail-item">
                            <div class="detail-label"><i class="bi bi-tag"></i>Tipo</div>
                            <div class="detail-value" id="modalDetalleTipo"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="detail-item">
                            <div class="detail-label"><i class="bi bi-person"></i>Solicitante</div>
                            <div class="detail-value" id="modalDetalleSolicitante"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="detail-item">
                            <div class="detail-label"><i class="bi bi-envelope"></i>Correo</div>
                            <div class="detail-value" id="modalDetalleCorreo"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="detail-item">
                            <div class="detail-label"><i class="bi bi-circle-fill"></i>Estado</div>
                            <div class="detail-value" id="modalDetalleEstado"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="detail-item">
                            <div class="detail-label"><i class="bi bi-calendar"></i>Fecha de creación</div>
                            <div class="detail-value" id="modalDetalleFecha"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="detail-item">
                            <div class="detail-label"><i class="bi bi-pencil-square"></i>Última actualización</div>
                            <div class="detail-value" id="modalDetalleFechaMod"></div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="detail-label mb-1"><i class="bi bi-chat-left-text"></i>Descripción</div>
                        <div class="detail-desc" id="descripcionCompletaTexto"></div>
                    </div>

                    <div class="col-12 d-none" id="cajaFeedbackUsuario">
                        <div class="detail-label mb-1"><i class="bi bi-chat-left-dots"></i>Observaciones</div>
                        <div class="detail-obs" id="feedbackTextoUsuario"></div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>