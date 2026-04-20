<div id="alertContainer" role="alert" aria-live="polite"></div>

<!-- Admin header -->
<div class="admin-page-header mb-4" style="position:relative;overflow:hidden;">
    <!-- SVG decorativo -->
    <svg style="position:absolute;top:0;right:0;height:100%;opacity:.08;pointer-events:none;" viewBox="0 0 220 80" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="200" cy="40" r="90" fill="white"/>
        <circle cx="140" cy="0"  r="55" fill="white"/>
        <circle cx="180" cy="80" r="40" fill="white"/>
    </svg>
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3" style="position:relative;z-index:1;">
        <div>
            <h4 class="mb-1 fw-bold d-flex align-items-center gap-2">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm-1 14H5c-.55 0-1-.45-1-1V7c0-.55.45-1 1-1h14c.55 0 1 .45 1 1v10c0 .55-.45 1-1 1zM6 10h2v2H6zm0 4h8v2H6zm10 0h2v2h-2zm-6-4h8v2h-8z"/></svg>
                Gestión de Solicitudes
            </h4>
            <small class="text-white-50" id="totalCount">Cargando…</small>
        </div>
        <button class="btn btn-sm px-3 fw-semibold" style="background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.28);color:#fff;" data-bs-toggle="modal" data-bs-target="#modalNuevaSolicitud">
            <i class="bi bi-plus-lg me-1"></i> Nueva Solicitud
        </button>
    </div>
</div>

<!-- Stat cards -->
<div class="row g-3 mb-4" id="statsRow">
    <div class="col-6 col-md-3">
        <div class="stat-card stat-pending">
            <i class="bi bi-hourglass-split stat-icon"></i>
            <span class="stat-number" id="statPendiente">–</span>
            <span class="stat-label">Pendientes</span>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-review">
            <i class="bi bi-eye stat-icon"></i>
            <span class="stat-number" id="statRevision">–</span>
            <span class="stat-label">En Revisión</span>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-approved">
            <i class="bi bi-check-circle stat-icon"></i>
            <span class="stat-number" id="statAprobada">–</span>
            <span class="stat-label">Aprobadas</span>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-rejected">
            <i class="bi bi-x-circle stat-icon"></i>
            <span class="stat-number" id="statRechazada">–</span>
            <span class="stat-label">Rechazadas</span>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card filter-card mb-4">
    <div class="card-body">
        <form id="filterForm" class="row g-2 align-items-end">
            <div class="col-sm-6 col-md-3">
                <label for="filterEstado" class="form-label">Estado</label>
                <select id="filterEstado" class="form-select form-select-sm">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="en_revision">En Revisión</option>
                    <option value="aprobada">Aprobada</option>
                    <option value="rechazada">Rechazada</option>
                </select>
            </div>
            <div class="col-sm-6 col-md-3">
                <label for="filterTipo" class="form-label">Tipo</label>
                <select id="filterTipo" class="form-select form-select-sm">
                    <option value="">Todos los tipos</option>
                    <option value="academica">Académica</option>
                    <option value="certificado">Certificado</option>
                    <option value="actualizacion_datos">Actualización de Datos</option>
                    <option value="otra">Otra</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="filterTexto" class="form-label">Búsqueda</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input
                        type="text"
                        id="filterTexto"
                        class="form-control border-start-0 ps-0"
                        placeholder="Nombre o correo…"
                        maxlength="100"
                    >
                </div>
            </div>
            <div class="col-auto">
                <button type="button" id="btnLimpiarFiltros" class="btn btn-sm btn-outline-secondary" title="Limpiar filtros">
                    <i class="bi bi-x-lg me-1"></i>
                    <span class="d-none d-sm-inline">Limpiar</span>
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
                <thead>
                    <tr>
                        <th class="ps-3 sortable user-select-none" data-sort="id" title="Ordenar por ID">
                            ID <i class="bi bi-arrow-down-up text-muted ms-1 sort-icon" style="font-size:.7rem;"></i>
                        </th>
                        <th class="sortable user-select-none" data-sort="nombre_solicitante" title="Ordenar por Solicitante">
                            Solicitante <i class="bi bi-arrow-down-up text-muted ms-1 sort-icon" style="font-size:.7rem;"></i>
                        </th>
                        <th class="sortable user-select-none d-none d-md-table-cell" data-sort="tipo_solicitud" title="Ordenar por Tipo">
                            Tipo <i class="bi bi-arrow-down-up text-muted ms-1 sort-icon" style="font-size:.7rem;"></i>
                        </th>
                        <th class="sortable user-select-none" data-sort="estado" title="Ordenar por Estado">
                            Estado <i class="bi bi-arrow-down-up text-muted ms-1 sort-icon" style="font-size:.7rem;"></i>
                        </th>
                        <th class="sortable user-select-none d-none d-lg-table-cell" data-sort="fecha_creacion" title="Ordenar por Fecha">
                            Fecha <i class="bi bi-arrow-down-up text-muted ms-1 sort-icon" style="font-size:.7rem;"></i>
                        </th>
                        <th class="text-center pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody id="solicitudesTableBody">
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 p-3 border-top bg-light">
            <small class="text-muted" id="paginationInfo">Mostrando 0 a 0 de 0 resultados</small>
            <nav aria-label="Páginas">
                <ul class="pagination pagination-sm mb-0" id="paginationContainer"></ul>
            </nav>
        </div>
    </div>
