<?php

/**
 * Registra los campos personalizados (metaboxes) de CMB2 para las categorías de woocommerce,
 * permitiendo definir propiedades adicionales como el nombre en singular.
 */
function makistyle_cmb2_woocommerce_categorias()
{
	$prefix = 'makistyle_cmb2_woocommerce_categorias_';
	$makistyle_cmb2_tipo_recurso_taxonomia = new_cmb2_box([
		'id' => $prefix . 'metaboxes',
		'title' => esc_html__(
			'Campos de las categorias de woocommerce',
			'cmb2',
		),
		'object_types' => ['term'], // Post type
		'taxonomies' => ['product_cat'],
		'show_in_rest' => true,
	]);
	$makistyle_cmb2_tipo_recurso_taxonomia->add_field([
		'name' => esc_html__('Nombre SINGULAR', 'cmb2'),
		'desc' => esc_html__('Nombre de la categoría en singular', 'cmb2'),
		'id' => $prefix . 'nombre_singular',
		'type' => 'text',
	]);
}
add_action('cmb2_init', 'makistyle_cmb2_woocommerce_categorias');

/**
 * PRODUCTOS DE WOOCOMMERCE
 */
function makistyle_cmb2_productos()
{
	$prefix = 'makistyle_cmb2_woocommerce_';

	$product_info_box = new_cmb2_box([
		'id' => $prefix . 'product_metaboxes',
		'title' => esc_html__('Información adicional del producto', 'cmb2'),
		'object_types' => ['product'],
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true,
		'show_in_rest' => true,
	]);

	$product_info_box->add_field([
		'name' => esc_html__('Fecha de lanzamiento', 'cmb2'),
		'desc' => esc_html__(
			'Fecha de lanzamiento del producto (puede no coincidir con la de publicación)',
			'cmb2',
		),
		'id' => $prefix . 'fecha_lanzamiento2',
		'type' => 'text_date_timestamp',
		'date_format' => 'd-m-Y',
	]);

	$product_info_box->add_field([
		'name' => esc_html__('Enlace de compra alternativo', 'cmb2'),
		'desc' => esc_html__(
			'Enlace alternativo de compra en una tienda externa (si el producto no se registra como externo/afiliado).',
			'cmb2',
		),
		'id' => $prefix . 'enlace_compra',
		'type' => 'text_url',
	]);

	$product_info_box->add_field([
		'name' => esc_html__('Texto botón compra alternativa', 'cmb2'),
		'desc' => esc_html__(
			'Texto del botón que apunta a la tienda externa (p. ej. Payhip).',
			'cmb2',
		),
		'id' => $prefix . 'texto_enlace_compra',
		'type' => 'text',
	]);

	$product_info_box->add_field([
		'name' => esc_html__('Autores', 'cmb2'),
		'desc' => esc_html__(
			'Autores del producto (pueden no coincidir con el autor de la publicación).',
			'cmb2',
		),
		'id' => $prefix . 'autores',
		'type' => 'text',
	]);

	$product_videos_box = new_cmb2_box([
		'id' => $prefix . 'product_video_metaboxes',
		'title' => esc_html__('Vídeo asociado al producto', 'cmb2'),
		'object_types' => ['product'],
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true,
		'show_in_rest' => true,
	]);

	$product_videos_box->add_field([
		'name' => esc_html__('ID de Youtube destacado', 'cmb2'),
		'desc' => esc_html__(
			'Añade un ID de Youtube asociado al producto para reproducir su vídeo.',
			'cmb2',
		),
		'id' => $prefix . 'id_youtube_destacado',
		'type' => 'text',
	]);

	$product_videos_box->add_field([
		'name' => esc_html__('URL de vídeo local', 'cmb2'),
		'desc' => esc_html__(
			'URL de un vídeo local (del sistema de ficheros) asociado al producto.',
			'cmb2',
		),
		'id' => $prefix . 'url_video_local',
		'type' => 'file',
	]);
}
add_action('cmb2_init', 'makistyle_cmb2_productos');

// Callback para establecer la fecha actual como valor por defecto para WooCommerce
function makistyle_wc_fecha_actual_default()
{
	return time();
}

/**
 * Registra los campos personalizados (metaboxes) de CMB2 para la taxonomía de descuentos de WooCommerce
 * ("descuentos_taxonomia"). Habilita la configuración detallada de descuentos, incluyendo
 * porcentaje de rebaja, rango de fechas válidas y estado de activación.
 */
