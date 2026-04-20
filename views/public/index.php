<div id="alertContainer" role="alert" aria-live="polite"></div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-plus me-2"></i>
                    Registrar nueva solicitud
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Complete el formulario a continuación. Recibirá una respuesta a su correo electrónico.
                </p>

                <div class="alert alert-danger d-none" id="erroresNueva">
                    <strong><i class="bi bi-exclamation-triangle me-1"></i>Corrija los siguientes errores:</strong>
                    <ul class="lista-errores mb-0 mt-1"></ul>
                </div>

                <form id="formPublica" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="inputNombre" class="form-label">
                                Nombre completo <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="inputNombre"
                                name="nombre_solicitante"
                                class="form-control"
                                maxlength="150"
                                required
                            >
                        </div>
                        <div class="col-md-6">
                            <label for="inputCorreo" class="form-label">
                                Correo electrónico <span class="text-danger">*</span>
                            </label>
                            <input
                                type="email"
                                id="inputCorreo"
                                name="correo_electronico"
                                class="form-control"
                                maxlength="150"
                                required
                            >
                        </div>
                        <div class="col-md-6">
                            <label for="inputTipo" class="form-label">
                                Tipo de solicitud <span class="text-danger">*</span>
                            </label>
                            <select id="inputTipo" name="tipo_solicitud" class="form-select" required>
                                <option value="">Seleccione…</option>
                                <option value="academica">Académica</option>
                                <option value="certificado">Certificado</option>
                                <option value="actualizacion_datos">Actualización de Datos</option>
                                <option value="otra">Otra</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="inputDescripcion" class="form-label">
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
                            <div class="form-text">Mínimo 10 caracteres.</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" id="btnEnviarSolicitud" class="btn btn-primary">
                            <i class="bi bi-send me-1"></i> Enviar solicitud
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
