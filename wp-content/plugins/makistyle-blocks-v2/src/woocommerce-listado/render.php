<?php

// ── Funciones auxiliares (locales a este bloque) ─────────────────────────────
// mkv2_post_get_image_sizes está centralizada en el tema: inc/imagenes.php

if (!function_exists('mkv2_woocommerce_get_articulo')):
	function mkv2_woocommerce_get_articulo()
	{
		$postId = get_the_ID();
		$fechaLanzamiento = get_post_meta(
			$postId,
			'makistyle_cmb2_woocommerce_fecha_lanzamiento2',
			true,
		);
		$fechaFormateada = $fechaLanzamiento
			? date('d-m-Y', $fechaLanzamiento)
			: '';

		// Obtener precio regular de WooCommerce de forma segura
		$precio = '';
		if (function_exists('wc_get_product')) {
			$product = wc_get_product($postId);
			if ($product) {
				$precio = $product->get_regular_price();
			}
		} else {
			$precio = get_post_meta($postId, '_regular_price', true);
		}

		$autores = get_post_meta(
			$postId,
			'makistyle_cmb2_woocommerce_autores',
			true,
		);
		$idYoutube = get_post_meta(
			$postId,
			'makistyle_cmb2_woocommerce_id_youtube_destacado',
			true,
		);
		$origen = rawurlencode(home_url());
		$titulo = get_the_title();
		$tituloCompleto = $titulo . ($autores ? ' – ' . $autores : '');

		$seo_description = get_post_meta(
			$postId,
			'makistyle_cmb2_seo_description',
			true,
		);

		// Tipo recurso (Categoría de WooCommerce)
		$nombreTipoRecursoSingular = '';
		$tipoRecursoPost = get_the_terms($postId, 'product_cat');
		if ($tipoRecursoPost && !is_wp_error($tipoRecursoPost)) {
			$nombreTipoRecursoSingular = get_term_meta(
				$tipoRecursoPost[0]->term_id,
				'makistyle_cmb2_tipo_recurso_taxonomia_nombre_singular',
				true,
			);
			if (!$nombreTipoRecursoSingular) {
				$nombreTipoRecursoSingular = $tipoRecursoPost[0]->name;
			}
		}

		// ── Elemento superior: YouTube o imagen ──────────────────────────────────
		if ($idYoutube) {
			static $counter = 0;
			$counter++;
			$playerId = 'youtubeDestacado-' . $counter;
			$spinnerId = 'spinner-youtube-' . $counter;
			$height = 400;
			$esAutoplay = 0;

			$elementoSuperior = sprintf(
				'<div class="mkv2-yt-container">
				<div id="%3$s" class="mk-spinner-div">
					<div class="mk-spinner" role="status">
						<span class="mk-visually-hidden">Loading…</span>
					</div>
				</div>
				<div class="mkv2-yt-iframe-wrap">
					<div id="%2$s" data-youtube-destacado data-videoid="%1$s" data-autoplay="%4$s" data-height="%5$s" data-spinner-id="%3$s"></div>
				</div>
			</div>',
				esc_attr($idYoutube), // %1$s
				$playerId, // %2$s
				$spinnerId, // %3$s
				$esAutoplay, // %4$s
				$height, // %5$s
			);
		} else {
			$elementoSuperior = mkv2_post_get_card_figure(
				$postId,
				get_the_permalink(),
				'mkv2-card-img-link',
				'mkv2-card-figure',
				'mkv2-card-img',
			);
		}

		// ── Descuentos: calcular descuentos activos de la taxonomía ──────────────
		$maxDescuentoDescuentos = 0;
		$descuentos = get_the_terms($postId, 'descuentos_taxonomia');
		if ($descuentos && !is_wp_error($descuentos)) {
			foreach ($descuentos as $descuento) {
				$descuentoActivo = get_term_meta(
					$descuento->term_id,
					'makistyle_cmb2_descuentos_taxonomia_descuento_activo',
					true,
				);
				$fechaInicio = (int) get_term_meta(
					$descuento->term_id,
					'makistyle_cmb2_descuentos_taxonomia_fecha_inicio',
					true,
				);
				$fechaFinal = (int) get_term_meta(
					$descuento->term_id,
					'makistyle_cmb2_descuentos_taxonomia_fecha_final',
					true,
				);
				$ahora = time();

				$dentroDeRango = true;
				// if (!empty($fechaInicio) && $ahora < $fechaInicio) {
				// 	$dentroDeRango = false;
				// }
				// if (!empty($fechaFinal) && $ahora > $fechaFinal) {
				// 	$dentroDeRango = false;
				// }
				$dentroDeRango =
					$fechaInicio &&
					$fechaFinal &&
					$ahora >= $fechaInicio &&
					$ahora <= $fechaFinal;

				if ($descuentoActivo !== 'on' || !$dentroDeRango) {
					continue;
				}

				$porcentajeDescuento = (int) get_term_meta(
					$descuento->term_id,
					'makistyle_cmb2_descuentos_taxonomia_porcentaje_descuento',
					true,
				);

				if (
					$porcentajeDescuento &&
					$porcentajeDescuento > $maxDescuentoDescuentos
				) {
					$maxDescuentoDescuentos = $porcentajeDescuento;
				}
			}
		}

		$precioOriginalVal = floatval($precio);
		$precioFinal = $precioOriginalVal;
		$hayDescuentos = false;

		if ($precioOriginalVal > 0 && $maxDescuentoDescuentos > 0) {
			$hayDescuentos = true;
			$precioFinal =
				$precioOriginalVal -
				($precioOriginalVal * $maxDescuentoDescuentos) / 100;
			if ($precioFinal < 0) {
				$precioFinal = 0;
			}
		}

		// ── Badge de precio ───────────────────────────────────────────────────────
		$badgePrecio = '';
		if ($precio !== '' && $precio !== false && $precio !== null) {
			if ($precioOriginalVal == 0) {
				$textoBoton = 'GRATUITO';
			} elseif ($hayDescuentos) {
				$textoBoton =
					'<span class="mkv2-precio-tachado">' .
					number_format($precioOriginalVal, 2, ',', '.') .
					' €</span> ' .
					number_format($precioFinal, 2, ',', '.') .
					' €';
			} else {
				$textoBoton =
					number_format($precioOriginalVal, 2, ',', '.') . ' €';
			}
			$badgePrecio = sprintf(
				'<div class="mkv2-badge-precio-wrap">
				<span class="mkv2-badge-precio">%1$s</span>
			</div>',
				$textoBoton,
			);
		}

		// ── Card completa ─────────────────────────────────────────────────────────
		return sprintf(
			'<div>
			<div class="mkv2-card">
				<div class="mkv2-card-body">
					<div class="mkv2-card-media">%1$s</div>
					<a href="%8$s" class="mkv2-card-link">
						<div class="mkv2-card-header">
							<span class="mkv2-badge-tipo%9$s">%2$s</span>
							<p class="mkv2-card-fecha">%3$s</p>
						</div>
						<p class="mkv2-card-titulo">%4$s</p>
						%6$s
						<p class="mkv2-clamp">%7$s</p>
					</a>
				</div>
			</div>
		</div>',
			$elementoSuperior, // %1$s
			esc_html($nombreTipoRecursoSingular), // %2$s
			esc_html($fechaFormateada), // %3$s
			esc_html($tituloCompleto), // %4$s
			'', // %5$s
			$badgePrecio, // %6$s
			esc_html($seo_description), // %7$s
			esc_url(get_the_permalink()), // %8$s
			$nombreTipoRecursoSingular ? '' : ' mkv2-badge-tipo--hidden', // %9$s
		);
	}
