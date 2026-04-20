'use strict';

function escapeHtml(str) {
    if (str == null) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
}

const TOAST_ICONS = {
    success: 'bi-check-circle-fill',
    danger: 'bi-exclamation-triangle-fill',
    warning: 'bi-exclamation-circle-fill',
    info: 'bi-info-circle-fill',
};

// alertas
function mostrarAlerta(tipo, mensaje, duracion = 5000) {
    const container = document.getElementById('toastContainer');
    const id = 'toast-' + Date.now();
    const icon = TOAST_ICONS[tipo] ?? 'bi-info-circle-fill';

    container.insertAdjacentHTML('beforeend', `
        <div id="${id}" class="toast toast-custom toast-${tipo} align-items-center border-0"
             role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex align-items-center">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="bi ${icon} flex-shrink-0"></i>
                    <span>${escapeHtml(mensaje)}</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 ms-auto flex-shrink-0"
                        data-bs-dismiss="toast" aria-label="Cerrar"></button>
            </div>
        </div>
    `);

    const el = document.getElementById(id);
    const toast = new bootstrap.Toast(el, { delay: duracion > 0 ? duracion : 9999999 });
    toast.show();
    el.addEventListener('hidden.bs.toast', () => el.remove());
}
