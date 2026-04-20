<div id="alertContainer" role="alert" aria-live="polite"></div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold">Solicitudes</h5>
    </div>
    <button
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#modalNuevaSolicitud"
    >
        <i class="bi bi-plus-lg me-1"></i> Nueva Solicitud
    </button>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form id="filterForm" class="row g-3 align-items-end">
            <div class="col-sm-6 col-md-3">
                <label for="filterEstado" class="form-label fw-semibold">Estado</label>
                <select id="filterEstado" class="form-select">
                    <option value="">Todos</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="en_revision">En Revisión</option>
                    <option value="aprobada">Aprobada</option>
                    <option value="rechazada">Rechazada</option>
                </select>
            </div>
            <div class="col-sm-6 col-md-3">
                <label for="filterTipo" class="form-label fw-semibold">Tipo</label>
                <select id="filterTipo" class="form-select">
                    <option value="">Todos</option>
                    <option value="academica">Académica</option>
                    <option value="certificado">Certificado</option>
                    <option value="actualizacion_datos">Actualización de Datos</option>
                    <option value="otra">Otra</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="filterTexto" class="form-label fw-semibold">Búsqueda libre</label>
                <input
                    type="text"
                    id="filterTexto"
                    class="form-control"
                    placeholder="Nombre o correo electrónico…"
                    maxlength="100"
                >
            </div>
            <div class="col-auto d-flex gap-2">
                <button type="submit" class="btn btn-primary" title="Buscar">
                    <i class="bi bi-search"></i>
                </button>
                <button type="button" id="btnLimpiarFiltros" class="btn btn-outline-secondary" title="Limpiar filtros">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabla -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">ID</th>
                        <th>Solicitante</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th class="text-center pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody id="solicitudesTableBody">
                    <?php
                    $badgeClase = ['pendiente' => 'warning', 'en_revision' => 'info', 'aprobada' => 'success', 'rechazada' => 'danger'];
                    $tipoLabel  = ['academica' => 'Académica', 'certificado' => 'Certificado', 'actualizacion_datos' => 'Actualización de Datos', 'otra' => 'Otra'];
                    foreach ($solicitudes ?? [] as $s): ?>
                    <tr>
                        <td class="ps-3"><?= $s['id'] ?></td>
                        <td><?= htmlspecialchars($s['nombre_solicitante']) ?></td>
                        <td><?= $tipoLabel[$s['tipo_solicitud']] ?? $s['tipo_solicitud'] ?></td>
                        <td>
                            <span class="badge bg-<?= $badgeClase[$s['estado']] ?? 'secondary' ?>">
                                <?= ucfirst(str_replace('_', ' ', $s['estado'])) ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y', strtotime($s['fecha_creacion'])) ?></td>
                        <td class="text-center pe-3 d-flex gap-1 justify-content-center">
                            <button class="btn btn-sm btn-outline-secondary" title="Ver detalle">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button
                                class="btn btn-sm btn-outline-primary"
                                title="Actualizar estado"
                                data-bs-toggle="modal"
                                data-bs-target="#modalActualizarEstado"
                                data-nombre="<?= htmlspecialchars($s['nombre_solicitante']) ?>"
                                data-estado="<?= $s['estado'] ?>"
                                onclick="
                                    document.getElementById('updateNombreDisplay').textContent = this.dataset.nombre;
                                    document.getElementById('updateEstado').value = this.dataset.estado;
                                "
                            >
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de nueva solicitud -->
<div
    class="modal fade"
    id="modalNuevaSolicitud"
    tabindex="-1"
    aria-labelledby="modalNuevaSolicitudLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalNuevaSolicitudLabel">
                    <i class="bi bi-file-earmark-plus me-2"></i>Nueva Solicitud
                </h5>
                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar"
                ></button>
            </div>

            <form id="formNuevaSolicitud" novalidate>
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="erroresNueva">
                        <strong><i class="bi bi-exclamation-triangle me-1"></i>Corrija los siguientes errores:</strong>
                        <ul class="lista-errores mb-0 mt-1"></ul>
                    </div>

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
                                rows="4"
                                placeholder="Describa su solicitud con el mayor detalle posible…"
                                required
                            ></textarea>
                            <div class="form-text">Mínimo 10 caracteres.</div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" id="btnEnviarSolicitud" class="btn btn-primary">
                        <i class="bi bi-send me-1"></i> Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de actualizar estado -->
<div
    class="modal fade"
    id="modalActualizarEstado"
    tabindex="-1"
    aria-labelledby="modalActualizarEstadoLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalActualizarEstadoLabel">
                    <i class="bi bi-pencil-square me-2"></i>Actualizar Estado
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar"
                ></button>
            </div>

            <form id="formActualizarEstado">
                <div class="modal-body">
                    <input type="hidden" id="updateId">
                    <p class="mb-3">
                        Solicitud de:
                        <strong id="updateNombreDisplay"></strong>
                    </p>
                    <label for="updateEstado" class="form-label fw-semibold">
                        Nuevo Estado <span class="text-danger">*</span>
                    </label>
                    <select id="updateEstado" class="form-select" required>
                        <option value="pendiente">Pendiente</option>
                        <option value="en_revision">En Revisión</option>
                        <option value="aprobada">Aprobada</option>
                        <option value="rechazada">Rechazada</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" id="btnGuardarEstado" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Guardar Cambio
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de ver detalles -->
<div
    class="modal fade"
    id="modalDetalle"
    tabindex="-1"
    aria-labelledby="modalDetalleLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalleLabel">
                    <i class="bi bi-file-text me-2"></i>Detalle de Solicitud
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar"
                ></button>
            </div>
            <div class="modal-body" id="detalleContenido">
                <!-- Cargado dinamicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
