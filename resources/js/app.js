import './bootstrap';

import 'bootstrap/dist/css/bootstrap.min.css';

import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Automatically ensure all Bootstrap modals are mounted directly on document.body when opened
document.addEventListener('show.bs.modal', function(event) {
    var modal = event.target;
    if (modal && modal.parentElement !== document.body) {
        document.body.appendChild(modal);
    }
});