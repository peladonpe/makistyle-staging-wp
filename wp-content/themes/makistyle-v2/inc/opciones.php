<?php

function makistyle_cmb2_opciones_tema()
{
	$prefix = 'makistyle_cmb2_opciones_tema_';

	$cmb_options = new_cmb2_box([
		'id' => $prefix . 'page',
		'title' => esc_html__('Ajustes Makistyle', 'cmb2'),
		'object_types' => ['options-page'],
		'option_key' => $prefix,
		'icon_url' => 'dashicons-awards',
	]);

	$cmb_options->add_field([
		'name' => esc_html__('Descripción meta por defecto', 'cmb2'),
		'desc' => esc_html__('Descripción meta, 150 caracteres máx.', 'cmb2'),
		'id' => 'meta_description',
		'type' => 'textarea',
	]);

	$cmb_options->add_field([
		'name' => esc_html__('Descripción meta de búsquedas', 'cmb2'),
		'desc' => esc_html__('Descripción meta, 120-320 caracteres.', 'cmb2'),
		'id' => 'meta_description_pagina_busquedas',
		'type' => 'textarea',
	]);
}
add_action('cmb2_admin_init', 'makistyle_cmb2_opciones_tema');

function makistyle_cmb2_opciones_tema_contador_script()
{
	// Asegurarnos de que sólo se ejecuta en la página de opciones
	if (
		!isset($_GET['page']) ||
		$_GET['page'] !== 'makistyle_cmb2_opciones_tema_'
	) {
		return;
	} ?>
	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function() {
			function inicializarContador(selector) {
				var textarea = document.querySelector(selector);
				if (!textarea) return;

				var contador = document.createElement('div');
				contador.style.marginTop = '5px';
				contador.style.fontWeight = 'bold';
				textarea.parentNode.appendChild(contador);

				function actualizarContador() {
					var longitud = textarea.value.length;
					contador.textContent = longitud + ' caracteres';

					if (longitud >= 120 && longitud <= 150) {
						contador.style.color = '#46b450'; // verde wp-admin
					} else if (longitud >= 151 && longitud <= 320) {
						contador.style.color = '#ffb900'; // naranja wp-admin
					} else if (longitud > 320) {
						contador.style.color = '#dc3232'; // rojo wp-admin
					} else {
						contador.style.color = ''; // por defecto
					}
				}

				textarea.addEventListener('input', actualizarContador);
				actualizarContador(); // inicializa valor actual
			}

			// Inicializamos el contador en ambos textarea de las opciones SEO
			inicializarContador('#meta_description');
			inicializarContador('#meta_description_pagina_busquedas');
		});
	</script>
	<?php
}
add_action('admin_footer', 'makistyle_cmb2_opciones_tema_contador_script');