function makistyle_cmb2_descuentos_taxonomia()
{
	$prefix = 'makistyle_cmb2_descuentos_taxonomia_';
	$makistyle_cmb2_descuentos_taxonomia = new_cmb2_box([
		'id' => $prefix . 'metaboxes',
		'title' => esc_html__(
			'Campos de la taxonomia descuentos WooCommerce',
			'cmb2',
		),
		'object_types' => ['term'], // Post type
		'taxonomies' => ['descuentos_taxonomia'],
		'show_in_rest' => true,
	]);

	$makistyle_cmb2_descuentos_taxonomia->add_field([
		'name' => esc_html__('Nombre del descuento', 'cmb2'),
		'desc' => esc_html__(
			'Nombre del descuento que se mostrará al usuario, p. ej. 20% descuento, promoción Navidad, etc.',
			'cmb2',
		),
		'id' => $prefix . 'nombre_descuento',
		'type' => 'text',
		'required' => true,
		'attributes' => [
			'required' => 'required',
		],
	]);

	$makistyle_cmb2_descuentos_taxonomia->add_field([
		'name' => esc_html__('Porcentaje de descuento', 'cmb2'),
		'desc' => esc_html__(
			'Porcentaje de descuento aplicable (número entero del 0 al 100)',
			'cmb2',
		),
		'id' => $prefix . 'porcentaje_descuento',
		'type' => 'text_small',
		'required' => true,
		'attributes' => [
			'type' => 'number',
			'pattern' => '\d*',
			'min' => '0',
			'max' => '100',
			'step' => '1',
			'required' => 'required',
		],
	]);

	$makistyle_cmb2_descuentos_taxonomia->add_field([
		'name' => esc_html__('Fecha de inicio', 'cmb2'),
		'desc' => esc_html__('Fecha de inicio del descuento', 'cmb2'),
		'id' => $prefix . 'fecha_inicio',
		'type' => 'text_date_timestamp',
		'date_format' => 'd-m-Y',
		'default_cb' => 'makistyle_wc_fecha_actual_default',
		'required' => true,
		'attributes' => [
			'required' => 'required',
		],
	]);

	$makistyle_cmb2_descuentos_taxonomia->add_field([
		'name' => esc_html__('Fecha final', 'cmb2'),
		'desc' => esc_html__('Fecha final del descuento', 'cmb2'),
		'id' => $prefix . 'fecha_final',
		'type' => 'text_date_timestamp',
		'date_format' => 'd-m-Y',
		'required' => true,
		'attributes' => [
			'required' => 'required',
		],
	]);

	$makistyle_cmb2_descuentos_taxonomia->add_field([
		'name' => esc_html__('DESCUENTO ACTIVO', 'cmb2'),
		'desc' => esc_html__('Desmarcar para desactivar el descuento', 'cmb2'),
		'id' => $prefix . 'descuento_activo',
		'type' => 'checkbox',
	]);
}
add_action('cmb2_init', 'makistyle_cmb2_descuentos_taxonomia');

/**
 * Obtiene los productos de WooCommerce ordenados por fecha de lanzamiento
 * (de más reciente a más antiguo) para el campo multicheck.
 */
function makistyle_get_woocommerce_products_options()
{
	$products = get_posts([
		'post_type' => 'product',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'meta_query' => [
			'relation' => 'OR',
			'lanzamiento_exists' => [
				'key' => 'makistyle_cmb2_woocommerce_fecha_lanzamiento2',
				'compare' => 'EXISTS',
				'type' => 'NUMERIC',
			],
			'lanzamiento_not_exists' => [
				'key' => 'makistyle_cmb2_woocommerce_fecha_lanzamiento2',
				'compare' => 'NOT EXISTS',
			],
		],
		'orderby' => [
			'lanzamiento_exists' => 'DESC',
			'title' => 'ASC',
		],
	]);

	$options = [];
	if (!empty($products) && !is_wp_error($products)) {
		foreach ($products as $product) {
			// $lanzamiento = get_post_meta(
			// 	$product->ID,
			// 	'makistyle_cmb2_woocommerce_fecha_lanzamiento2',
			// 	true,
			// );
			// if ($lanzamiento) {
			// 	$fecha_formateada = date('d-m-Y', $lanzamiento);
			// 	$options[$product->ID] =
			// 		$product->post_title . ' (' . $fecha_formateada . ')';
			// } else {
			// 	$options[$product->ID] = $product->post_title;
			// }
			$options[$product->ID] = $product->post_title;
		}
	}
	return $options;
}

/**
 * Registra los campos personalizados (metaboxes) de CMB2 para la página de opciones "descuentos2".
 * Réplica de los campos de la taxonomía descuentos_taxonomia.
 */
