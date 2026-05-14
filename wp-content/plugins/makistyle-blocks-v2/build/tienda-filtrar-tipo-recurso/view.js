/******/ (() => { // webpackBootstrap
/*!*************************************************!*\
  !*** ./src/tienda-filtrar-tipo-recurso/view.js ***!
  \*************************************************/
const $modalTipoRecurso = document.getElementById('modalTipoRecurso');
const $formularioFiltro = document.getElementById('formulario-filtro');
const $selectCategoria = document.querySelector('#select-categoria');
const $checkEsGratuito = document.querySelector('#check-es-gratuito');
const $btnSubmitFiltrar = document.querySelector('#btn-submit-filtrar');
const $lanzadorModalFiltrar = document.querySelector('#lanzador-modal-filtrar');
const urlParams = new URLSearchParams(window.location.search);
const precioUrlParam = urlParams.get('precio');
const $btnResetFiltro = document.querySelector('#btn-reset-filtro');
document.addEventListener('DOMContentLoaded', function () {
  //   if (selectTipoRecurso) {
  //     selectTipoRecurso.addEventListener("change", function () {
  //       let nuevoValor = selectTipoRecurso.value;
  //       if (nuevoValor !== "no-seleccionado") {
  //         selectTipoRecurso.value = "no-seleccionado";
  //         selectTipoRecurso.disabled = true;
  //         // location.href = `${URL_SERVIDOR}/tipo-recurso/${nuevoValor}`;
  //         location.href = `${URL_SERVIDOR}/tipo-recurso/${nuevoValor}?precio=0`;
  //       }
  //     });
  //   }
  if ($modalTipoRecurso) {
    const categoriaName = $selectCategoria.getAttribute('data-categoria-name');
    const categoriaValue = $selectCategoria.getAttribute('data-categoria-value');
    let categoriaSlug = $selectCategoria.getAttribute('data-categoria-slug');
    const homeUrl = $btnSubmitFiltrar.getAttribute('data-home-url');
    function setValorLanzadorFiltrar() {
      let textoLanzadorFiltrar = categoriaValue;
      const $enlaceBtnResetFiltro = $btnResetFiltro.querySelector('a');
      $enlaceBtnResetFiltro.href = `${homeUrl}/tienda`;
      if (precioUrlParam && precioUrlParam === '0') {
        textoLanzadorFiltrar += ', Gratuitos';
        $btnResetFiltro.classList.remove('d-none');
        $btnResetFiltro.style.display = 'block';
      } else {
        // eslint-disable-next-line no-lonely-if
        if (categoriaSlug === 'todo') {
          textoLanzadorFiltrar = 'Filtrar';
          $btnResetFiltro.style.display = 'none';
        } else {
          $btnResetFiltro.classList.remove('d-none');
          $btnResetFiltro.style.display = 'block';
        }
      }
      $lanzadorModalFiltrar.textContent = textoLanzadorFiltrar;
    }
    function setValoresModalFiltrar() {
      $selectCategoria.value = categoriaSlug;
      if (precioUrlParam && precioUrlParam === '0') {
        $checkEsGratuito.checked = true;
        // $checkEsGratuito.setAttribute("checked", true);
      } else {
        $checkEsGratuito.checked = false;
      }
    }
    setValorLanzadorFiltrar();
    setValoresModalFiltrar();
    const $btnCerrarModal = $modalTipoRecurso.querySelector('#boton-cerrar-modal');
    const $btnCancelarFiltro = document.querySelector('#btn-cancelar-filtro');
    function abrirModal() {
      setValoresModalFiltrar();
      $modalTipoRecurso.classList.remove('oculto');
    }
    function cerrarModal() {
      $modalTipoRecurso.classList.add('oculto');
    }
    $lanzadorModalFiltrar.addEventListener('click', abrirModal);
    $btnCerrarModal.addEventListener('click', cerrarModal);
    $btnCancelarFiltro.addEventListener('click', cerrarModal);
    $modalTipoRecurso.addEventListener('click', function (event) {
      if (event.target === $modalTipoRecurso) {
        cerrarModal();
      }
    });
    $formularioFiltro.addEventListener('submit', function (event) {
      event.preventDefault();
      categoriaSlug = $selectCategoria.value;
      let queryString = '';
      if ($checkEsGratuito.checked) {
        queryString = '?precio=0';
      }
      if (categoriaSlug === 'todo') {
        location.href = `${homeUrl}/tienda${queryString}`;
        return;
      }
      location.href = `${homeUrl}/${categoriaName}/${categoriaSlug}${queryString}`;
    });
  }

  // Búsqueda por texto
  const $buscarWrap = document.querySelector('.lanzador-buscar__wrap');
  const $buscarBtn = document.querySelector('.lanzador-buscar__btn');
  const $buscarInput = document.querySelector('.lanzador-buscar__input');
  if ($buscarBtn && $buscarInput && $buscarWrap) {
    const buscarHomeUrl = $buscarBtn.getAttribute('data-home-url');
    function lanzarBusqueda() {
      const termino = $buscarInput.value.trim();
      if (termino) {
        location.href = buscarHomeUrl + '?s=' + encodeURIComponent(termino) + '&t=tienda';
      }
    }
    $buscarBtn.addEventListener('click', function () {
      if (!$buscarWrap.classList.contains('activo')) {
        $buscarWrap.classList.add('activo');
        $buscarInput.focus();
      } else if ($buscarInput.value.trim()) {
        lanzarBusqueda();
      } else {
        $buscarWrap.classList.remove('activo');
      }
    });
    $buscarInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        lanzarBusqueda();
      }
    });
  }
});
/******/ })()
;
//# sourceMappingURL=view.js.map