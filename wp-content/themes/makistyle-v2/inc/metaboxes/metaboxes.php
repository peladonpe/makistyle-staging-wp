<?php
/**
 * Theme Metaboxes (CMB2)
 *
 * @package Makistyle
 */

// Import promotions metaboxes
require_once dirname(__FILE__) . '/tienda-metaboxes.php';
require_once dirname(__FILE__) . '/woocommerce.metaboxes.php';
/**
 * POSTS
 */



function makistyle_cmb2_category()
{
	$prefix = 'makistyle_cmb2_category_';
	$makistyle_cmb2_category = new_cmb2_box([
		'id' => $prefix . 'metaboxes',
		'title' => esc_html__('Campos de las categorías del blog', 'cmb2'),
		'object_types' => ['term'],
		'taxonomies' => ['category'],
	]);
	$makistyle_cmb2_category->add_field([
		'name' => esc_html__('Nombre SINGULAR', 'cmb2'),
		'desc' => esc_html__('Nombre de la categoría en singular', 'cmb2'),
		'id' => $prefix . 'nombre_singular',
		'type' => 'text',
	]);
}
add_action('cmb2_admin_init', 'makistyle_cmb2_category');

/**
 * SEO
 */
function makistyle_cmb2_seo()
{
	$prefix = 'makistyle_cmb2_seo_';

	$pv_cmb2_seo = new_cmb2_box([
		'id' => $prefix . 'metaboxes',
		'title' => esc_html__('Campos seo', 'cmb2'),
		'object_types' => ['page', 'tienda_pt', 'post', 'product'], // Post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'show_in_rest' => true,
	]);
	$pv_cmb2_seo->add_field([
		'name' => esc_html__('Meta description', 'cmb2'),
		'desc' => esc_html__('Descripción meta, 120-320 caracteres', 'cmb2'),
		'id' => $prefix . 'description',
		'type' => 'textarea',
	]);
}
add_action('cmb2_init', 'makistyle_cmb2_seo');

// JavaScript para mostrar el contador de caracteres en el metabox de SEO
function makistyle_cmb2_seo_contador_script()
{
	$screen = get_current_screen();
	if (
		!$screen ||
		!in_array($screen->post_type, ['page', 'tienda_pt', 'post', 'product'])
	) {
		return;
	}?>
	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function() {
			var textarea = document.querySelector('#makistyle_cmb2_seo_description');
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
		});
	</script>
<?php
}
add_action('admin_footer', 'makistyle_cmb2_seo_contador_script');