function makistyle_cmb2_descuentos2_opciones()
{
	$prefix = 'makistyle_cmb2_descuentos2_';
	$cmb_options = new_cmb2_box([
		'id' => $prefix . 'metaboxes',
		'title' => esc_html__('Descuentos 2', 'makistyle'),
		'object_types' => ['options-page'],
		'option_key' => 'descuentos2',
		'parent_slug' => 'edit.php?post_type=product',
		'capability' => 'manage_woocommerce',
	]);

	$cmb_options->add_field([
		'name' => esc_html__('Nombre', 'cmb2'),
		'desc' => esc_html__(
			'Nombre para identificar el descuento en los paneles de administración',
			'cmb2',
		),
		'id' => $prefix . 'nombre',
		'type' => 'text',
		'required' => true,
		'attributes' => [
			'required' => 'required',
		],
	]);

	$cmb_options->add_field([
		'name' => esc_html__('Descripción', 'cmb2'),
		'desc' => esc_html__('Descripción del descuento', 'cmb2'),
		'id' => $prefix . 'descripcion',
		'type' => 'textarea_small',
	]);

	$cmb_options->add_field([
		'name' => esc_html__('Nombre del descuento', 'cmb2'),
		'desc' => esc_html__(
			'Nombre del descuento que SE MOSTRARÁ AL USUARIO, p. ej. 20% descuento, promoción Navidad, etc.',
			'cmb2',
		),
		'id' => $prefix . 'nombre_descuento',
		'type' => 'text',
		'required' => true,
		'attributes' => [
			'required' => 'required',
		],
	]);

	$cmb_options->add_field([
		'name' => esc_html__('Porcentaje de descuento', 'cmb2'),
		'desc' => esc_html__(
			'Porcentaje de descuento aplicable (número entero del 0 al 100)',
			'cmb2',
		),
		'id' => $prefix . 'porcentaje_descuento',
		'type' => 'text_small',
		'required' => true,
		'attributes' => [
			'type' => 'number',
			'pattern' => '\d*',
			'min' => '0',
			'max' => '100',
			'step' => '1',
			'required' => 'required',
		],
	]);

	$cmb_options->add_field([
		'name' => esc_html__('Fecha de inicio', 'cmb2'),
		'desc' => esc_html__('Fecha de inicio del descuento', 'cmb2'),
		'id' => $prefix . 'fecha_inicio',
		'type' => 'text_date_timestamp',
		'date_format' => 'd-m-Y',
		'default_cb' => 'makistyle_wc_fecha_actual_default',
		'required' => true,
		'attributes' => [
			'required' => 'required',
		],
	]);

	$cmb_options->add_field([
		'name' => esc_html__('Fecha final', 'cmb2'),
		'desc' => esc_html__('Fecha final del descuento', 'cmb2'),
		'id' => $prefix . 'fecha_final',
		'type' => 'text_date_timestamp',
		'date_format' => 'd-m-Y',
		'required' => true,
		'attributes' => [
			'required' => 'required',
		],
	]);

	// $cmb_options->add_field([
	// 	'name' => esc_html__('Productos asociados', 'cmb2'),
	// 	'desc' => esc_html__(
	// 		'Selecciona los productos que tendrán aplicado este descuento. Los productos están ordenados por fecha de lanzamiento (de más recientes a más antiguos).',
	// 		'cmb2',
	// 	),
	// 	'id' => $prefix . 'productos_asociados',
	// 	'type' => 'multicheck',
	// 	'options_cb' => 'makistyle_get_woocommerce_products_options',
	// ]);

	$cmb_options->add_field([
		'name' => esc_html__('DESCUENTO ACTIVO', 'cmb2'),
		'desc' => esc_html__('Desmarcar para desactivar el descuento', 'cmb2'),
		'id' => $prefix . 'descuento_activo',
		'type' => 'checkbox',
	]);
}
// add_action('cmb2_admin_init', 'makistyle_cmb2_descuentos2_opciones');

// Validación de fechas en el cliente (JavaScript) para descuentos WooCommerce
function makistyle_wc_validar_fechas_descuentos_script()
{
	$screen = get_current_screen();
	$is_tax = $screen && $screen->taxonomy === 'descuentos_taxonomia';
	$is_page = $screen && strpos($screen->id, 'page_descuentos2') !== false;

	if (!$is_tax && !$is_page) {
		return;
	}?>
	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function() {
			var form = document.getElementById('edittag') || document.querySelector('form.cmb-form');
			if (!form) return;

			// Función para convertir fecha en formato d-m-Y a timestamp
			function convertirFechaATimestamp(fechaString) {
				if (!fechaString) return null;
				var partes = fechaString.split('-');
				if (partes.length !== 3) return null;

				var dia = parseInt(partes[0], 10);
				var mes = parseInt(partes[1], 10) - 1; // Los meses en JavaScript van de 0 a 11
				var anio = parseInt(partes[2], 10);

				return new Date(anio, mes, dia).getTime();
			}

			form.addEventListener('submit', function(e) {
				var fechaInicio = document.querySelector('input[name$="fecha_inicio"]');
				var fechaFinal = document.querySelector('input[name$="fecha_final"]');

				if (!fechaInicio || !fechaFinal) return;

				var valorInicio = fechaInicio.value;
				var valorFinal = fechaFinal.value;

				if (valorInicio && valorFinal) {
					var timestampInicio = convertirFechaATimestamp(valorInicio);
					var timestampFinal = convertirFechaATimestamp(valorFinal);

					if (timestampInicio && timestampFinal && timestampFinal < timestampInicio) {
						e.preventDefault();
						alert('Error: La fecha final no puede ser anterior a la fecha de inicio.');

						// Resaltar el campo con error
						fechaFinal.style.borderColor = 'red';
						fechaFinal.focus();

						return false;
					}
				}
			});
		});
	</script>
