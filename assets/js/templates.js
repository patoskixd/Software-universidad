'use strict';


// Etiquetas legibles para los valores de la BD
const LABELS_TIPO = {
    academica: 'Académica',
    certificado: 'Certificado',
    actualizacion_datos: 'Actualización de Datos',
    otra: 'Otra',
};

const BADGE_ESTADO = {
    pendiente: { cls: 'bg-secondary', label: 'Pendiente' },
    en_revision: { cls: 'bg-warning text-dark', label: 'En Revisión' },
    aprobada: { cls: 'bg-success', label: 'Aprobada' },
    rechazada: { cls: 'bg-danger', label: 'Rechazada' },
};

// MySQL devuelve la fecha como "YYYY-MM-DD HH:MM:SS", hay que reemplazar el espacio
// para que Date() lo parsee bien en todos los navegadores
function formatFecha(iso) {
    if (!iso) return '—';
    return new Date(iso.replace(' ', 'T'))
        .toLocaleString('es-CL', { dateStyle: 'short', timeStyle: 'short' });
}

function labelTipo(tipo) {
    return LABELS_TIPO[tipo] ?? tipo;
}

function badgeEstado(estado) {
    const b = BADGE_ESTADO[estado] ?? { cls: 'bg-secondary', label: estado };
    return `<span class="badge ${b.cls}">${b.label}</span>`;
}

// Fila de la tabla principal
function buildTableRow(s) {
    return `
        <tr>
            <td class="ps-3 text-muted small">#${s.id}</td>
            <td>
                <div class="fw-semibold">${escapeHtml(s.nombre_solicitante)}</div>
                <small class="text-muted">${escapeHtml(s.correo_electronico)}</small>
            </td>
            <td class="d-none d-md-table-cell">${labelTipo(s.tipo_solicitud)}</td>
            <td>${badgeEstado(s.estado)}</td>
            <td class="text-nowrap text-muted small d-none d-lg-table-cell">${formatFecha(s.fecha_creacion)}</td>
            <td class="text-center pe-3">
                <button class="btn btn-sm btn-outline-secondary me-1 btn-ver-detalle"
                    title="Ver detalle" data-id="${s.id}">
                    <i class="bi bi-eye"></i>
                </button>
                <button class="btn btn-sm btn-outline-primary btn-cambiar-estado"
                    title="Cambiar estado"
                    data-id="${s.id}"
                    data-nombre="${escapeHtml(s.nombre_solicitante)}"
                    data-estado="${s.estado}">
                    <i class="bi bi-pencil"></i>
                </button>
            </td>
        </tr>`;
}

// Contenido del modal de ver detalle
function buildDetalleHtml(s) {
    return `
        <div class="row g-3">
            <div class="col-sm-6">
                <div class="detail-item">
                    <div class="detail-label"><i class="bi bi-hash me-1"></i>ID</div>
                    <div class="detail-value fw-semibold">#${s.id}</div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="detail-item">
                    <div class="detail-label"><i class="bi bi-tag me-1"></i>Tipo</div>
                    <div class="detail-value">${labelTipo(s.tipo_solicitud)}</div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="detail-item">
                    <div class="detail-label"><i class="bi bi-person me-1"></i>Solicitante</div>
                    <div class="detail-value fw-semibold">${escapeHtml(s.nombre_solicitante)}</div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="detail-item">
                    <div class="detail-label"><i class="bi bi-envelope me-1"></i>Correo</div>
                    <div class="detail-value">
                        <a href="mailto:${escapeHtml(s.correo_electronico)}" class="text-decoration-none">
                            ${escapeHtml(s.correo_electronico)}
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="detail-item">
                    <div class="detail-label"><i class="bi bi-circle-fill me-1"></i>Estado</div>
                    <div class="detail-value">${badgeEstado(s.estado)}</div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="detail-item">
                    <div class="detail-label"><i class="bi bi-calendar me-1"></i>Fecha</div>
                    <div class="detail-value">${formatFecha(s.fecha_creacion)}</div>
                </div>
            </div>
            <div class="col-12">
                <div class="detail-item mb-0">
                    <div class="detail-label"><i class="bi bi-chat-left-text me-1"></i>Descripción</div>
                    <div class="detail-value text-pre-wrap p-3 bg-light rounded border" style="font-size:.9rem;">
                        ${escapeHtml(s.descripcion)}
                    </div>
                </div>
            </div>
        </div>`;
}

// Tarjeta movil para la tabla de administración
function buildMobileCard(s) {
    return `
        <div class="p-3 border-bottom bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold text-dark">${escapeHtml(s.nombre_solicitante)}</span>
                <div>${badgeEstado(s.estado)}</div>
            </div>
            <div class="d-flex flex-column gap-1 mb-3 small text-muted">
                <div><i class="bi bi-hash text-secondary me-1"></i> ID: #${s.id}</div>
                <div><i class="bi bi-envelope text-secondary me-1"></i> ${escapeHtml(s.correo_electronico)}</div>
                <div><i class="bi bi-tag text-secondary me-1"></i> ${labelTipo(s.tipo_solicitud)}</div>
                <div><i class="bi bi-calendar3 text-secondary me-1"></i> ${formatFecha(s.fecha_creacion)}</div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-secondary flex-fill btn-ver-detalle"
                    data-id="${s.id}">
                    <i class="bi bi-eye"></i> Ver
                </button>
                <button class="btn btn-sm btn-outline-primary flex-fill btn-cambiar-estado"
                    data-id="${s.id}"
                    data-nombre="${escapeHtml(s.nombre_solicitante)}"
                    data-estado="${s.estado}">
                    <i class="bi bi-pencil"></i> Estado
                </button>
            </div>
        </div>`;
}

