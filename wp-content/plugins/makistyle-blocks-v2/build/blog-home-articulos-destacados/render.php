<?php

// ── Funciones auxiliares (locales a este bloque) ─────────────────────────────
// mkv2_post_get_image_sizes está centralizada en el tema: inc/imagenes.php

if (!function_exists('mkv2_blog_home_get_articulo')):
	function mkv2_blog_home_get_articulo()
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

		if ($idYoutube) {
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
			$media = mkv2_post_get_card_figure(
				$postId,
				get_the_permalink(),
				'blog-card__img-link',
				'',
				'blog-card__img'
			);
		}

		return sprintf(
			'<div>
			<div class="blog-card">
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

$cantidad = isset($attributes['numeroArticulosDestacados'])
	? (int) $attributes['numeroArticulosDestacados']
	: 6;

$args = [
	'post_type' => 'post',
	'posts_per_page' => $cantidad,
	'orderby' => 'date',
	'order' => 'DESC',
	'tax_query' => [
		[
			'taxonomy' => 'post_tag',
			'field' => 'slug',
			'terms' => 'articulo-destacado',
		],
	],
];

$blog = new WP_Query($args);

$cards = '';
while ($blog->have_posts()):
	$blog->the_post();
	$cards .= mkv2_blog_home_get_articulo();
endwhile;

wp_reset_postdata();

$blogPageId = get_option('page_for_posts');
$blogUrl = $blogPageId
	? esc_url(get_permalink($blogPageId))
	: esc_url(home_url('/blog/'));
$blogImg = $blogPageId
	? get_the_post_thumbnail($blogPageId, 'medium', [
		'loading' => 'lazy',
		'width' => '400',
		'height' => '225',
	])
	: '';
?>
<section <?php echo get_block_wrapper_attributes([
	'class' => 'blog__wrapper-listado-cards seccion',
]); ?>>
	<div class="mkv2-destacados-encabezado">
		<h3 class="monoton-regular mkv2-destacados-titulo">Art&#237;culos destacados del <a href="<?php echo $blogUrl; ?>" class="mkv2-destacados-tienda-link">blog</a><a href="<?php echo $blogUrl; ?>" class="mkv2-destacados-link-icon" aria-label="<?php esc_attr_e(
	'Ir al blog',
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
			<div class="blog__cards-container<?php echo empty($cards) ? ' justify-content-center' : ''; ?>">
				<?php echo $cards; ?>
				<div>
					<div class="blog-card">
						<div class="blog-card-inner">
							<figure class="blog-imagen">
								<a href="<?php echo $blogUrl; ?>">
									<?php echo $blogImg; ?>
								</a>
							</figure>
							<div class="blog-botones">
								<a class="blog-btn" href="<?php echo $blogUrl; ?>"><?php esc_html_e(
	'Ir al blog',
	'makistyle',
); ?></a>
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