'use strict';
// Depende de: utils.js (debe cargarse antes)

const API_URL = 'api/solicitudes.php';

async function enviarSolicitud(e) {
    e.preventDefault();

    const form   = document.getElementById('formPublica');
    const errBox = document.getElementById('erroresNueva');
    const btn    = document.getElementById('btnEnviarSolicitud');

    errBox.classList.add('d-none');
    errBox.querySelector('.lista-errores').innerHTML = '';

    const payload = {
        nombre_solicitante: form.nombre_solicitante.value.trim(),
        correo_electronico: form.correo_electronico.value.trim(),
        tipo_solicitud:     form.tipo_solicitud.value,
        descripcion:        form.descripcion.value.trim(),
    };

    btn.disabled  = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Enviando…';

    try {
        const res  = await fetch(API_URL, {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': getCsrfToken(),
            },
            body:    JSON.stringify(payload),
        });
        const data = await res.json();

        if (!res.ok) {
            const lista = errBox.querySelector('.lista-errores');
            const msgs  = Array.isArray(data.errors) ? data.errors : [data.error ?? 'Error desconocido.'];
            msgs.forEach(m => {
                const li = document.createElement('li');
                li.textContent = m;
                lista.appendChild(li);
            });
            errBox.classList.remove('d-none');
            return;
        }

        form.reset();
        mostrarAlerta('success', 'Solicitud enviada correctamente. Recibirá respuesta en su correo.');
    } catch (_) {
        mostrarAlerta('danger', 'No se pudo enviar la solicitud. Intente más tarde.');
    } finally {
        btn.disabled  = false;
        btn.innerHTML = '<i class="bi bi-send me-1"></i> Enviar solicitud';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('formPublica').addEventListener('submit', enviarSolicitud);
});
