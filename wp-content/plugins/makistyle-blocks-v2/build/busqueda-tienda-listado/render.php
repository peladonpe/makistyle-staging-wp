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
		$classBadgeTipo =
			'mkv2-badge-tipo' .
			($nombreTipoRecursoSingular ? '' : ' mkv2-badge-tipo--hidden');
		return sprintf(
			'<div>
			<div class="mkv2-card">
				<div class="mkv2-card-body">
					<div class="mkv2-card-media">%1$s</div>
					<a href="%8$s" class="mkv2-card-link">
						<div class="mkv2-card-header">
							<span class="%9$s">%2$s</span>
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
			'', // %5$s (promo — no se muestra)
			$badgePrecio, // %6$s
			esc_html(get_the_excerpt()), // %7$s
			esc_url(get_the_permalink()), // %8$s
			$classBadgeTipo, // %9$s
		);
	}
endif;

// ── Lógica principal ──────────────────────────────────────────────────────────
// Solo renderiza cuando la búsqueda es de tienda (?t=tienda).
if (get_query_var('t') !== 'tienda') {
	return;
}

$paginacion = isset($attributes['esPaginado'])
	? (bool) $attributes['esPaginado']
	: false;

$cards = '';
$hay_resultados = false;
while (have_posts()):
	the_post();
	$hay_resultados = true;
	$cards .= mkv2_tienda_get_articulo();
endwhile;

$paginacionHtml = '<div class="paginacion">';
if ($paginacion) {
	$paginacionHtml .= get_the_posts_pagination([
		'prev_text' => __('« Página anterior', 'makistyle'),
		'next_text' => __('Página siguiente »', 'makistyle'),
	]);
}
$paginacionHtml .= '</div>';
?>
<section <?php echo get_block_wrapper_attributes([
	'class' => 'tienda__wrapper-listado-cards seccion',
]); ?>>
	<?php if ($hay_resultados): ?>
		<div class="tienda__grid-cards">
			<?php echo $cards; ?>
		</div>
		<?php echo $paginacionHtml; ?>
	<?php else: ?>
		<p class="busqueda-listado__sin-resultados">
			<?php
   $termino = get_search_query();
   if ($termino) {
   	printf(
   		esc_html__(
   			'No se han encontrado resultados para "%s".',
   			'makistyle',
   		),
   		esc_html($termino),
   	);
   } else {
   	esc_html_e('No se han encontrado resultados.', 'makistyle');
   }
   ?>
		</p>
	<?php endif; ?>
</section>