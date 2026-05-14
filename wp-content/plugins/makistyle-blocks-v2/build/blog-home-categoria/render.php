<?php

// ── Lógica principal ──────────────────────────────────────────────────────────

$categorias = get_categories([
	'hide_empty' => true,
	'orderby' => 'name',
	'order' => 'ASC',
]);

$cardsCategoria = '';
if (!empty($categorias) && !is_wp_error($categorias)) {
	$slugInicio = isset($attributes['categoriaInicio'])
		? trim($attributes['categoriaInicio'])
		: '';
	if ($slugInicio !== '') {
		usort($categorias, function ($a, $b) use ($slugInicio) {
			if ($a->slug === $slugInicio) {
				return -1;
			}
			if ($b->slug === $slugInicio) {
				return 1;
			}
			return 0;
		});
	}
	foreach ($categorias as $categoria) {
		if ($categoria->slug === 'sin-categoria') {
			continue;
		}

		$posts = get_posts([
			'post_type' => 'post',
			'posts_per_page' => 3,
			'orderby' => 'date',
			'order' => 'DESC',
			'category' => $categoria->term_id,
		]);

		if (empty($posts)) {
			continue;
		}

		$items = '';
		foreach ($posts as $post) {
			$items .= sprintf(
				'<li><a href="%s" class="mkv2-tipo-link">%s</a></li>',
				esc_url(get_permalink($post->ID)),
				esc_html(get_the_title($post->ID)),
			);
		}

		$cardsCategoria .= sprintf(
			'<div>
			<div class="mkv2-card mkv2-card--tipo">
				<div class="mkv2-card-body">
					<div class="mkv2-tipo-contenido">
						<h4 class="mkv2-tipo-titulo gradient-titulo">%s</h4>
						<ul class="mkv2-tipo-lista">%s</ul>
					</div>
					<div class="blog-cat-botones">
						<a class="blog-cat-btn" href="%s">%s</a>
					</div>
				</div>
			</div>
		</div>',
			esc_html($categoria->name),
			$items,
			esc_url(get_category_link($categoria->term_id)),
			'Ver ' . esc_html(strtolower($categoria->name)),
		);
	}
}

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
	'class' => 'blog__wrapper-categoria-cards seccion',
]); ?>>
	<div class="mkv2-destacados-encabezado">
		<h3 class="monoton-regular mkv2-destacados-titulo">Art&#237;culos del <a href="<?php echo $blogUrl; ?>" class="mkv2-destacados-tienda-link">blog</a><a href="<?php echo $blogUrl; ?>" class="mkv2-destacados-link-icon" aria-label="<?php esc_attr_e(
	'Todo el blog',
	'makistyle',
); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
					<polyline points="15 3 21 3 21 9" />
					<line x1="10" y1="14" x2="21" y2="3" />
				</svg></a> por categor&#237;a</h3>
	</div>
	<div class="mkv2-carousel-container">
		<button class="mkv2-carousel-btn mkv2-carousel-btn--prev" aria-label="<?php esc_attr_e(
  	'Anterior',
  	'makistyle',
  ); ?>" hidden>&#8249;</button>
		<div class="mkv2-carousel-viewport">
			<div class="blog-categoria__cards-container<?php echo empty($cardsCategoria) ? ' justify-content-center' : ''; ?>">
				<?php echo $cardsCategoria; ?>
				<div>
					<div class="mkv2-card">
						<div class="mkv2-card-body">
							<div class="blog-cat-card-inner">
								<figure class="blog-cat-imagen">
									<a href="<?php echo $blogUrl; ?>">
										<?php echo $blogImg; ?>
									</a>
								</figure>
								<div class="blog-cat-botones">
									<a class="blog-cat-btn" href="<?php echo $blogUrl; ?>"><?php esc_html_e(
	'Todo el blog',
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