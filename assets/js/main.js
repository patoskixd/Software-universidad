'use strict';

const API_URL = 'api/solicitudes.php';

let modalNueva, modalEstado, modalDetalle;
let filtrosActivos = {};


const LABELS_TIPO = {
    academica:           'Académica',
    certificado:         'Certificado',
    actualizacion_datos: 'Actualización de Datos',
    otra:                'Otra',
};

const BADGE_ESTADO = {
    pendiente:   { cls: 'bg-secondary',        label: 'Pendiente'   },
    en_revision: { cls: 'bg-warning text-dark', label: 'En Revisión' },
    aprobada:    { cls: 'bg-success',           label: 'Aprobada'    },
    rechazada:   { cls: 'bg-danger',            label: 'Rechazada'   },
};

function badgeEstado(estado) {
    const b = BADGE_ESTADO[estado] ?? { cls: 'bg-secondary', label: estado };
    return `<span class="badge ${b.cls}">${b.label}</span>`;
}

function labelTipo(tipo) {
    return LABELS_TIPO[tipo] ?? tipo;
}

function formatFecha(iso) {
    if (!iso) return '—';
    const d = new Date(iso.replace(' ', 'T'));
    return d.toLocaleString('es-CL', {
        dateStyle: 'short',
        timeStyle: 'short',
    });
}

async function fetchJSON(url, options = {}) {
    const res = await fetch(url, options);
    const data = await res.json();
    if (!res.ok) {
        const msg = Array.isArray(data.errors)
            ? data.errors
            : [data.error ?? 'Error desconocido'];
        throw { status: res.status, messages: msg };
    }
    return data;
}

//  tabla 

function buildTableRow(s) {
    return `
        <tr>
            <td class="ps-3 text-muted">#${s.id}</td>
            <td>
                <div class="fw-semibold">${escapeHtml(s.nombre_solicitante)}</div>
                <small class="text-muted">${escapeHtml(s.correo_electronico)}</small>
            </td>
            <td>${labelTipo(s.tipo_solicitud)}</td>
            <td>${badgeEstado(s.estado)}</td>
            <td class="text-nowrap text-muted">${formatFecha(s.fecha_creacion)}</td>
            <td class="text-center pe-3">
                <button
                    class="btn btn-sm btn-outline-secondary me-1 btn-ver-detalle"
                    title="Ver detalle"
                    data-id="${s.id}"
                >
                    <i class="bi bi-eye"></i>
                </button>
                <button
                    class="btn btn-sm btn-outline-primary btn-cambiar-estado"
                    title="Cambiar estado"
                    data-id="${s.id}"
                    data-nombre="${escapeHtml(s.nombre_solicitante)}"
                    data-estado="${s.estado}"
                >
                    <i class="bi bi-pencil"></i>
                </button>
            </td>
        </tr>
    `;
}

