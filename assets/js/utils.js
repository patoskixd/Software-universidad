'use strict';

function escapeHtml(str) {
    if (str == null) return '';
    return String(str)
        .replace(/&/g,  '&amp;')
        .replace(/</g,  '&lt;')
        .replace(/>/g,  '&gt;')
        .replace(/"/g,  '&quot;')
        .replace(/'/g,  '&#039;');
}

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
}

// duracion = 0 deja la alerta fija
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
