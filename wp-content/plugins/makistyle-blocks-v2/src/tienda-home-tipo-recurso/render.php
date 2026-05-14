<?php

// ── Lógica principal ──────────────────────────────────────────────────────────

$terminos = get_terms([
	'taxonomy' => 'tipo_recurso_taxonomia',
	'hide_empty' => true,
]);

$queryTipos = new WP_Query([
	'post_type' => 'tienda_pt',
	'posts_per_page' => -1,
	'fields' => 'ids',
	'orderby' => 'meta_value_num',
	'meta_key' => 'makistyle_cmb2_tienda_fecha_lanzamiento2',
	'order' => 'DESC',
	'no_found_rows' => true,
	'update_post_meta_cache' => false,
	'update_post_term_cache' => false,
	'tax_query' => [
		[
			'taxonomy' => 'tipo_recurso_taxonomia',
			'operator' => 'EXISTS',
		],
	],
]);

$grupos = [];
foreach ($queryTipos->posts as $postId) {
	$terms = get_the_terms($postId, 'tipo_recurso_taxonomia');
	if (!$terms || is_wp_error($terms)) {
		continue;
	}
	$termId = $terms[0]->term_id;
	if (!isset($grupos[$termId])) {
		$grupos[$termId] = [];
	}
	if (count($grupos[$termId]) < 3) {
		$grupos[$termId][] = [
			'titulo' => get_the_title($postId),
			'url' => get_permalink($postId),
		];
	}
}
wp_reset_postdata();

$cardsTipos = '';
if (!is_wp_error($terminos)) {
	$slugInicio = isset($attributes['tipoRecursoInicio'])
		? trim($attributes['tipoRecursoInicio'])
		: '';
	if ($slugInicio !== '') {
		usort($terminos, function ($a, $b) use ($slugInicio) {
			if ($a->slug === $slugInicio) {
				return -1;
			}
			if ($b->slug === $slugInicio) {
				return 1;
			}
			return 0;
		});
	}
	foreach ($terminos as $termino) {
		if (empty($grupos[$termino->term_id])) {
			continue;
		}
		$items = '';
		foreach ($grupos[$termino->term_id] as $item) {
			$items .= sprintf(
				'<li><a href="%s" class="mkv2-tipo-link">%s</a></li>',
				esc_url($item['url']),
				esc_html($item['titulo']),
			);
		}
		$cardsTipos .= sprintf(
			'<div>
			<div class="mkv2-card mkv2-card--tipo">
				<div class="mkv2-card-body">
					<div class="mkv2-tipo-contenido">
						<h4 class="mkv2-tipo-titulo gradient-titulo">%s</h4>
						<ul class="mkv2-tipo-lista">%s</ul>
					</div>
					<div class="tienda-botones">
						<a class="tienda-btn" href="%s">%s</a>
					</div>
				</div>
			</div>
		</div>',
			esc_html($termino->name),
			$items,
			esc_url(get_term_link($termino)),
			'Ver ' . esc_html(strtolower($termino->name)),
		);
	}
}

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
		<h3 class="monoton-regular mkv2-destacados-titulo">Productos de la <a href="<?php echo $tiendaUrl; ?>" class="mkv2-destacados-tienda-link">tienda</a><a href="<?php echo $tiendaUrl; ?>" class="mkv2-destacados-link-icon" aria-label="<?php esc_attr_e(
	'Toda la tienda',
	'makistyle',
); ?>"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
					<polyline points="15 3 21 3 21 9" />
					<line x1="10" y1="14" x2="21" y2="3" />
				</svg></a> por categoría</h3>
	</div>
	<div class="mkv2-carousel-container">
		<button class="mkv2-carousel-btn mkv2-carousel-btn--prev" aria-label="<?php esc_attr_e(
  	'Anterior',
  	'makistyle',
  ); ?>" hidden>&#8249;</button>
		<div class="mkv2-carousel-viewport">
			<div class="tienda__grid-cards<?php echo empty($cardsTipos) ? ' justify-content-center' : ''; ?>">
				<?php echo $cardsTipos; ?>
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
	'Toda la tienda',
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