function renderTabla(solicitudes) {
    const tbody  = document.getElementById('solicitudesTableBody');
    const totalEl = document.getElementById('totalCount');

    if (!solicitudes.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                    No se encontraron solicitudes con los filtros aplicados.
                </td>
            </tr>`;
        totalEl.textContent = '0 solicitudes';
        return;
    }

    tbody.innerHTML = solicitudes.map(buildTableRow).join('');
    totalEl.textContent = `${solicitudes.length} solicitud${solicitudes.length !== 1 ? 'es' : ''}`;
}

//  carga con filtros 

async function cargarSolicitudes(filtros = {}) {
    const params = new URLSearchParams();
    if (filtros.estado)         params.set('estado', filtros.estado);
    if (filtros.tipo_solicitud) params.set('tipo_solicitud', filtros.tipo_solicitud);
    if (filtros.texto)          params.set('texto', filtros.texto);

    const url = `${API_URL}${params.toString() ? '?' + params : ''}`;

    try {
        const data = await fetchJSON(url);
        renderTabla(data);
    } catch (err) {
        mostrarAlerta('danger', err.messages?.[0] ?? 'Error al cargar las solicitudes.');
    }
}

//  modal detalle 

async function verDetalle(id) {
    const contenedor = document.getElementById('detalleContenido');
    contenedor.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>';
    modalDetalle.show();

    try {
        const s = await fetchJSON(`${API_URL}?id=${id}`);
        contenedor.innerHTML = `
            <dl class="row mb-0">
                <dt class="col-sm-4">ID</dt>
                <dd class="col-sm-8">#${s.id}</dd>

                <dt class="col-sm-4">Nombre</dt>
                <dd class="col-sm-8">${escapeHtml(s.nombre_solicitante)}</dd>

                <dt class="col-sm-4">Correo</dt>
                <dd class="col-sm-8">${escapeHtml(s.correo_electronico)}</dd>

                <dt class="col-sm-4">Tipo</dt>
                <dd class="col-sm-8">${labelTipo(s.tipo_solicitud)}</dd>

                <dt class="col-sm-4">Estado</dt>
                <dd class="col-sm-8">${badgeEstado(s.estado)}</dd>

                <dt class="col-sm-4">Fecha</dt>
                <dd class="col-sm-8">${formatFecha(s.fecha_creacion)}</dd>

                <dt class="col-sm-4">Descripción</dt>
                <dd class="col-sm-8 text-pre-wrap">${escapeHtml(s.descripcion)}</dd>
            </dl>
        `;
    } catch (err) {
        contenedor.innerHTML = `<div class="alert alert-danger">${err.messages?.[0] ?? 'No se pudo cargar el detalle.'}</div>`;
    }
}

//  modal estado 

function abrirModalEstado(id, nombre, estadoActual) {
    document.getElementById('updateId').value                = id;
    document.getElementById('updateNombreDisplay').textContent = nombre;
    document.getElementById('updateEstado').value             = estadoActual;
    modalEstado.show();
}

async function guardarEstado(e) {
    e.preventDefault();

    const id     = parseInt(document.getElementById('updateId').value, 10);
    const estado = document.getElementById('updateEstado').value;
    const btn    = document.getElementById('btnGuardarEstado');

    btn.disabled  = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Guardando…';

    try {
        await fetchJSON(API_URL, {
            method:  'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ id, estado }),
        });

        modalEstado.hide();
        mostrarAlerta('success', 'Estado actualizado correctamente.');
        await cargarSolicitudes(filtrosActivos);
    } catch (err) {
        mostrarAlerta('danger', err.messages?.[0] ?? 'Error al actualizar el estado.');
    } finally {
        btn.disabled  = false;
        btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Guardar Cambio';
    }
}

//  modal nueva solicitud 

async function enviarNuevaSolicitud(e) {
    e.preventDefault();

    const form   = document.getElementById('formNuevaSolicitud');
    const errBox = document.getElementById('erroresNueva');
    const btn    = document.getElementById('btnEnviarSolicitud');

    // limpiar errores previos
    errBox.classList.add('d-none');
    errBox.querySelector('.lista-errores').innerHTML = '';

    const payload = {
        nombre_solicitante:  form.nombre_solicitante.value.trim(),
        correo_electronico:  form.correo_electronico.value.trim(),
        tipo_solicitud:      form.tipo_solicitud.value,
        descripcion:         form.descripcion.value.trim(),
    };

    btn.disabled  = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Enviando…';

    try {
        await fetchJSON(API_URL, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify(payload),
        });

        modalNueva.hide();
        form.reset();
        mostrarAlerta('success', 'Solicitud registrada exitosamente.');
        await cargarSolicitudes(filtrosActivos);
    } catch (err) {
        const lista = errBox.querySelector('.lista-errores');
        (err.messages ?? ['Error al registrar la solicitud.']).forEach(m => {
            const li = document.createElement('li');
            li.textContent = m;
            lista.appendChild(li);
        });
        errBox.classList.remove('d-none');
    } finally {
        btn.disabled  = false;
        btn.innerHTML = '<i class="bi bi-send me-1"></i> Enviar Solicitud';
    }
}

//  alertas 

function mostrarAlerta(tipo, mensaje, duracion = 5000) {
    const container = document.getElementById('alertContainer');
    const id = 'alert-' + Date.now();

    const html = `
        <div id="${id}" class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            ${escapeHtml(mensaje)}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);

    if (duracion > 0) {
        setTimeout(() => {
            const el = document.getElementById(id);
            if (el) el.remove();
        }, duracion);
    }
}

//  utils 

function escapeHtml(str) {
    if (str == null) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

//  init 

document.addEventListener('DOMContentLoaded', () => {
    // Instanciar modales de Bootstrap
    modalNueva   = new bootstrap.Modal(document.getElementById('modalNuevaSolicitud'));
    modalEstado  = new bootstrap.Modal(document.getElementById('modalActualizarEstado'));
    modalDetalle = new bootstrap.Modal(document.getElementById('modalDetalle'));

    //  Listeners de formularios 
    document.getElementById('formNuevaSolicitud')
        .addEventListener('submit', enviarNuevaSolicitud);

    document.getElementById('formActualizarEstado')
        .addEventListener('submit', guardarEstado);

    // limpiar errores al cerrar modal de nueva solicitud
    document.getElementById('modalNuevaSolicitud')
        .addEventListener('hidden.bs.modal', () => {
            const errBox = document.getElementById('erroresNueva');
            errBox.classList.add('d-none');
            errBox.querySelector('.lista-errores').innerHTML = '';
            document.getElementById('formNuevaSolicitud').reset();
        });

    //  delegacion de eventos para botones de la tabla 
    document.getElementById('solicitudesTableBody').addEventListener('click', (e) => {
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
                btnEstado.dataset.estado
            );
        }
    });

    //  filtros 
    document.getElementById('filterForm').addEventListener('submit', e => {
        e.preventDefault();
        filtrosActivos = {
            estado:         document.getElementById('filterEstado').value,
            tipo_solicitud: document.getElementById('filterTipo').value,
            texto:          document.getElementById('filterTexto').value.trim(),
        };
        cargarSolicitudes(filtrosActivos);
    });

    document.getElementById('btnLimpiarFiltros').addEventListener('click', () => {
        document.getElementById('filterEstado').value = '';
        document.getElementById('filterTipo').value   = '';
        document.getElementById('filterTexto').value  = '';
        filtrosActivos = {};
        cargarSolicitudes();
    });

    //  Carga inicial 
    cargarSolicitudes();
});
