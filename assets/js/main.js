'use strict';
// Requiere utils.js y templates.js cargados antes

const API_URL = 'api/solicitudes.php';

// referencias a los modales Bootstrap, se inicializan en DOMContentLoaded
let modalEstado, modalDetalle;

let filtrosActivos = {};
let solicitudesActuales = [];
let sortCol = 'id';
let sortDesc = true;
let paginaActual = 1;

async function fetchJSON(url, options = {}) {
    const method = (options.method ?? 'GET').toUpperCase();

    if (method !== 'GET' && method !== 'HEAD') {
        options.headers = {
            ...options.headers,
            'X-CSRF-Token': getCsrfToken(),
        };
    }

    const res = await fetch(url, options);
    const data = await res.json();
    if (!res.ok) {
        const messages = Array.isArray(data.errors)
            ? data.errors
            : [data.error ?? 'Error desconocido'];
        throw { status: res.status, messages };
    }
    return data;
}

function renderTabla(solicitudes) {
    const tbody = document.getElementById('solicitudesTableBody');
    const mbody = document.getElementById('solicitudesMobileBody');
    if (!solicitudes.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                    No se encontraron solicitudes con los filtros aplicados.
                </td>
            </tr>`;
        if (mbody) {
            mbody.innerHTML = `
                <div class="text-center text-muted py-5 bg-white border-bottom">
                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                    No se encontraron solicitudes.
                </div>`;
        }
        return;
    }

    tbody.innerHTML = solicitudes.map(buildTableRow).join('');
    if (mbody) {
        mbody.innerHTML = solicitudes.map(buildMobileCard).join('');
    }
}

// Pide el total de cada estado por separado para mostrar en las tarjetas
async function actualizarStats() {
    const mapaIds = {
        pendiente: 'statPendiente',
        en_revision: 'statRevision',
        aprobada: 'statAprobada',
        rechazada: 'statRechazada',
    };

    Object.values(mapaIds).forEach(id => {
        const el = document.getElementById(id);
        if (el) el.textContent = '0';
    });

    try {
        const data = await fetchJSON(`${API_URL}?stats=1`);
        data.forEach(item => {
            const elId = mapaIds[item.estado];
            if (elId) {
                const el = document.getElementById(elId);
                if (el) el.textContent = item.total;
            }
        });
    } catch (_) { }
}

async function cargarSolicitudes(filtros = {}) {
    const params = new URLSearchParams();
    if (filtros.estado) params.set('estado', filtros.estado);
    if (filtros.tipo_solicitud) params.set('tipo_solicitud', filtros.tipo_solicitud);
    if (filtros.texto) params.set('texto', filtros.texto);
    params.set('page', paginaActual);
    params.set('limit', 10);

    try {
        const response = await fetchJSON(`${API_URL}?${params.toString()}`);
        solicitudesActuales = response.data;
        aplicarOrden();
        renderPaginacion(response);
        actualizarStats();
    } catch (err) {
        mostrarAlerta('danger', err.messages?.[0] ?? 'Error al cargar las solicitudes.');
    }
}

function renderPaginacion(info) {
    const totalEl = document.getElementById('totalCount');
    const pagInfo = document.getElementById('paginationInfo');
    const pagContainer = document.getElementById('paginationContainer');

    if (!info.total) {
        totalEl.textContent = '0 solicitudes';
        if (pagInfo) pagInfo.textContent = 'No hay resultados';
        if (pagContainer) pagContainer.innerHTML = '';
        return;
    }

    const { page, limit, total, last_page } = info;

    totalEl.textContent = `${total} solicitud${total !== 1 ? 'es' : ''}`;

    if (pagInfo) {
        const start = (page - 1) * limit + 1;
        const end = Math.min(page * limit, total);
        pagInfo.textContent = `Mostrando ${start} a ${end} de ${total} resultados`;
    }

    if (!pagContainer) return;

    let html = `
        <li class="page-item ${page <= 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${page - 1}" aria-label="Anterior">&laquo;</a>
        </li>`;

    for (let i = 1; i <= last_page; i++) {
        if (i === 1 || i === last_page || (i >= page - 2 && i <= page + 2)) {
            html += `<li class="page-item ${i === page ? 'active' : ''}">
                         <a class="page-link" href="#" data-page="${i}">${i}</a>
                     </li>`;
        } else if (i === page - 3 || i === page + 3) {
            html += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
        }
    }

    html += `
        <li class="page-item ${page >= last_page ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${page + 1}" aria-label="Siguiente">&raquo;</a>
        </li>`;

    pagContainer.innerHTML = html;

    pagContainer.querySelectorAll('a.page-link').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            if (!e.currentTarget.parentElement.classList.contains('disabled')) {
                paginaActual = parseInt(e.currentTarget.dataset.page, 10);
                cargarSolicitudes(filtrosActivos);
            }
        });
    });
}

function aplicarOrden() {
    if (!solicitudesActuales.length) {
        renderTabla([]);
        actualizarIconosSort();
        return;
    }

    solicitudesActuales.sort((a, b) => {
        let valA = a[sortCol];
        let valB = b[sortCol];

        if (sortCol === 'id') {
            valA = parseInt(valA, 10);
            valB = parseInt(valB, 10);
        } else if (valA && valB) {
            valA = valA.toString().toLowerCase();
            valB = valB.toString().toLowerCase();
        }

        if (valA < valB) return sortDesc ? 1 : -1;
        if (valA > valB) return sortDesc ? -1 : 1;
        return 0;
    });

    renderTabla(solicitudesActuales);
    actualizarIconosSort();
}

function actualizarIconosSort() {
    document.querySelectorAll('.sortable').forEach(th => {
        const icon = th.querySelector('.sort-icon');
        if (!icon) return;
        icon.className = 'bi bi-arrow-down-up text-muted ms-1 sort-icon';
        if (th.dataset.sort === sortCol) {
            icon.className = sortDesc
                ? 'bi bi-arrow-down text-primary ms-1 sort-icon'
                : 'bi bi-arrow-up   text-primary ms-1 sort-icon';
        }
    });
}

async function verDetalle(id) {
    const contenedor = document.getElementById('detalleContenido');
    contenedor.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>';
    modalDetalle.show();

    try {
        const s = await fetchJSON(`${API_URL}?id=${id}`);
        contenedor.innerHTML = buildDetalleHtml(s);
    } catch (err) {
        contenedor.innerHTML = `<div class="alert alert-danger">${err.messages?.[0] ?? 'No se pudo cargar el detalle.'}</div>`;
    }
}

function abrirModalEstado(id, nombre, estadoActual, observacionesAnteriores) {
    document.getElementById('updateId').value = id;
    document.getElementById('updateNombreDisplay').textContent = nombre;

    const selEstado = document.getElementById('updateEstado');
    selEstado.value = estadoActual;

    const areaObs = document.getElementById('updateObservaciones');
    areaObs.value = observacionesAnteriores ?? '';

    // Disparar evento change manualmente para ocultar/mostrar el contenedor
    selEstado.dispatchEvent(new Event('change'));

    modalEstado.show();
}

async function guardarEstado(e) {
    e.preventDefault();

    const id = parseInt(document.getElementById('updateId').value, 10);
    const estado = document.getElementById('updateEstado').value;
    const obs = document.getElementById('updateObservaciones').value;
    const btn = document.getElementById('btnGuardarEstado');

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Guardando…';

    try {
        await fetchJSON(API_URL, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, estado, observaciones: obs }),
        });
        modalEstado.hide();
        mostrarAlerta('success', 'Estado actualizado correctamente.');
        await cargarSolicitudes(filtrosActivos);
    } catch (err) {
        mostrarAlerta('danger', err.messages?.[0] ?? 'Error al actualizar el estado.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Guardar Cambio';
    }
}


document.addEventListener('DOMContentLoaded', () => {
    modalEstado = new bootstrap.Modal(document.getElementById('modalActualizarEstado'));
    modalDetalle = new bootstrap.Modal(document.getElementById('modalDetalle'));

    document.getElementById('formActualizarEstado').addEventListener('submit', guardarEstado);

    // Un solo listener en el contenedor padre para manejar la tabla (desktop) y las tarjetas (mobile) simultáneamente
    document.getElementById('solicitudesContainer').addEventListener('click', e => {
        const btnDetalle = e.target.closest('.btn-ver-detalle');
        if (btnDetalle) {
            verDetalle(parseInt(btnDetalle.dataset.id, 10));
            return;
        }
        const btnEstado = e.target.closest('.btn-cambiar-estado');
        if (btnEstado) {
            abrirModalEstado(
                parseInt(btnEstado.dataset.id, 10),
                btnEstado.dataset.nombre,
                btnEstado.dataset.estado,
                btnEstado.dataset.observaciones
            );
        }
    });

    document.getElementById('updateEstado').addEventListener('change', e => {
        const estado = e.target.value;
        const cont = document.getElementById('contenedorObservaciones');
        if (estado === 'aprobada' || estado === 'rechazada') {
            cont.classList.remove('d-none');
        } else {
            cont.classList.add('d-none');
        }
    });

    const aplicarFiltros = () => {
        filtrosActivos = {
            estado: document.getElementById('filterEstado').value,
            tipo_solicitud: document.getElementById('filterTipo').value,
            texto: document.getElementById('filterTexto').value.trim(),
        };
        paginaActual = 1;
        cargarSolicitudes(filtrosActivos);
    };

    document.getElementById('filterForm').addEventListener('submit', e => { e.preventDefault(); aplicarFiltros(); });
    document.getElementById('filterEstado').addEventListener('change', aplicarFiltros);
    document.getElementById('filterTipo').addEventListener('change', aplicarFiltros);

    // Pequeño delay para no lanzar la búsqueda en cada tecla
    let debounceTimer;
    document.getElementById('filterTexto').addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(aplicarFiltros, 300);
    });

    document.getElementById('btnLimpiarFiltros').addEventListener('click', () => {
        document.getElementById('filterEstado').value = '';
        document.getElementById('filterTipo').value = '';
        document.getElementById('filterTexto').value = '';
        filtrosActivos = {};
        paginaActual = 1;
        cargarSolicitudes();
    });

    document.querySelectorAll('.sortable').forEach(th => {
        th.addEventListener('click', () => {
            const col = th.dataset.sort;
            if (sortCol === col) {
                sortDesc = !sortDesc;
            } else {
                sortCol = col;
                // id y fecha muestran los más recientes primero por defecto
                sortDesc = col === 'id' || col === 'fecha_creacion';
            }
            aplicarOrden();
        });
    });

    cargarSolicitudes();
});
