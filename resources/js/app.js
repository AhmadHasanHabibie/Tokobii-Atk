import './bootstrap';

import 'bootstrap/dist/css/bootstrap.min.css';

import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Automatically ensure all Bootstrap modals are mounted directly on document.body when opened
document.addEventListener('show.bs.modal', function (event) {
    var modal = event.target;
    if (modal && modal.parentElement !== document.body) {
        document.body.appendChild(modal);
    }
});

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