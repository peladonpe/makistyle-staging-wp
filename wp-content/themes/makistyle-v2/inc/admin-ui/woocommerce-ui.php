<?php

/** Lógica de administración para WooCommerce (product) */

// Columnas específicas para productos WooCommerce
function makistyle_custom_product_columns($columns)
{
	$new_columns = [];
	foreach ($columns as $key => $title) {
		if ($key === 'sku') {
			$new_columns['post_info'] = __('Información', 'makistyle');
		} elseif (strpos(strtolower($title), 'marca') !== false || strpos($key, 'brand') !== false || $key === 'product_brand') {
			$new_columns['fecha_lanzamiento'] = __('Fecha Lanzamiento', 'makistyle');
		} elseif ($key === 'product_tag') {
			// Quitar la columna de etiquetas nativas
			continue;
		} elseif ($key === 'date') {
			// Quitar la columna de fecha de publicación nativa
			continue;
		} elseif ($key === 'thumb') {
			// Quitar la columna de la imagen miniatura nativa de WooCommerce
			continue;
		} else {
			$new_columns[$key] = $title;
		}
	}
	
	// Por si acaso no se encontraron para reemplazarlos, asegurar que estén:
	if (!isset($new_columns['post_info'])) {
		$new_columns['post_info'] = __('Información', 'makistyle');
	}
	if (!isset($new_columns['fecha_lanzamiento'])) {
		$new_columns['fecha_lanzamiento'] = __('Fecha Lanzamiento', 'makistyle');
	}
	if (!isset($new_columns['cupones'])) {
		$new_columns['cupones'] = __('Cupones', 'makistyle');
	}
	if (!isset($new_columns['descuentos'])) {
		$new_columns['descuentos'] = __('Descuentos', 'makistyle');
	}

	return $new_columns;
}
add_filter('manage_edit-product_columns', 'makistyle_custom_product_columns', 20);

function makistyle_show_woocommerce_post_info_column($column_name, $post_id)
{
	if ($column_name === 'post_info') {
		$post = get_post($post_id);
		$current_slug = $post->post_name;

		$mensajeSlug =
			'<p style="margin: 5px 0; color: #3180FC;"><strong>SLUG:</strong> <span style="color: #3180FC;">' . esc_html($current_slug) . '</span></p>';

		$post_status = get_post_status($post_id);
		if ($post_status === 'draft') {
			echo $mensajeSlug;
			return;
		}

		$msgImgDestacada = '<p style="margin: 5px 0; color: #3180FC;"><strong>IMG DESTACADA:</strong> ';
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

		$meta_key_yt = 'makistyle_cmb2_woocommerce_id_youtube_destacado';
		$meta_key_local = 'makistyle_cmb2_woocommerce_url_video_local';

		$linkVideo = get_post_meta($post_id, $meta_key_yt, true);
		if (empty($linkVideo)) {
			$linkVideo = get_post_meta($post_id, $meta_key_local, true);
		}
		$msgLinkVideo = '<p style="margin: 5px 0; color: #3180FC;"><strong>LINK VIDEO:</strong> ';
		$msgLinkVideo .= !empty($linkVideo)
			? '<span style="color: #00a32a;">SI'
			: '<span style="color: #d63638; font-size:1.3em;">NO';
		$msgLinkVideo .= '</span></p>';

		$seoDescription = get_post_meta($post_id, 'makistyle_cmb2_seo_description', true);
		$msgSeo = '<p style="margin: 5px 0; color: #3180FC;"><strong>SEO:</strong> ';
		$msgSeo .= !empty($seoDescription)
			? '<span style="color: #00a32a;">SI'
			: '<span style="color: #d63638; font-size:1.3em;">NO';
		$msgSeo .= '</span></p>';

		echo $mensajeSlug .
			$msgImgDestacada .
			$msgLinkVideo .
			$msgSeo;
	}
}
add_action(
	'manage_product_posts_custom_column',
	'makistyle_show_woocommerce_post_info_column',
	10,
	2,
);

