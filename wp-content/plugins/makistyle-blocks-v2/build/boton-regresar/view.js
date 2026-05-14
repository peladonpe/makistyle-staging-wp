/******/ (() => { // webpackBootstrap
/*!************************************!*\
  !*** ./src/boton-regresar/view.js ***!
  \************************************/
document.addEventListener('DOMContentLoaded', function () {
  const btn = document.querySelector('.boton-regresar__historial');
  if (btn) {
    btn.addEventListener('click', function () {
      history.back();
    });
  }
});
/******/ })()
;
//# sourceMappingURL=view.js.map