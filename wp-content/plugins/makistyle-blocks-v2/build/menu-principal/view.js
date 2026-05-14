/******/ (() => { // webpackBootstrap
/*!************************************!*\
  !*** ./src/menu-principal/view.js ***!
  \************************************/
(function () {
  'use strict';

  const toggler = document.getElementById('mn-toggler');
  const offcanvas = document.getElementById('mn-offcanvas');
  const overlay = document.getElementById('mn-overlay');
  const closeBtn = document.getElementById('mn-offcanvas-close');
  const navLinks = offcanvas ? offcanvas.querySelectorAll('.mn-offcanvas__nav-link') : [];
  if (!toggler || !offcanvas || !overlay) {
    return;
  }
  function openMenu() {
    offcanvas.classList.add('is-open');
    overlay.classList.add('is-open');
    toggler.setAttribute('aria-expanded', 'true');
    offcanvas.setAttribute('aria-hidden', 'false');
    overlay.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }
  function closeMenu() {
    offcanvas.classList.remove('is-open');
    overlay.classList.remove('is-open');
    toggler.setAttribute('aria-expanded', 'false');
    offcanvas.setAttribute('aria-hidden', 'true');
    overlay.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }
  toggler.addEventListener('click', openMenu);
  overlay.addEventListener('click', closeMenu);
  if (closeBtn) {
    closeBtn.addEventListener('click', closeMenu);
  }

  // Marcar el item activo según la URL actual
  const currentUrl = window.location.href.replace(/\/$/, '');
  navLinks.forEach(function (link) {
    link.addEventListener('click', closeMenu);
    const linkHref = link.getAttribute('href');
    if (linkHref) {
      const normalizedHref = linkHref.replace(/\/$/, '');
      if (normalizedHref === currentUrl) {
        link.closest('.mn-offcanvas__nav-item')?.classList.add('current_page_item');
      }
    }
  });

  // Cerrar con tecla Escape
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && offcanvas.classList.contains('is-open')) {
      closeMenu();
      toggler.focus();
    }
  });
})();
/******/ })()
;
//# sourceMappingURL=view.js.map