function makistyle_show_woocommerce_fecha_lanzamiento_column($column_name, $post_id)
{
	if ($column_name === 'fecha_lanzamiento') {
		$meta_key = 'makistyle_cmb2_woocommerce_fecha_lanzamiento2';
		$timestamp = get_post_meta($post_id, $meta_key, true);
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
	'manage_product_posts_custom_column',
	'makistyle_show_woocommerce_fecha_lanzamiento_column',
	10,
	2,
);

function makistyle_sortable_woocommerce_fecha_lanzamiento_column($sortable_columns)
{
	$sortable_columns['fecha_lanzamiento'] = 'fecha_lanzamiento';
	return $sortable_columns;
}
add_filter(
	'manage_edit-product_sortable_columns',
	'makistyle_sortable_woocommerce_fecha_lanzamiento_column',
);

function makistyle_orderby_woocommerce_fecha_lanzamiento($query)
{
	if (!is_admin() || !$query->is_main_query()) {
		return;
	}

	$post_type = $query->get('post_type');
	if ($post_type !== 'product') {
		return;
	}

	$orderby  = $query->get('orderby');
	$meta_key = 'makistyle_cmb2_woocommerce_fecha_lanzamiento2';

	// Orden manual por la columna fecha_lanzamiento (clic en cabecera)
	if ($orderby === 'fecha_lanzamiento') {
		$query->set('meta_key', $meta_key);
		$query->set('orderby', 'meta_value_num');
		return;
	}

	// Orden por defecto: fecha de lanzamiento más reciente arriba
	if (empty($orderby)) {
		$query->set('meta_key', $meta_key);
		$query->set('orderby', 'meta_value_num');
		$query->set('order', 'DESC');
	}
}
add_action('pre_get_posts', 'makistyle_orderby_woocommerce_fecha_lanzamiento');

/**
 * Mostrar el contenido de la columna de Cupones en los productos de WooCommerce
 */
function makistyle_show_product_cupones_column($column_name, $post_id)
{
	if ($column_name === 'cupones') {
		global $wpdb;
		
		// Buscar IDs de cupones publicados que contengan el ID de este producto en sus restricciones de productos
		$coupon_ids = $wpdb->get_col($wpdb->prepare(
			"SELECT pm.post_id 
			FROM $wpdb->postmeta pm 
			INNER JOIN $wpdb->posts p ON pm.post_id = p.ID 
			WHERE pm.meta_key = 'product_ids' 
			AND FIND_IN_SET(%d, pm.meta_value) 
			AND p.post_status = 'publish'",
			$post_id
		));

		if (!empty($coupon_ids)) {
			$html = [];
			foreach ($coupon_ids as $cid) {
				$coupon = new WC_Coupon($cid);
				$color = '#00a32a'; // Verde por defecto
				
				// Mostrar en rojo si está caducado
				$expiry_date = $coupon->get_date_expires();
				if ($expiry_date && $expiry_date->getTimestamp() < time()) {
					$color = '#d63638'; // Rojo
				}
				
				$edit_link = get_edit_post_link($cid);
				// WooCommerce convierte get_code() a minúsculas internamente.
				// Para obtener el texto exacto original con sus mayúsculas, consultamos el título.
				$code = get_the_title($cid);
				$html[] = '<a href="' . esc_url($edit_link) . '" style="color: ' . $color . '; font-weight: bold; text-decoration: none;">' . esc_html($code) . '</a>';
			}
			
			if (empty($html)) {
				echo '<span style="color: #999;">—</span>';
			} else {
				echo implode('<br>', $html);
			}
		} else {
			echo '<span style="color: #999;">—</span>';
		}
	} elseif ($column_name === 'descuentos') {
		$terms = get_the_terms($post_id, 'descuentos_taxonomia');
		if ($terms && !is_wp_error($terms)) {
			$html = [];
			foreach ($terms as $term) {
				$activo = get_term_meta($term->term_id, 'makistyle_cmb2_descuentos_taxonomia_descuento_activo', true);
				$fecha_inicio = get_term_meta($term->term_id, 'makistyle_cmb2_descuentos_taxonomia_fecha_inicio', true);
				$fecha_final = get_term_meta($term->term_id, 'makistyle_cmb2_descuentos_taxonomia_fecha_final', true);
				
				$now = time();
				$dentro_de_fecha = true;
				
				if (!empty($fecha_inicio) && $now < (int)$fecha_inicio) {
					$dentro_de_fecha = false;
				}
				if (!empty($fecha_final) && $now > (int)$fecha_final) {
					$dentro_de_fecha = false;
				}
				$color = '#00a32a'; // Verde por defecto
				
				if ($activo !== 'on' || !$dentro_de_fecha) {
					$color = '#d63638'; // Rojo si inactivo o fuera de fecha
				}

				$edit_link = get_edit_term_link($term->term_id, 'descuentos_taxonomia');
				$html[] = '<a href="' . esc_url($edit_link) . '" style="color: ' . $color . '; font-weight: bold; text-decoration: none;">' . esc_html($term->name) . '</a>';
			}
			
			if (empty($html)) {
				echo '<span style="color: #999;">—</span>';
			} else {
				echo implode('<br>', $html);
			}
		} else {
			echo '<span style="color: #999;">—</span>';
		}
	}
}
add_action(
	'manage_product_posts_custom_column',
	'makistyle_show_product_cupones_column',
	10,
	2,
);

// Añadir columna personalizada para mostrar metaboxes de descuentos_taxonomia
function makistyle_add_wc_descuentos_columns($columns)
{
	$columns['meta_info'] = __('Información Meta', 'makistyle');
	return $columns;
}
add_filter(
	'manage_edit-descuentos_taxonomia_columns',
	'makistyle_add_wc_descuentos_columns',
);

function makistyle_show_wc_descuentos_columns($content, $column_name, $term_id)
{
	if ($column_name === 'meta_info') {
		$output = '';

		// Nombre del descuento
		$nombre_descuento = get_term_meta(
			$term_id,
			'makistyle_cmb2_descuentos_taxonomia_nombre_descuento',
			true,
		);
		if ($nombre_descuento) {
			$output .=
				'<div><strong>Nombre:</strong> <span style="color: #00a32a;">' .
				esc_html($nombre_descuento) .
				'</span></div>';
		}

		// Porcentaje de descuento
		$porcentaje = get_term_meta(
			$term_id,
			'makistyle_cmb2_descuentos_taxonomia_porcentaje_descuento',
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
			'makistyle_cmb2_descuentos_taxonomia_fecha_inicio',
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
			'makistyle_cmb2_descuentos_taxonomia_fecha_final',
			true,
		);
		if ($fecha_final) {
			$output .=
				'<div><strong>Final:</strong> <span style="color: #00a32a;">' .
				date('d-m-Y', $fecha_final) .
				'</span></div>';
		}

		// Estado del descuento
		$descuento_activa = get_term_meta(
			$term_id,
			'makistyle_cmb2_descuentos_taxonomia_descuento_activo',
			true,
		);
		$now = time();
		$estado_texto = 'INACTIVO';
		$estado_color = '#d63638';

		if ($descuento_activa === 'on') {
			if (!empty($fecha_inicio) && $now < (int)$fecha_inicio) {
				$estado_texto = 'PROGRAMADO';
			} elseif (!empty($fecha_final) && $now > (int)$fecha_final) {
				$estado_texto = 'CADUCADO';
			} else {
				$estado_texto = 'ACTIVO';
				$estado_color = '#00a32a';
			}
		}
		$output .=
			'<div><strong>Estado:</strong> <span style="color: ' .
			$estado_color .
			'; font-weight: bold;">' .
			$estado_texto .
			'</span></div>';

		return $output ?: '—';
	}
	return $content;
}
add_filter(
	'manage_descuentos_taxonomia_custom_column',
	'makistyle_show_wc_descuentos_columns',
	10,
	3,
);

/**
 * Añadir acceso directo a Cupones bajo el menú de Productos de WooCommerce
 */
function makistyle_add_coupons_submenu_under_products()
{
	add_submenu_page(
		'edit.php?post_type=product',       // Menú padre (Productos)
		__('Cupones', 'woocommerce'),       // Título de la página
		__('Cupones', 'woocommerce'),       // Título del menú
		'manage_woocommerce',               // Capacidad requerida
		'edit.php?post_type=shop_coupon',   // Slug / URL de destino
		null,                                // Callback (ninguno, es un enlace directo)
		6
	);
}
add_action('admin_menu', 'makistyle_add_coupons_submenu_under_products');


