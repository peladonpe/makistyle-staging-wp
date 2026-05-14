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

		$seo_description = get_post_meta(
			$postId,
			'makistyle_cmb2_seo_description',
			true,
		);

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
			'', // %5$s (promo — no se muestra)
			$badgePrecio, // %6$s
			esc_html($seo_description), // %7$s
			esc_url(get_the_permalink()), // %8$s
			$nombreTipoRecursoSingular ? '' : ' mkv2-badge-tipo--hidden', // %9$s
		);
	}
endif;

// ── Lógica principal ──────────────────────────────────────────────────────────

$cantidad = (int) get_option('posts_per_page', 6);
$paginacion = isset($attributes['esPaginado'])
	? (bool) $attributes['esPaginado']
	: false;

$args = [
	'post_type' => 'tienda_pt',
	'posts_per_page' => $cantidad,
	'paged' => get_query_var('paged', 1),
	'orderby' => 'meta_value_num',
	'meta_key' => 'makistyle_cmb2_tienda_fecha_lanzamiento2',
	'order' => 'DESC',
];

// Filtro precio=0: pre-calcula IDs elegibles (precio final con descuentos)
// para que post__in permita paginar correctamente sobre el conjunto filtrado.
$precioQueryString = get_query_var('precio');
if ($precioQueryString === '0') {
	$subQuery = new WP_Query([
		'post_type' => 'tienda_pt',
		'posts_per_page' => -1,
		'fields' => 'ids',
		'suppress_filters' => true,
	]);

	$idsFiltrados = array_values(
		array_filter($subQuery->posts, function ($id) {
			$precioFinal = mkv2_calcular_precio_final($id);
			return $precioFinal !== null && floatval($precioFinal) == 0;
		}),
	);

	$args['post__in'] = !empty($idsFiltrados) ? $idsFiltrados : [0];
}

$tienda = new WP_Query($args);

$cards = '';
$hay_resultados = false;
while ($tienda->have_posts()):
	$tienda->the_post();
	$hay_resultados = true;
	$cards .= mkv2_tienda_get_articulo();
endwhile;

$paginacionHtml = '';
if ($paginacion) {
	$total_pages = $tienda->max_num_pages;
	if ($total_pages > 1) {
		$current_page = max(1, get_query_var('paged'));
		$base_link = get_pagenum_link(1);
		$base_path = strtok($base_link, '?'); // elimina el query string del base
		$paginacionHtml = '<div class="paginacion">';
		$paginacionHtml .= paginate_links([
			'base' => trailingslashit($base_path) . '%_%',
			'format' => 'page/%#%/',
			'current' => $current_page,
			'total' => $total_pages,
			'add_args' => $precioQueryString === '0' ? ['precio' => '0'] : [],
			'prev_text' => __('« Página anterior', 'makistyle'),
			'next_text' => __('Página siguiente »', 'makistyle'),
		]);
		$paginacionHtml .= '</div>';
	}
}

wp_reset_postdata();
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
		<p class="tienda__sin-resultados">
			<?php esc_html_e('No se han encontrado resultados.', 'makistyle'); ?>
		</p>
	<?php endif; ?>
</section>