<?php
}
add_action('admin_footer', 'makistyle_wc_validar_fechas_descuentos_script');

// JavaScript para manejar la lógica de descuento activo/inactivo
function makistyle_wc_descuento_activo_script()
{
	$screen = get_current_screen();
	$is_tax = $screen && $screen->taxonomy === 'descuentos_taxonomia';
	$is_page = $screen && strpos($screen->id, 'page_descuentos2') !== false;

	if (!$is_tax && !$is_page) {
		return;
	}?>
	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function() {
			// Cambiar la descripción del campo Nombre nativo de WordPress
			var nameDesc = document.getElementById('name-description') || 
			               document.querySelector('.term-name-wrap p.description') || 
			               document.querySelector('.term-name-wrap p');
			if (nameDesc) {
				nameDesc.textContent = 'Nombre para identificar el descuento en los paneles de administración.';
			}

			var checkbox = document.querySelector('input[name$="descuento_activo"]');
			var inputNombre = document.querySelector('#name') || document.querySelector('input[name$="nombre"]');
			var inputSlug = document.querySelector('#slug');
			var inputParent = document.querySelector('#parent');
			var textareaDescription = document.querySelector('#description') || document.querySelector('textarea[name$="descripcion"]');
			var inputNombreDescuento = document.querySelector('input[name$="nombre_descuento"]');
			var inputPorcentajeDescuento = document.querySelector('input[name$="porcentaje_descuento"]');
			var inputFechaInicio = document.querySelector('input[name$="fecha_inicio"]');
			var inputFechaFinal = document.querySelector('input[name$="fecha_final"]');
			var containerProductos = document.querySelector('.cmb-type-multicheck');

			if (!checkbox) {
				return;
			}
			// Función para deshabilitar un campo
			function deshabilitarCampo(campo) {
				if (!campo) return;
				campo.style.opacity = '0.6';
				campo.style.pointerEvents = 'none';
				campo.style.backgroundColor = '#f0f0f0';
				campo.style.borderRadius = '4px';
				// campo.disabled = true;
			}

			// Función para habilitar un campo
			function habilitarCampo(campo) {
				if (!campo) return;
				campo.style.opacity = '';
				campo.style.pointerEvents = '';
				campo.style.backgroundColor = '';
				campo.style.borderRadius = '';
				// campo.disabled = false;
			}

			function actualizarEstadoCampos() {

				// Cuando NO está checked (descuento desactivado), deshabilitar los campos
				if (!checkbox.checked) {
					deshabilitarCampo(inputNombre);
					deshabilitarCampo(inputSlug);
					deshabilitarCampo(inputParent);
					deshabilitarCampo(textareaDescription);
					deshabilitarCampo(inputNombreDescuento);
					deshabilitarCampo(inputPorcentajeDescuento);
					deshabilitarCampo(inputFechaInicio);
					deshabilitarCampo(inputFechaFinal);
					deshabilitarCampo(containerProductos);
				} else {
					// Restaurar estado normal cuando está activa
					habilitarCampo(inputNombre);
					habilitarCampo(inputSlug);
					habilitarCampo(inputParent);
					habilitarCampo(textareaDescription);
					habilitarCampo(inputNombreDescuento);
					habilitarCampo(inputPorcentajeDescuento);
					habilitarCampo(inputFechaInicio);
					habilitarCampo(inputFechaFinal);
					habilitarCampo(containerProductos);
				}
			}

			// Ejecutar al cargar
			actualizarEstadoCampos();

			// Ejecutar cuando cambie el checkbox
			checkbox.addEventListener('change', function() {
				checkbox.blur();
				actualizarEstadoCampos();
			});

			// Desenfocar los checkboxes de productos asociados para evitar retrasos visuales por el focus
			if (containerProductos) {
				containerProductos.addEventListener('change', function(e) {
					if (e.target && e.target.type === 'checkbox') {
						e.target.blur();
					}
				});
			}
		});
	</script>
<?php
}
add_action('admin_footer', 'makistyle_wc_descuento_activo_script');
