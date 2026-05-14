<?php

// function posts_link_attributes()
// {
//      return 'class="page-link"';
// }
// add_filter('next_posts_link_attributes', 'posts_link_attributes');
// add_filter('previous_posts_link_attributes', 'posts_link_attributes');

/** Agrega un mensaje personalizado a la página en el admin */

// Añadir columna personalizada para información de posts
function makistyle_add_post_info_column($columns)
{
	$new_columns = [];
	foreach ($columns as $key => $value) {
		$new_columns[$key] = $value;
		// Insertar después de la columna de título
		if ($key === 'title') {
			$new_columns['post_info'] = __('Información', 'makistyle');
		}
	}
	return $new_columns;
}
add_filter('manage_post_posts_columns', 'makistyle_add_post_info_column');
add_filter('manage_tienda_pt_posts_columns', 'makistyle_add_post_info_column');
add_filter('manage_page_posts_columns', 'makistyle_add_post_info_column');

function makistyle_show_post_info_column($column_name, $post_id)
{
	if ($column_name === 'post_info') {
		$post = get_post($post_id);
		$current_slug = $post->post_name;

		$mensajeSlug =
			'<p style="margin: 5px 0; color: #3180FC;"><strong>SLUG:</strong> <span style="color: #3180FC;">';
		if (is_home()) {
			$mensajeSlug .= 'blog';
		} else {
			$mensajeSlug .= $current_slug;
		}
		$mensajeSlug .= '</span></p>';

		$post_status = get_post_status($post_id);
		if ($post_status === 'draft') {
			echo $mensajeSlug;
			return;
		}

		$msgImgDestacada = '';
		$msgSeo          = '';

		if (
			get_post_type($post_id) === 'post' ||
			get_post_type($post_id) === 'tienda_pt'
		) {

			$msgImgDestacada =
				'<p style="margin: 5px 0; color: #3180FC;"><strong>IMG DESTACADA:</strong> ';
			if (has_post_thumbnail($post_id)) {
				$thumb_id   = get_post_thumbnail_id($post_id);
				$thumb_path = get_attached_file($thumb_id);
				if ($thumb_path && file_exists($thumb_path)) {
					$size_kb  = round(filesize($thumb_path) / 1024);
					$img_type = ' - ' . strtoupper(pathinfo($thumb_path, PATHINFO_EXTENSION));
					$img_info = $size_kb . ' KB ' . $img_type;
					if ($size_kb < 200) {
						$msgImgDestacada .= '<span style="color: #00a32a;">' . $img_info;
					} else {
						$msgImgDestacada .= '<span style="color: #d63638; font-size:1.3em;">' . $img_info;
					}
				} else {
					$msgImgDestacada .= '<span style="color: #00a32a;">SI';
				}
			} else {
				$msgImgDestacada .= '<span style="color: #d63638; font-size:1.3em;">NO';
			}
			$msgImgDestacada .= '</span></p>';

			$linkVideo = get_post_meta(
				$post_id,
				'makistyle_cmb2_todos_pt_id_youtube_destacado',
				true,
			);
			$msgLinkVideo =
				'<p style="margin: 5px 0; color: #3180FC;"><strong>LINK VIDEO:</strong> ';
			$msgLinkVideo .= !empty($linkVideo)
				? '<span style="color: #00a32a;">SI'
				: '<span style="color: #d63638; font-size:1.3em;">NO';
			$msgLinkVideo .= '</span></p>';

			$seoDescription = get_post_meta(
				$post_id,
				'makistyle_cmb2_seo_description',
				true,
			);
			$msgSeo =
				'<p style="margin: 5px 0; color: #3180FC;"><strong>SEO:</strong> ';
			$msgSeo .= !empty($seoDescription)
				? '<span style="color: #00a32a;">SI'
				: '<span style="color: #d63638; font-size:1.3em;">NO';
			$msgSeo .= '</span></p>';
		}

		$msgPrecio = '';
		if (get_post_type($post_id) === 'tienda_pt') {
			$precio = get_post_meta(
				$post_id,
				'makistyle_cmb2_tienda_precio',
				true,
			);
			$msgPrecio =
				'<p style="margin: 5px 0; color: #3180FC;"><strong>PRECIO:</strong> ';
			$msgPrecio .=
				$precio || $precio == 0
					? '<span style="color: #00a32a;">SI</span>'
					: '<span style="color: #d63638; font-size:1.3em;">NO</span>';
			$msgPrecio .= '</p>';
		}

		// SEO para páginas (page)
		if (get_post_type($post_id) === 'page') {
			$seoDescription = get_post_meta(
				$post_id,
				'makistyle_cmb2_seo_description',
				true,
			);
			$msgSeo  = '<p style="margin: 5px 0; color: #3180FC;"><strong>SEO:</strong> ';
			$msgSeo .= !empty($seoDescription)
				? '<span style="color: #00a32a;">SI'
				: '<span style="color: #d63638; font-size:1.3em;">NO';
			$msgSeo .= '</span></p>';
		}

		echo $mensajeSlug .
			$msgImgDestacada .
			$msgLinkVideo .
			$msgSeo .
			$msgPrecio;
	}
}
add_action(
	'manage_post_posts_custom_column',
	'makistyle_show_post_info_column',
	10,
	2,
);
add_action(
	'manage_tienda_pt_posts_custom_column',
	'makistyle_show_post_info_column',
	10,
	2,
);
add_action(
	'manage_page_posts_custom_column',
	'makistyle_show_post_info_column',
	10,
	2,
);

