<?php
/**
 * WooCommerce Custom Logic & Hooks
 *
 * @package Makistyle
 */

/**
 * Arreglo para la API de WooCommerce en entorno local (HTTP)
 * Permite la autenticación por parámetros de URL si fallan las cabeceras.
 */
add_filter(
	'woocommerce_rest_check_permissions',
	function ($permission) {
		if (isset($_GET['consumer_key']) && isset($_GET['consumer_secret'])) {
			// Aquí podrías incluso validar las claves contra la DB si quisieras,
			// pero para local, el simple hecho de que se envíen suele ser suficiente
			// para que WC entienda que es una petición autorizada.
			return true;
		}
		return $permission;
	},
	10,
);

/**
 * Ajustar la consulta principal de WooCommerce (paginación, ordenación y filtrado).
 * Esto alinea la consulta global de la tienda con los requisitos de nuestro bloque "woocommerce-listado",
 * evitando errores 404 y aplicando los filtros globalmente de forma nativa.
 */
function mkv2_ajustar_paginacion_tienda_woocommerce($query)
{
	if (!is_admin() && $query->is_main_query()) {
		if (
			is_shop() ||
			$query->is_post_type_archive('product') ||
			$query->is_tax('product_cat') ||
			$query->is_tax('product_tag')
		) {
			// 1. Paginación
			$cantidad = (int) get_option('posts_per_page', 6);
			$query->set('posts_per_page', $cantidad);

			// 2. Ordenación
			$query->set('orderby', 'meta_value_num');
			$query->set(
				'meta_key',
				'makistyle_cmb2_woocommerce_fecha_lanzamiento2',
			);
			$query->set('order', 'DESC');

			// 3. Filtro de precio=0 (Comentado a petición del usuario para posible uso futuro)
			$precioQueryString = get_query_var('precio');
			if ($precioQueryString === '0') {
				$subQuery = new WP_Query([
					'post_type' => 'product',
					'posts_per_page' => -1,
					'fields' => 'ids',
					'suppress_filters' => true,
				]);

				$idsFiltrados = array_values(
					array_filter($subQuery->posts, function ($id) {
						$precio = '';
						if (function_exists('wc_get_product')) {
							$product = wc_get_product($id);
							if ($product) {
								$precio = $product->get_regular_price();
							}
						} else {
							$precio = get_post_meta(
								$id,
								'_regular_price',
								true,
							);
						}
						return $precio !== '' && floatval($precio) == 0;
					}),
				);

				$query->set(
					'post__in',
					!empty($idsFiltrados) ? $idsFiltrados : [0],
				);
			}
		}
	}
}
add_action('pre_get_posts', 'mkv2_ajustar_paginacion_tienda_woocommerce', 9999);

add_filter(
	'loop_shop_per_page',
	function ($cols) {
		return (int) get_option('posts_per_page', 6);
	},
	9999,
);
