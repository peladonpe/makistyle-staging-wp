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

/**
 * Aplica automáticamente los cupones configurados como "descuento" cuando se añade un producto al carrito.
 * Verifica si el producto tiene cupones asociados en 'product_ids' y si estos tienen 'Es descuento' activado.
 */
function makistyle_aplicar_descuentos_al_carrito( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
	global $wpdb;

	// Buscar cupones publicados que incluyan este producto en sus restricciones ("product_ids")
	$coupon_ids = $wpdb->get_col($wpdb->prepare(
		"SELECT pm.post_id 
		FROM $wpdb->postmeta pm 
		INNER JOIN $wpdb->posts p ON pm.post_id = p.ID 
		WHERE pm.meta_key = 'product_ids' 
		AND FIND_IN_SET(%d, pm.meta_value) 
		AND p.post_status = 'publish'
		AND p.post_type = 'shop_coupon'",
		$product_id
	));

	if ( ! empty( $coupon_ids ) ) {
		foreach ( $coupon_ids as $cid ) {
			// Comprobar si el cupón tiene activada la casilla "Es descuento"
			$es_descuento = get_post_meta( $cid, 'makistyle_cmb2_coupon_es_descuento', true );
			
			if ( $es_descuento === 'on' ) {
				$coupon = new WC_Coupon( $cid );
				$coupon_code = $coupon->get_code();

				// Si el descuento no está aplicado ya en el carrito, lo aplicamos
				if ( WC()->cart && ! WC()->cart->has_discount( $coupon_code ) ) {
					WC()->cart->apply_coupon( $coupon_code );
				}
			}
		}
	}
}
add_action( 'woocommerce_add_to_cart', 'makistyle_aplicar_descuentos_al_carrito', 10, 6 );

/**
 * Actualiza el icono del carrito del menú principal mediante AJAX.
 */
function makistyle_actualizar_icono_carrito_menu($fragments) {
	ob_start();
	$cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/carrito/');
	$cart_count = (function_exists('WC') && WC()->cart) ? WC()->cart->get_cart_contents_count() : 0;
	?>
	<a href="<?php echo esc_url($cart_url); ?>" class="mn-navbar__action mn-navbar__action--cart" aria-label="Carrito">
		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
		<?php if ($cart_count > 0) : ?>
			<span class="mn-navbar__cart-count"><?php echo esc_html($cart_count); ?></span>
		<?php endif; ?>
	</a>
	<?php
	$fragments['a.mn-navbar__action--cart'] = ob_get_clean();
	return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'makistyle_actualizar_icono_carrito_menu');
