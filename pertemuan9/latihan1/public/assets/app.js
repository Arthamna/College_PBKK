'use strict';

document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('#sidebarToggle');
    toggle?.addEventListener('click', (event) => {
        event.preventDefault();
        document.body.classList.toggle('sb-sidenav-toggled');
        toggle.setAttribute('aria-expanded', String(document.body.classList.contains('sb-sidenav-toggled')));
    });

    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm(form.dataset.confirm)) event.preventDefault();
        });
    });
});
