'use strict';


const API_URL = 'api/solicitudes.php';

async function enviarSolicitud(e) {
    e.preventDefault();

    const form = document.getElementById('formPublica');
    const errBox = document.getElementById('erroresNueva');
    const btn = document.getElementById('btnEnviarSolicitud');

    errBox.classList.add('d-none');
    errBox.querySelector('.lista-errores').innerHTML = '';

    const payload = {
        nombre_solicitante: form.nombre_solicitante.value.trim(),
        correo_electronico: form.correo_electronico.value.trim(),
        tipo_solicitud: form.tipo_solicitud.value,
        descripcion: form.descripcion.value.trim(),
    };

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Enviando…';

    try {
        const res = await fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': getCsrfToken(),
            },
            body: JSON.stringify(payload),
        });
        const data = await res.json();

        if (!res.ok) {
            const lista = errBox.querySelector('.lista-errores');
            const msgs = Array.isArray(data.errors) ? data.errors : [data.error ?? 'Error desconocido.'];
            msgs.forEach(m => {
                const li = document.createElement('li');
                li.textContent = m;
                lista.appendChild(li);
            });
            errBox.classList.remove('d-none');
            return;
        }

        form.reset();
        mostrarAlerta('success', 'Solicitud enviada correctamente. Puede consultar el estado usando su correo electrónico.');
    } catch (_) {
        mostrarAlerta('danger', 'No se pudo enviar la solicitud. Intente más tarde.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-send me-1"></i> Enviar solicitud';
    }
}

async function consultarSolicitudes(e) {
    e.preventDefault();

    const correo = document.getElementById('inputConsultaCorreo').value.trim();
    const contenedor = document.getElementById('consultaResultados');
    const btn = e.submitter;

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Buscando…';

    try {
        const params = new URLSearchParams({ correo });
        const res = await fetch(`${API_URL}?${params}`);
        const data = await res.json();

        if (!res.ok) {
            contenedor.innerHTML = '';
            mostrarAlerta('danger', data.error ?? 'Error al consultar.');
            return;
        }

        if (!data.length) {
            contenedor.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                    No se encontraron solicitudes para ese correo.
                </div>`;
            return;
        }

        let filasEscritorio = '';
        let filasMovil = '';

        data.forEach(s => {
            let descCorta = s.descripcion || '';
            if (descCorta.length > 15) {
                descCorta = descCorta.substring(0, 15) + '...';
            }
            const jsonStr = encodeURIComponent(JSON.stringify(s));

            // Fila para escritorio
            filasEscritorio += `
            <tr>
                <td>${escapeHtml(labelTipo(s.tipo_solicitud))}</td>
                <td><span title="${escapeHtml(s.descripcion || '')}" style="cursor:help;">${escapeHtml(descCorta)}</span></td>
                <td>${badgeEstado(s.estado)}</td>
                <td class="text-muted small">${formatFecha(s.fecha_actualizacion || s.fecha_creacion)}</td>
                <td class="text-muted small">
                    ${s.observaciones
                        ? `<span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" title="${escapeHtml(s.observaciones)}" style="cursor:help;max-width:100px;display:inline-block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                               <i class="bi bi-chat-left-dots me-1"></i>${escapeHtml(s.observaciones.length > 12 ? s.observaciones.substring(0,12) + '…' : s.observaciones)}
                           </span>`
                        : '<span class="text-muted">—</span>'}
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-primary shadow-sm text-nowrap" onclick="verDescripcionCompleta(this)" data-json="${jsonStr}">
                        <i class="bi bi-eye"></i> Ver
                    </button>
                </td>
            </tr>`;

            // Tarjeta para móvil
            filasMovil += `
            <div class="p-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold text-dark">${escapeHtml(s.nombre_solicitante || '')}</span>
                    <div>${badgeEstado(s.estado)}</div>
                </div>
                <div class="d-flex flex-column gap-1 mb-3 small text-muted">
                    <div><i class="bi bi-tag text-secondary me-1"></i> ${escapeHtml(labelTipo(s.tipo_solicitud))}</div>
                    <div><i class="bi bi-calendar3 text-secondary me-1"></i> ${formatFecha(s.fecha_creacion)}</div>
                    ${s.observaciones ? `<div class="mt-1 p-2 rounded" style="background:#fff3cd;color:#7d5a00;"><i class="bi bi-chat-left-dots-fill me-1"></i><strong>Observaciones:</strong> ${escapeHtml(s.observaciones)}</div>` : ''}
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary w-100 shadow-sm" onclick="verDescripcionCompleta(this)" data-json="${jsonStr}">
                    <i class="bi bi-eye"></i> Ver detalle completo
                </button>
            </div>`;
        });

        contenedor.innerHTML = `
            <div class="border-top">
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Tipo</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                                <th>Últ. actualización</th>
                                <th>Observaciones</th>
                                <th class="text-center pe-3">Acción</th>
                            </tr>
                        </thead>
                        <tbody>${filasEscritorio}</tbody>
                    </table>
                </div>
                <div class="d-md-none">${filasMovil}</div>
            </div>`;
    } catch (_) {
        contenedor.innerHTML = '';
        mostrarAlerta('danger', 'No se pudo conectar. Intente más tarde.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-search me-1"></i> Buscar';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('formPublica').addEventListener('submit', enviarSolicitud);
    document.getElementById('formConsulta').addEventListener('submit', consultarSolicitudes);
});

window.verDescripcionCompleta = function (btn) {
    const s = JSON.parse(decodeURIComponent(btn.getAttribute('data-json')));

    document.getElementById('modalDetalleTipo').textContent = labelTipo(s.tipo_solicitud);
    document.getElementById('modalDetalleSolicitante').textContent = s.nombre_solicitante || '';
    document.getElementById('modalDetalleCorreo').textContent = s.correo_electronico || '';
    document.getElementById('modalDetalleEstado').innerHTML = badgeEstado(s.estado);
    document.getElementById('modalDetalleFecha').textContent = s.fecha_creacion ? formatFecha(s.fecha_creacion) : '-';
    document.getElementById('modalDetalleFechaMod').textContent = s.fecha_actualizacion ? formatFecha(s.fecha_actualizacion) : 'Sin modificaciones recientes';
    document.getElementById('descripcionCompletaTexto').textContent = s.descripcion || 'Sin descripción';

    // Mostrar observaciones del administrador solo si existen
    const cajaFeedback = document.getElementById('cajaFeedbackUsuario');
    const feedbackTexto = document.getElementById('feedbackTextoUsuario');
    if (s.observaciones && s.observaciones.trim() !== '') {
        feedbackTexto.textContent = s.observaciones;
        cajaFeedback.classList.remove('d-none');
    } else {
        feedbackTexto.textContent = '';
        cajaFeedback.classList.add('d-none');
    }

    const modalVerDescripcion = new bootstrap.Modal(document.getElementById('modalVerDescripcion'));
    modalVerDescripcion.show();
};