// Añadir columna personalizada para mostrar metaboxes de promociones_taxonomia
function makistyle_add_promociones_columns($columns)
{
	$columns['meta_info'] = __('Información Meta', 'makistyle');
	return $columns;
}
add_filter(
	'manage_edit-promociones_taxonomia_columns',
	'makistyle_add_promociones_columns',
);

function makistyle_show_promociones_columns($content, $column_name, $term_id)
{
	if ($column_name === 'meta_info') {
		$term = get_term($term_id, 'promociones_taxonomia');
		$output = '';

		// Mostrar nombre de promoción (siempre)
		$nombre_promocion = get_term_meta(
			$term_id,
			'makistyle_cmb2_promociones_taxonomia_nombre_promocion',
			true,
		);
		if ($nombre_promocion) {
			$output .=
				'<div><strong>Nombre:</strong> <span style="color: #00a32a;">' .
				esc_html($nombre_promocion) .
				'</span></div>';
		}

		// Verificar si es hijo de "descuentos" para mostrar porcentaje
		if ($term->parent) {
			$parent = get_term($term->parent, 'promociones_taxonomia');
			if ($parent && $parent->slug === 'descuentos') {
				$porcentaje = get_term_meta(
					$term_id,
					'makistyle_cmb2_promociones_taxonomia_porcentaje_descuento',
					true,
				);
				if ($porcentaje !== '') {
					$output .=
						'<div><strong>Descuento:</strong> <span style="color: #00a32a;">' .
						esc_html($porcentaje) .
						'%</span></div>';
				}

				// Fecha de inicio
				$fecha_inicio = get_term_meta(
					$term_id,
					'makistyle_cmb2_promociones_taxonomia_fecha_inicio',
					true,
				);
				if ($fecha_inicio) {
					$output .=
						'<div><strong>Inicio:</strong> <span style="color: #00a32a;">' .
						date('d-m-Y', $fecha_inicio) .
						'</span></div>';
				}

				// Fecha final
				$fecha_final = get_term_meta(
					$term_id,
					'makistyle_cmb2_promociones_taxonomia_fecha_final',
					true,
				);
				if ($fecha_final) {
					$output .=
						'<div><strong>Final:</strong> <span style="color: #00a32a;">' .
						date('d-m-Y', $fecha_final) .
						'</span></div>';
				}

				// Estado de la promoción
				$promocion_activa = get_term_meta(
					$term_id,
					'makistyle_cmb2_promociones_taxonomia_promocion_activa',
					true,
				);
				$estado_texto =
					$promocion_activa === 'on' ? 'ACTIVA' : 'INACTIVA';
				$estado_color =
					$promocion_activa === 'on' ? '#00a32a' : '#d63638';
				$output .=
					'<div><strong>Estado:</strong> <span style="color: ' .
					$estado_color .
					'; font-weight: bold;">' .
					$estado_texto .
					'</span></div>';
			}
		}

		// Verificar si es hijo de "cupones" para mostrar código
		if ($term->parent) {
			$parent = get_term($term->parent, 'promociones_taxonomia');
			if ($parent && $parent->slug === 'cupones') {
				$codigo = get_term_meta(
					$term_id,
					'makistyle_cmb2_promociones_taxonomia_codigo_descuento',
					true,
				);
				if ($codigo) {
					$output .=
						'<div><strong>Código:</strong> <span style="color: #00a32a;">' .
						esc_html($codigo) .
						'</span></div>';
				}

				// Porcentaje de descuento
				$porcentaje = get_term_meta(
					$term_id,
					'makistyle_cmb2_promociones_taxonomia_porcentaje_descuento',
					true,
				);
				if ($porcentaje !== '') {
					$output .=
						'<div><strong>Descuento:</strong> <span style="color: #00a32a;">' .
						esc_html($porcentaje) .
						'%</span></div>';
				}

				// Fecha de inicio
				$fecha_inicio = get_term_meta(
					$term_id,
					'makistyle_cmb2_promociones_taxonomia_fecha_inicio',
					true,
				);
				if ($fecha_inicio) {
					$output .=
						'<div><strong>Inicio:</strong> <span style="color: #00a32a;">' .
						date('d-m-Y', $fecha_inicio) .
						'</span></div>';
				}

				// Fecha final
				$fecha_final = get_term_meta(
					$term_id,
					'makistyle_cmb2_promociones_taxonomia_fecha_final',
					true,
				);
				if ($fecha_final) {
					$output .=
						'<div><strong>Final:</strong> <span style="color: #00a32a;">' .
						date('d-m-Y', $fecha_final) .
						'</span></div>';
				}

				// Estado de la promoción
				$promocion_activa = get_term_meta(
					$term_id,
					'makistyle_cmb2_promociones_taxonomia_promocion_activa',
					true,
				);
				$estado_texto =
					$promocion_activa === 'on' ? 'ACTIVA' : 'INACTIVA';
				$estado_color =
					$promocion_activa === 'on' ? '#00a32a' : '#d63638';
				$output .=
					'<div><strong>Estado:</strong> <span style="color: ' .
					$estado_color .
					'; font-weight: bold;">' .
					$estado_texto .
					'</span></div>';
			}
		}

		return $output ?: '—';
	}
	return $content;
}
add_filter(
	'manage_promociones_taxonomia_custom_column',
	'makistyle_show_promociones_columns',
	10,
	3,
);

