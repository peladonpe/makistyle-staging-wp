<?php
/**
 * Custom Store (Tienda Personalizada CPT tienda_pt) Custom Logic & Hooks
 *
 * @package Makistyle
 */

function mkv2_add_query_vars_filter($vars)
{
	$vars[] = 'precio';
	$vars[] = 't';
	return $vars;
}
add_filter('query_vars', 'mkv2_add_query_vars_filter');

/**
 * Calcula el precio final de un producto aplicando los descuentos activos.
 * Replica la lógica de tienda-precio/render.php.
 * Devuelve null si el producto no tiene precio asignado.
 */
function mkv2_calcular_precio_final($post_id)
{
	$precio = get_post_meta($post_id, 'makistyle_cmb2_tienda_precio', true);

	if ($precio === '' || $precio === false || $precio === null) {
		return null;
	}

	$precio = floatval($precio);

	if ($precio <= 0) {
		return $precio;
	}

	$maxDescuentoCupones = 0;
	$maxDescuentoDescuentos = 0;
	$promociones = get_the_terms($post_id, 'promociones_taxonomia');

	if ($promociones && !is_wp_error($promociones)) {
		foreach ($promociones as $promocion) {
			$promocionActiva = get_term_meta(
				$promocion->term_id,
				'makistyle_cmb2_promociones_taxonomia_promocion_activa',
				true,
			);
			$fechaInicio = (int) get_term_meta(
				$promocion->term_id,
				'makistyle_cmb2_promociones_taxonomia_fecha_inicio',
				true,
			);
			$fechaFinal = (int) get_term_meta(
				$promocion->term_id,
				'makistyle_cmb2_promociones_taxonomia_fecha_final',
				true,
			);
			$ahora = time();
			$dentroDeRango =
				$fechaInicio &&
				$fechaFinal &&
				$ahora >= $fechaInicio &&
				$ahora <= $fechaFinal;

			if ($promocionActiva !== 'on' || !$dentroDeRango) {
				continue;
			}

			$porcentajeDescuento = get_term_meta(
				$promocion->term_id,
				'makistyle_cmb2_promociones_taxonomia_porcentaje_descuento',
				true,
			);
			if ($porcentajeDescuento && $promocion->parent != 0) {
				$termPadre = get_term(
					$promocion->parent,
					'promociones_taxonomia',
				);
				if ($termPadre && !is_wp_error($termPadre)) {
					if (
						$termPadre->slug === 'cupones' &&
						$porcentajeDescuento > $maxDescuentoCupones
					) {
						$maxDescuentoCupones = $porcentajeDescuento;
					} elseif (
						$termPadre->slug === 'descuentos' &&
						$porcentajeDescuento > $maxDescuentoDescuentos
					) {
						$maxDescuentoDescuentos = $porcentajeDescuento;
					}
				}
			}
		}
	}

	$precioFinal =
		$precio -
		($precio * $maxDescuentoCupones) / 100 -
		($precio * $maxDescuentoDescuentos) / 100;
	return max(0.0, $precioFinal);
}

function my_custom_query_taxonomias($query)
{
	if (
		!is_admin() &&
		$query->is_main_query() &&
		$query->is_tax('tipo_recurso_taxonomia')
	) {
		// Ordenar por fecha de lanzamiento descendente
		$query->set('orderby', 'meta_value_num');
		$query->set('meta_key', 'makistyle_cmb2_tienda_fecha_lanzamiento2');
		$query->set('order', 'DESC');

		// Filtro de precio: solo cuando precio=0 (recursos gratuitos con descuentos incluidos).
		$precioQueryString = get_query_var('precio');
		if ($precioQueryString === '0') {
			$termSlug = $query->get('tipo_recurso_taxonomia');

			$subQuery = new WP_Query([
				'post_type' => 'tienda_pt',
				'posts_per_page' => -1,
				'fields' => 'ids',
				'tax_query' => [
					[
						'taxonomy' => 'tipo_recurso_taxonomia',
						'field' => 'slug',
						'terms' => $termSlug,
					],
				],
				'suppress_filters' => true,
			]);

			$idsFiltrados = array_values(
				array_filter($subQuery->posts, function ($id) {
					$precioFinal = mkv2_calcular_precio_final($id);
					return $precioFinal !== null && floatval($precioFinal) == 0;
				}),
			);

			// Si no hay resultados, post__in = [0] evita que WP devuelva todos los posts.
			$query->set(
				'post__in',
				!empty($idsFiltrados) ? $idsFiltrados : [0],
			);
		}
	}
}
add_filter('pre_get_posts', 'my_custom_query_taxonomias');

/**
 * Limita las búsquedas del frontend al CPT indicado por el queryString 't'.
 * t=tienda → busca en tienda_pt (por defecto)
 * t=blog   → busca en post
 */
function makistyle_search_only_tienda_or_blog($query)
{
	if (!is_admin() && $query->is_main_query() && $query->is_search()) {
		$target = get_query_var('t');
		if ($target === 'tienda') {
			$query->set('post_type', ['tienda_pt']);
		} elseif ($target === 'blog') {
			$query->set('post_type', ['post']);
		} else {
			$query->set('post__in', [0]);
		}
	}
}
add_action('pre_get_posts', 'makistyle_search_only_tienda_or_blog');
