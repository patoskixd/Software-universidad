<!-- Admin header -->
<div class="admin-page-header mb-4" style="position:relative;overflow:hidden;">
    <!-- SVG decorativo -->
    <svg style="position:absolute;top:0;right:0;height:100%;opacity:.08;pointer-events:none;" viewBox="0 0 220 80"
        fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="200" cy="40" r="90" fill="white" />
        <circle cx="140" cy="0" r="55" fill="white" />
        <circle cx="180" cy="80" r="40" fill="white" />
    </svg>
    <div style="position:relative;z-index:1;">
        <h4 class="mb-1 fw-bold d-flex align-items-center gap-2">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm-1 14H5c-.55 0-1-.45-1-1V7c0-.55.45-1 1-1h14c.55 0 1 .45 1 1v10c0 .55-.45 1-1 1zM6 10h2v2H6zm0 4h8v2H6zm10 0h2v2h-2zm-6-4h8v2h-8z" />
            </svg>
            Gestión de Solicitudes
        </h4>
        <small class="text-white-50" id="totalCount">Cargando…</small>
    </div>
</div>

<!-- Stat cards -->
<div class="row g-3 mb-4" id="statsRow">
    <div class="col-6 col-md-3">
        <div class="stat-card stat-pending">
            <div class="stat-top-line"></div>
            <i class="bi bi-hourglass-split stat-icon"></i>
            <span class="stat-number" id="statPendiente">–</span>
            <span class="stat-label">Pendientes</span>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-review">
            <div class="stat-top-line"></div>
            <i class="bi bi-eye stat-icon"></i>
            <span class="stat-number" id="statRevision">–</span>
            <span class="stat-label">En Revisión</span>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-approved">
            <div class="stat-top-line"></div>
            <i class="bi bi-check-circle stat-icon"></i>
            <span class="stat-number" id="statAprobada">–</span>
            <span class="stat-label">Aprobadas</span>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-rejected">
            <div class="stat-top-line"></div>
            <i class="bi bi-x-circle stat-icon"></i>
            <span class="stat-number" id="statRechazada">–</span>
            <span class="stat-label">Rechazadas</span>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card filter-card mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
            <i class="bi bi-funnel-fill" style="color:var(--blue);font-size:.85rem;"></i>
            <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:var(--sub);">Filtrar solicitudes</span>
        </div>
        <form id="filterForm" class="row g-3 align-items-end">
            <div class="col-sm-6 col-md-3">
                <label for="filterEstado" class="detail-label mb-1"><i class="bi bi-circle-fill"></i>Estado</label>
                <select id="filterEstado" class="form-select form-select-sm">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="en_revision">En Revisión</option>
                    <option value="aprobada">Aprobada</option>
                    <option value="rechazada">Rechazada</option>
                </select>
            </div>
            <div class="col-sm-6 col-md-3">
                <label for="filterTipo" class="detail-label mb-1"><i class="bi bi-tag"></i>Tipo</label>
                <select id="filterTipo" class="form-select form-select-sm">
                    <option value="">Todos los tipos</option>
                    <option value="academica">Académica</option>
                    <option value="certificado">Certificado</option>
                    <option value="actualizacion_datos">Actualización de Datos</option>
                    <option value="otra">Otra</option>
                </select>
            </div>
            <div class="col-sm-12 col-md-4">
                <label for="filterTexto" class="detail-label mb-1"><i class="bi bi-search"></i>Búsqueda</label>
                <input type="text" id="filterTexto" class="form-control form-control-sm"
                    placeholder="Nombre o correo…" maxlength="100">
            </div>
            <div class="col-sm-12 col-md-2 d-flex gap-2 justify-content-md-end">
                <button type="button" id="btnExportarExcel" class="btn btn-sm btn-success flex-grow-1 flex-md-grow-0" title="Exportar a Excel">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    <span class="d-none d-sm-inline">Exportar</span>
                </button>
                <button type="button" id="btnLimpiarFiltros" class="btn btn-sm btn-outline-secondary flex-grow-1 flex-md-grow-0" title="Limpiar filtros">
                    <i class="bi bi-x-lg me-1"></i>
                    <span class="d-none d-sm-inline">Limpiar</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tabla -->
