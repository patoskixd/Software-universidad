'use strict';

// Escapa HTML para no romper el DOM al insertar datos del servidor
function escapeHtml(str) {
    if (str == null) return '';
    return String(str)
        .replace(/&/g,  '&amp;')
        .replace(/</g,  '&lt;')
        .replace(/>/g,  '&gt;')
        .replace(/"/g,  '&quot;')
        .replace(/'/g,  '&#039;');
}

// Inserta una alerta Bootstrap en #alertContainer y la elimina tras `duracion` ms
// duracion = 0 la deja fija hasta que el usuario la cierre
function mostrarAlerta(tipo, mensaje, duracion = 5000) {
    const container = document.getElementById('alertContainer');
    const id = 'alert-' + Date.now();

    container.insertAdjacentHTML('beforeend', `
        <div id="${id}" class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            ${escapeHtml(mensaje)}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    `);

    if (duracion > 0) {
        setTimeout(() => document.getElementById(id)?.remove(), duracion);
    }
}
