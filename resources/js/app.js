import './bootstrap';

import 'bootstrap/dist/css/bootstrap.min.css';

import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

/**
 * Global Tokobii Modal & Overlay Manager
 * Ensures all modals, backdrops, and overlays have 100% viewport coverage
 * and are elevated directly to document.body to avoid stacking context traps.
 */
function elevateModalsToBody() {
    var modals = document.querySelectorAll('.modal, .tokobii-guidance-backdrop, .tokobii-modal-overlay, [data-tokobii-modal]');
    modals.forEach(function (modal) {
        if (modal && modal.parentElement && modal.parentElement !== document.body) {
            document.body.appendChild(modal);
        }
    });

    // Check for any server-rendered / active modals on page load
    var activeModal = document.querySelector('.modal.show, .modal.d-block, .tokobii-guidance-backdrop.show, .tokobii-modal-overlay.show');
    if (activeModal) {
        document.body.classList.add('modal-open', 'tokobii-modal-open');
    }
}

// Elevate on DOM load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', elevateModalsToBody);
} else {
    elevateModalsToBody();
}

// Elevate when any Bootstrap modal is triggered
document.addEventListener('show.bs.modal', function (event) {
    var modal = event.target;
    if (modal && modal.parentElement !== document.body) {
        document.body.appendChild(modal);
    }
    document.body.classList.add('modal-open', 'tokobii-modal-open');
});

document.addEventListener('hidden.bs.modal', function () {
    var remainingModals = document.querySelectorAll('.modal.show, .tokobii-guidance-backdrop.show, .tokobii-modal-overlay.show');
    if (remainingModals.length === 0) {
        document.body.classList.remove('modal-open', 'tokobii-modal-open');
    }
});

// Global helpers for custom Tokobii modals
window.openTokobiiModal = function (modalId) {
    var el = document.getElementById(modalId);
    if (el) {
        if (el.parentElement !== document.body) {
            document.body.appendChild(el);
        }
        el.classList.add('show');
        document.body.classList.add('modal-open', 'tokobii-modal-open');
    }
};

window.closeTokobiiModal = function (modalId) {
    var el = document.getElementById(modalId);
    if (el) {
        el.classList.remove('show');
        var remaining = document.querySelectorAll('.modal.show, .tokobii-guidance-backdrop.show, .tokobii-modal-overlay.show');
        if (remaining.length === 0) {
            document.body.classList.remove('modal-open', 'tokobii-modal-open');
        }
    }
};

// Resilient global event listener for all flash alerts / notification close buttons
document.addEventListener('click', function (e) {
    var closeBtn = e.target.closest('[data-bs-dismiss="alert"], [data-tokobii-dismiss="alert"], .btn-close');
    if (closeBtn) {
        var alertEl = closeBtn.closest('.alert, .tokobii-alert, .auth-alert');
        if (alertEl) {
            e.preventDefault();
            e.stopPropagation();
            alertEl.style.transition = 'opacity 0.2s ease, transform 0.2s ease, max-height 0.25s ease, margin 0.2s ease, padding 0.2s ease';
            alertEl.style.opacity = '0';
            alertEl.style.transform = 'translateY(-6px)';
            setTimeout(function () {
                if (alertEl && alertEl.parentElement) {
                    alertEl.remove();
                }
            }, 200);
        }
    }
});