</div>


<!-- ===== Modal: Nueva Solicitud ===== -->
<div class="modal fade" id="modalNuevaSolicitud" tabindex="-1" aria-labelledby="modalNuevaSolicitudLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalNuevaSolicitudLabel">
                    <i class="bi bi-file-earmark-plus me-2"></i>Nueva Solicitud
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formNuevaSolicitud" novalidate>
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="erroresNueva">
                        <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Corrija los siguientes errores:</strong>
                        <ul class="lista-errores mb-0 mt-1"></ul>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="inputNombre" class="form-label">Nombre completo <span class="text-danger">*</span></label>
                            <input type="text" id="inputNombre" name="nombre_solicitante" class="form-control" maxlength="150" required>
                        </div>
                        <div class="col-md-6">
                            <label for="inputCorreo" class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                            <input type="email" id="inputCorreo" name="correo_electronico" class="form-control" maxlength="150" required>
                        </div>
                        <div class="col-md-6">
                            <label for="inputTipo" class="form-label">Tipo de solicitud <span class="text-danger">*</span></label>
                            <select id="inputTipo" name="tipo_solicitud" class="form-select" required>
                                <option value="">Seleccione…</option>
                                <option value="academica">Académica</option>
                                <option value="certificado">Certificado</option>
                                <option value="actualizacion_datos">Actualización de Datos</option>
                                <option value="otra">Otra</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="inputDescripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                            <textarea id="inputDescripcion" name="descripcion" class="form-control" rows="4"
                                placeholder="Describa su solicitud con el mayor detalle posible…" required></textarea>
                            <div class="form-text">Mínimo 10 caracteres.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnEnviarSolicitud" class="btn btn-primary">
                        <i class="bi bi-send me-1"></i> Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ===== Modal: Actualizar Estado ===== -->
<div class="modal fade" id="modalActualizarEstado" tabindex="-1" aria-labelledby="modalActualizarEstadoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalActualizarEstadoLabel">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Actualizar Estado
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formActualizarEstado">
                <div class="modal-body">
                    <input type="hidden" id="updateId">
                    <div class="alert alert-info mb-3">
                        <i class="bi bi-person me-1"></i>
                        Solicitud de: <strong id="updateNombreDisplay"></strong>
                    </div>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnGuardarEstado" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Guardar Cambio
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ===== Modal: Ver Detalle ===== -->
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-labelledby="modalDetalleLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalleLabel">
                    <i class="bi bi-file-text me-2 text-primary"></i>Detalle de Solicitud
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="detalleContenido"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
