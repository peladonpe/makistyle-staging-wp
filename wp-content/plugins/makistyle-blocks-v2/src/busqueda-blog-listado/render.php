<?php

// ── Funciones auxiliares (locales a este bloque) ─────────────────────────────
// mkv2_post_get_image_sizes está centralizada en el tema: inc/imagenes.php

if (!function_exists('mkv2_blog_get_articulo')):
	function mkv2_blog_get_articulo()
	{
		$postId = get_the_ID();
		$titulo = get_the_title();
		$fecha = get_the_date('d/m/Y');
		$permalink = esc_url(get_the_permalink());
		$extracto = esc_html(get_the_excerpt());
		$idYoutube = get_post_meta(
			$postId,
			'makistyle_cmb2_todos_pt_id_youtube_destacado',
			true,
		);
		$origen = rawurlencode(home_url());

		// Categoría
		$nombreCategoria = '';
		$categorias = get_the_terms($postId, 'category');
		if (
			$categorias &&
			!is_wp_error($categorias) &&
			$categorias[0]->slug !== 'sin-categoria'
		) {
			$nombreCategoria = get_term_meta(
				$categorias[0]->term_id,
				'makistyle_cmb2_category_nombre_singular',
				true,
			);
			if (!$nombreCategoria) {
				$nombreCategoria = $categorias[0]->name;
			}
		}
		$claseOcultoCategoria = $nombreCategoria
			? ''
			: ' blog-card__categoria--hidden';

		// ── Columna media ─────────────────────────────────────────────────────
		if ($idYoutube) {
			// Imagen como reserva de altura (invisible) + spinner + div player
			$idImagen  = get_post_thumbnail_id($postId);
			$altImagen = esc_attr(
				get_post_meta($idImagen, '_wp_attachment_image_alt', true),
			);

			static $counter = 0;
			$counter++;
			$playerId = 'youtubeDestacado-' . $counter;
			$spinnerId = 'spinner-youtube-' . $counter;
			$height = 400;
			$esAutoplay = 0;

			$htmlYoutubeFinal = sprintf(
				/*html*/ '
				<div id="%5$s" class="mk-spinner-div">
					<div class="mk-spinner" role="status">
						<span class="mk-visually-hidden">Loading…</span>
					</div>
				</div>
				<div class="blog-card__video">
					<div id="%4$s" data-youtube-destacado data-videoid="%1$s" data-autoplay="%2$s" data-height="%3$s" data-spinner-id="%5$s"></div>
				</div>
				',
				esc_attr($idYoutube), // %1$s
				$esAutoplay, // %2$s
				$height, // %3$s
				$playerId, // %4$s
				$spinnerId, // %5$s
			);

			$fallback = mkv2_post_get_card_figure_fallback($postId, 400);

			$media = sprintf(
				/*html*/ '
				%1$s
				%2$s',
				$fallback, // %1$s
				$htmlYoutubeFinal, // %2$s
			);
		} else {
			// Sin YouTube: imagen normal
			$media = mkv2_post_get_card_figure(
				$postId,
				get_the_permalink(),
				'blog-card__img-link',
				'',
				'blog-card__img'
			);
		}

		// ── Card completa (horizontal) ────────────────────────────────────────
		return sprintf(
			'<div class="blog-card">
				<div class="blog-card__media">%1$s</div>
				<div class="blog-card__content">
					<a href="%5$s" class="blog-card__content-link">
						<div class="blog-card__header">
							<span class="blog-card__categoria%7$s">%6$s</span>
							<p class="blog-card__date">%2$s</p>
						</div>
						<h3 class="blog-card__title">%3$s</h3>
						<p class="blog-card__excerpt mkv2-clamp">%4$s</p>
					</a>
				</div>
			</div>',
			$media, // %1$s
			esc_html($fecha), // %2$s
			esc_html($titulo), // %3$s
			$extracto, // %4$s
			$permalink, // %5$s
			esc_html($nombreCategoria), // %6$s
			$claseOcultoCategoria, // %7$s
		);
	}
endif;

// ── Lógica principal ──────────────────────────────────────────────────────────
// Solo renderiza cuando la búsqueda es de blog (?t=blog).
if (get_query_var('t') !== 'blog') {
	return;
}

$paginacion = isset($attributes['esPaginado'])
	? (bool) $attributes['esPaginado']
	: true;

$cards = '';
$hay_resultados = false;
while (have_posts()):
	the_post();
	$hay_resultados = true;
	$cards .= mkv2_blog_get_articulo();
endwhile;

$paginacionHtml = '';
if ($paginacion) {
	$paginacionHtml = get_the_posts_pagination([
		'prev_text' => __('« Página anterior', 'makistyle'),
		'next_text' => __('Página siguiente »', 'makistyle'),
	]);
}
?>
<section <?php echo get_block_wrapper_attributes([
	'class' => 'blog__wrapper-listado seccion',
]); ?>>
	<?php if ($hay_resultados): ?>
		<div class="blog__cards-container">
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