<div class="card" id="solicitudesContainer">
    <div class="card-body p-0">
        <!-- Vista Escritorio -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3 sortable user-select-none" data-sort="id" title="Ordenar por ID">
                            ID <i class="bi bi-arrow-down-up text-muted ms-1 sort-icon" style="font-size:.7rem;"></i>
                        </th>
                        <th class="sortable user-select-none" data-sort="nombre_solicitante"
                            title="Ordenar por Solicitante">
                            Solicitante <i class="bi bi-arrow-down-up text-muted ms-1 sort-icon"
                                style="font-size:.7rem;"></i>
                        </th>
                        <th class="sortable user-select-none" data-sort="tipo_solicitud" title="Ordenar por Tipo">
                            Tipo <i class="bi bi-arrow-down-up text-muted ms-1 sort-icon" style="font-size:.7rem;"></i>
                        </th>
                        <th class="sortable user-select-none" data-sort="estado" title="Ordenar por Estado">
                            Estado <i class="bi bi-arrow-down-up text-muted ms-1 sort-icon"
                                style="font-size:.7rem;"></i>
                        </th>
                        <th class="sortable user-select-none" data-sort="fecha_creacion" title="Ordenar por Fecha">
                            Fecha Creación <i class="bi bi-arrow-down-up text-muted ms-1 sort-icon"
                                style="font-size:.7rem;"></i>
                        </th>
                        <th class="text-center pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody id="solicitudesTableBody">
                </tbody>
            </table>
        </div>

        <!-- Vista Móvil -->
        <div class="d-md-none" id="solicitudesMobileBody"></div>

        <!-- Paginación -->
        <div
            class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 p-3 border-top bg-light">
            <small class="text-muted" id="paginationInfo">Mostrando 0 a 0 de 0 resultados</small>
            <nav aria-label="Páginas">
                <ul class="pagination pagination-sm mb-0" id="paginationContainer"></ul>
            </nav>
        </div>
    </div>
</div>



<!--  Modal de atualizar estado  -->
<div class="modal fade" id="modalActualizarEstado" tabindex="-1" aria-labelledby="modalActualizarEstadoLabel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="modalActualizarEstadoLabel">
                    <i class="bi bi-pencil-square me-2"></i>Actualizar Estado
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"
                    onclick="this.blur()"></button>
            </div>
            <form id="formActualizarEstado">
                <div class="modal-body">
                    <input type="hidden" id="updateId">
                    <div class="d-flex align-items-center gap-2 p-3 mb-3 rounded" style="background:#f0f6ff;border:1px solid #bfdbfe;">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:34px;height:34px;background:var(--blue);color:#fff;">
                            <i class="bi bi-person-fill" style="font-size:.9rem;"></i>
                        </div>
                        <div class="lh-sm">
                            <div style="font-size:.67rem;text-transform:uppercase;letter-spacing:.08em;color:var(--blue);font-weight:700;">Solicitante</div>
                            <div class="fw-semibold" style="font-size:.95rem;color:var(--txt);" id="updateNombreDisplay"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="updateEstado" class="detail-label mb-1">
                            <i class="bi bi-circle-fill"></i>Nuevo Estado <span class="text-danger">*</span>
                        </label>
                        <select id="updateEstado" class="form-select" required>
                            <option value="pendiente">Pendiente</option>
                            <option value="en_revision">En Revisión</option>
                            <option value="aprobada">Aprobada</option>
                            <option value="rechazada">Rechazada</option>
                        </select>
                    </div>

                    <div id="contenedorObservaciones" class="d-none">
                        <label for="updateObservaciones" class="detail-label mb-1">
                            <i class="bi bi-chat-left-dots"></i>Observaciones <span class="text-muted fw-normal" style="text-transform:none;letter-spacing:0;">(Opcional)</span>
                        </label>
                        <textarea id="updateObservaciones" class="form-control" rows="3"
                            placeholder="Indique un motivo al estudiante..." maxlength="2000"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        onclick="this.blur()">Cancelar</button>
                    <button type="submit" id="btnGuardarEstado" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Guardar Cambio
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!--  Modal de ver detalle  -->
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-labelledby="modalDetalleLabel">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="modalDetalleLabel">
                    <i class="bi bi-file-text me-2"></i>Detalle de Solicitud
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"
                    onclick="this.blur()"></button>
            </div>
            <div class="modal-body" id="detalleContenido"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                    onclick="this.blur()">Cerrar</button>
            </div>
        </div>
    </div>
</div>