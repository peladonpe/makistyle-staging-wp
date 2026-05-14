<?php

// ── Funciones auxiliares (locales a este bloque) ─────────────────────────────
// mkv2_post_get_image_sizes está centralizada en el tema: inc/imagenes.php

if (!function_exists('mkv2_tienda_get_articulo')):
	function mkv2_tienda_get_articulo()
	{
		$postId = get_the_ID();
		$fechaLanzamiento = get_post_meta(
			$postId,
			'makistyle_cmb2_tienda_fecha_lanzamiento2',
			true,
		);
		$fechaFormateada = $fechaLanzamiento
			? date('d-m-Y', $fechaLanzamiento)
			: '';
		$precio = get_post_meta($postId, 'makistyle_cmb2_tienda_precio', true);
		$autores = get_post_meta(
			$postId,
			'makistyle_cmb2_tienda_autores',
			true,
		);
		$idYoutube = get_post_meta(
			$postId,
			'makistyle_cmb2_todos_pt_id_youtube_destacado',
			true,
		);
		$origen = rawurlencode(home_url());
		$titulo = get_the_title();
		$tituloCompleto = $titulo . ($autores ? ' – ' . $autores : '');

		// Tipo recurso
		$nombreTipoRecursoSingular = '';
		$tipoRecursoPost = get_the_terms($postId, 'tipo_recurso_taxonomia');
		if ($tipoRecursoPost && !is_wp_error($tipoRecursoPost)) {
			$nombreTipoRecursoSingular = get_term_meta(
				$tipoRecursoPost[0]->term_id,
				'makistyle_cmb2_tipo_recurso_taxonomia_nombre_singular',
				true,
			);
		}

		// ── Elemento superior: YouTube o imagen ──────────────────────────────────
		if ($idYoutube) {
			static $counter = 0;
			$counter++;
			$playerId = 'youtubeDestacado2-' . $counter;
			$spinnerId = 'spinner-youtube2-' . $counter;
			$height = 400;
			$esAutoplay = 0;

			$elementoSuperior = sprintf(
				/*html*/ '
				<div class="mkv2-yt-container">
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
				'mkv2-card-img'
			);
		}

		// ── Promociones: calcular descuentos activos ──────────────────────────────
		$maxDescuentoCupones = 0;
		$maxDescuentoDescuentos = 0;
		$promociones = get_the_terms($postId, 'promociones_taxonomia');
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

		$precioFinal = $precio;
		$hayDescuentos = false;
		if (
			$precio > 0 &&
			($maxDescuentoCupones > 0 || $maxDescuentoDescuentos > 0)
		) {
			$hayDescuentos = true;
			$precioFinal =
				$precio -
				($precio * $maxDescuentoCupones) / 100 -
				($precio * $maxDescuentoDescuentos) / 100;
			if ($precioFinal < 0) {
				$precioFinal = 0;
			}
		}

		// ── Badge de precio ───────────────────────────────────────────────────────
		$badgePrecio = '';
		if ($precio !== '' && $precio !== false && $precio !== null) {
			if ($precio == 0) {
				$textoBoton = 'GRATUITO';
			} elseif ($hayDescuentos) {
				$textoBoton =
					'<span class="mkv2-precio-tachado">' .
					esc_html($precio) .
					' €</span>' .
					number_format($precioFinal, 2, ',', '.') .
					' €';
			} else {
				$textoBoton = esc_html($precio) . ' €';
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
						<p class="mkv2-card-promo">%5$s</p>
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
			'', // %5$s (promo — no se muestra)
			$badgePrecio, // %6$s
			esc_html(get_the_excerpt()), // %7$s
			esc_url(get_the_permalink()), // %8$s
			$nombreTipoRecursoSingular ? '' : ' mkv2-badge-tipo--hidden', // %9$s
		);
	}
endif;

// ── Lógica principal ──────────────────────────────────────────────────────────

$cantidad = isset($attributes['numeroProductosDestacados'])
	? (int) $attributes['numeroProductosDestacados']
	: 6;

$args = [
	'post_type' => 'tienda_pt',
	'posts_per_page' => $cantidad,
	'orderby' => 'meta_value_num',
	'meta_key' => 'makistyle_cmb2_tienda_fecha_lanzamiento2',
	'order' => 'DESC',
	'tax_query' => [
		[
			'taxonomy' => 'etiquetas_tienda_taxonomia',
			'field' => 'slug',
			'terms' => 'producto-destacado',
		],
	],
];

$tienda = new WP_Query($args);

$cards = '';
while ($tienda->have_posts()):
	$tienda->the_post();
	$cards .= mkv2_tienda_get_articulo();
endwhile;

wp_reset_postdata();

$tiendaPage = get_page_by_path('tienda');
$tiendaUrl = $tiendaPage
	? esc_url(get_permalink($tiendaPage->ID))
	: esc_url(home_url('/tienda/'));
$tiendaImg = $tiendaPage
	? get_the_post_thumbnail($tiendaPage->ID, 'medium', [
		'loading' => 'lazy',
		'width' => '400',
		'height' => '225',
	])
	: '';
?>
<section <?php echo get_block_wrapper_attributes([
	'class' => 'tienda__wrapper-listado-cards seccion',
]); ?>>
	<div class="mkv2-destacados-encabezado">
		<h3 class="monoton-regular mkv2-destacados-titulo">Productos destacados de la <a href="<?php echo $tiendaUrl; ?>" class="mkv2-destacados-tienda-link">tienda</a><a href="<?php echo $tiendaUrl; ?>" class="mkv2-destacados-link-icon" aria-label="<?php esc_attr_e(
	'Ir a la tienda',
	'makistyle',
); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
					<polyline points="15 3 21 3 21 9" />
					<line x1="10" y1="14" x2="21" y2="3" />
				</svg></a></h3>
	</div>
	<div class="mkv2-carousel-container">
		<button class="mkv2-carousel-btn mkv2-carousel-btn--prev" aria-label="<?php esc_attr_e(
  	'Anterior',
  	'makistyle',
  ); ?>" hidden>&#8249;</button>
		<div class="mkv2-carousel-viewport">
			<div class="tienda__grid-cards<?php echo empty($cards) ? ' justify-content-center' : ''; ?>">
				<?php echo $cards; ?>
				<div>
					<div class="mkv2-card">
						<div class="mkv2-card-body">
							<div class="tienda-card-inner">
								<figure class="tienda-imagen">
									<a href="<?php echo $tiendaUrl; ?>">
										<?php echo $tiendaImg; ?>
									</a>
								</figure>
								<div class="tienda-botones">
									<a class="tienda-btn" href="<?php echo $tiendaUrl; ?>"><?php esc_html_e(
	'Ir a la tienda',
	'makistyle',
); ?></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<button class="mkv2-carousel-btn mkv2-carousel-btn--next" aria-label="<?php esc_attr_e(
  	'Siguiente',
  	'makistyle',
  ); ?>">&#8250;</button>
	</div>
</section>