// Añadir columna de Fecha de Lanzamiento en tienda_pt
function makistyle_add_fecha_lanzamiento_column($columns)
{
	$new_columns = [];
	foreach ($columns as $key => $value) {
		$new_columns[$key] = $value;
		if ($key === 'post_info') {
			$new_columns['fecha_lanzamiento'] = __(
				'Fecha Lanzamiento',
				'makistyle',
			);
		}
	}
	return $new_columns;
}
add_filter(
	'manage_tienda_pt_posts_columns',
	'makistyle_add_fecha_lanzamiento_column',
	20,
);

function makistyle_show_fecha_lanzamiento_column($column_name, $post_id)
{
	if ($column_name === 'fecha_lanzamiento') {
		$timestamp = get_post_meta(
			$post_id,
			'makistyle_cmb2_tienda_fecha_lanzamiento2',
			true,
		);
		if ($timestamp) {
			echo '<span style="color: #3180FC;">' .
				esc_html(date('d-m-Y', $timestamp)) .
				'</span>';
		} else {
			echo '<span style="color: #999;">—</span>';
		}
	}
}
add_action(
	'manage_tienda_pt_posts_custom_column',
	'makistyle_show_fecha_lanzamiento_column',
	10,
	2,
);

function makistyle_sortable_fecha_lanzamiento_column($sortable_columns)
{
	$sortable_columns['fecha_lanzamiento'] = 'fecha_lanzamiento';
	return $sortable_columns;
}
add_filter(
	'manage_edit-tienda_pt_sortable_columns',
	'makistyle_sortable_fecha_lanzamiento_column',
);

function makistyle_orderby_fecha_lanzamiento($query)
{
	if (!is_admin() || !$query->is_main_query()) {
		return;
	}

	if ($query->get('post_type') !== 'tienda_pt') {
		return;
	}

	$orderby = $query->get('orderby');

	// Orden manual por la columna fecha_lanzamiento (clic en cabecera)
	if ($orderby === 'fecha_lanzamiento') {
		$query->set('meta_key', 'makistyle_cmb2_tienda_fecha_lanzamiento2');
		$query->set('orderby', 'meta_value_num');
		return;
	}

	// Orden por defecto: fecha de lanzamiento más reciente arriba
	if (empty($orderby)) {
		$query->set('meta_key', 'makistyle_cmb2_tienda_fecha_lanzamiento2');
		$query->set('orderby', 'meta_value_num');
		$query->set('order', 'DESC');
	}
}
add_action('pre_get_posts', 'makistyle_orderby_fecha_lanzamiento');
