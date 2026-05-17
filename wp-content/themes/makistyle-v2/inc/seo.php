<?php

/**
 * Makistyle SEO Helpers
 * Inyecta el contenido de meta description en el <head> (Solo Frontend)
 */

function makistyle_render_seo_description_meta()
{
	// Salir si estamos en el panel de administración
	if (is_admin()) {
		return;
	}

	$seo_description = '';

	// Comprobar si es una vista individual de los post types requeridos
	if (is_singular(['post', 'page', 'tienda_pt', 'product'])) {
		$post_id = get_queried_object_id();

		if ($post_id) {
			// Recuperar el valor del custom field
			$seo_description = get_post_meta(
				$post_id,
				'makistyle_cmb2_seo_description',
				true,
			);
		}
	} elseif (is_home() || is_category()) {
		$id_blog = get_option('page_for_posts');
		if ($id_blog) {
			$seo_description = get_post_meta(
				$id_blog,
				'makistyle_cmb2_seo_description',
				true,
			);
		}
	} elseif (is_tax('tipo_recurso_taxonomia')) {
		$page_tienda = get_page_by_path('tienda');
		if ($page_tienda) {
			$seo_description = get_post_meta(
				$page_tienda->ID,
				'makistyle_cmb2_seo_description',
				true,
			);
		}
	} elseif (is_search()) {
		$opcionesTema = get_option('makistyle_cmb2_opciones_tema_');
		if (!empty($opcionesTema['meta_description_pagina_busquedas'])) {
			$seo_description = $opcionesTema['meta_description_pagina_busquedas'];
		}
	}
	if (empty($seo_description)) {
		$opcionesTema = get_option('makistyle_cmb2_opciones_tema_');
		$seo_description = $opcionesTema['meta_description'];
	}
	// Escapar contenido y eliminar etiquetas HTML
	$seo_description_esc = esc_attr(wp_strip_all_tags($seo_description));

	echo '<meta name="description" content="' .
		$seo_description_esc .
		'" />' .
		"\n";
}

// Hook para inyectar en frontend
add_action('wp_head', 'makistyle_render_seo_description_meta');