endif;

// ── Lógica principal ──────────────────────────────────────────────────────────
// Usa el loop nativo de WordPress (main query), ya ordenado y filtrado por functions.php

$paginacion = isset($attributes['esPaginado'])
	? (bool) $attributes['esPaginado']
	: false;

$cards = '';
$hay_resultados = false;
while (have_posts()):
	the_post();
	$hay_resultados = true;
	$cards .= mkv2_woocommerce_get_articulo();
endwhile;

$paginacionHtml = '';
if ($paginacion) {
	$pag_links = get_the_posts_pagination([
		'prev_text' => __('« Página anterior', 'makistyle'),
		'next_text' => __('Página siguiente »', 'makistyle'),
	]);
	if ($pag_links) {
		$paginacionHtml = '<div class="paginacion">' . $pag_links . '</div>';
	}
}
?>
<section <?php echo get_block_wrapper_attributes([
	'class' => 'woocommerce__wrapper-listado-cards seccion',
]); ?>>
	<?php if ($hay_resultados): ?>
		<div class="woocommerce__grid-cards">
			<?php echo $cards; ?>
		</div>
		<?php echo $paginacionHtml; ?>
	<?php else: ?>
		<p class="woocommerce__sin-resultados">
			<?php esc_html_e('No se han encontrado resultados.', 'makistyle'); ?>
		</p>
	<?php endif; ?>
</section>
