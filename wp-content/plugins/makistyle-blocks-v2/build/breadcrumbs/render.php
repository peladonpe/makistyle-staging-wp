<?php
// ── Breadcrumbs ───────────────────────────────────────────────────────────────
// Estructura: Home > [Blog|Tienda] > [Artículo|Producto]
// Detecta el contexto mediante las clases del <body> de WordPress.

$body_classes = get_body_class();

// URLs de Home
$home_url   = esc_url(home_url('/'));
$home_texto = __('Home', 'makistyle');

if (in_array('single-tienda_pt', $body_classes) || (function_exists('is_product') && is_product())) {
	// ── Contexto: producto de tienda ──────────────────────────────────────────
	$tienda_url = home_url('/tienda/');
	if (function_exists('wc_get_page_id')) {
		$shop_page_id = wc_get_page_id('shop');
		if ($shop_page_id > 0) {
			$tienda_url = get_permalink($shop_page_id);
		}
	} else {
		$tienda_page = get_page_by_path('tienda');
		if ($tienda_page) {
			$tienda_url = get_permalink($tienda_page->ID);
		}
	}

	$items = [
		['url' => $home_url,             'texto' => $home_texto,                   'enlace' => true],
		['url' => esc_url($tienda_url),  'texto' => __('Tienda', 'makistyle'),     'enlace' => true],
		['url' => '',                    'texto' => __('Producto', 'makistyle'),   'enlace' => false],
	];
} elseif (in_array('single-post', $body_classes)) {
	// ── Contexto: entrada individual de blog ──────────────────────────────────
	$blog_page_id = get_option('page_for_posts');
	$blog_url     = $blog_page_id
		? get_permalink($blog_page_id)
		: home_url('/blog/');

	$items = [
		['url' => $home_url,            'texto' => $home_texto,                  'enlace' => true],
		['url' => esc_url($blog_url),   'texto' => __('Blog', 'makistyle'),      'enlace' => true],
		['url' => '',                   'texto' => __('Artículo', 'makistyle'),  'enlace' => false],
	];
} elseif (is_home() || (in_array('archive', $body_classes) && in_array('category', $body_classes))) {
	// ── Contexto: listado de blog / archivo de categoría ──────────────────────
	$blog_page_id = get_option('page_for_posts');
	$blog_url     = $blog_page_id
		? get_permalink($blog_page_id)
		: home_url('/blog/');

	$items = [
		['url' => $home_url,          'texto' => $home_texto,             'enlace' => true],
		['url' => esc_url($blog_url), 'texto' => __('Blog', 'makistyle'), 'enlace' => false],
	];
} elseif (in_array('archive', $body_classes) && (in_array('tax-tipo_recurso_taxonomia', $body_classes) || (function_exists('is_product_taxonomy') && is_product_taxonomy()))) {
	// ── Contexto: archivo de tienda por tipo de recurso o taxonomía de producto
	$tienda_url = home_url('/tienda/');
	if (function_exists('wc_get_page_id')) {
		$shop_page_id = wc_get_page_id('shop');
		if ($shop_page_id > 0) {
			$tienda_url = get_permalink($shop_page_id);
		}
	} else {
		$tienda_page = get_page_by_path('tienda');
		if ($tienda_page) {
			$tienda_url = get_permalink($tienda_page->ID);
		}
	}

	$items = [
		['url' => $home_url,             'texto' => $home_texto,              'enlace' => true],
		['url' => esc_url($tienda_url),  'texto' => __('Tienda', 'makistyle'), 'enlace' => false],
	];
} elseif (function_exists('is_shop') && is_shop()) {
	// ── Contexto: tienda (catálogo) de WooCommerce ────────────────────────────
	$items = [
		['url' => $home_url, 'texto' => $home_texto, 'enlace' => true],
		['url' => '',        'texto' => __('Tienda', 'makistyle'), 'enlace' => false],
	];
} elseif (is_front_page()) {
	// ── Contexto: home ────────────────────────────────────────────────────────
	$items = [
		['url' => $home_url, 'texto' => $home_texto, 'enlace' => false],
	];
} elseif (is_page()) {
	// ── Contexto: página estática ─────────────────────────────────────────────
	$items = [
		['url' => $home_url, 'texto' => $home_texto,           'enlace' => true],
		['url' => '',        'texto' => get_the_title(),        'enlace' => false],
	];
} else {
	// ── Fallback: solo Home ───────────────────────────────────────────────────
	$items = [
		['url' => $home_url, 'texto' => $home_texto, 'enlace' => false],
	];
}

// ── Renderizar ────────────────────────────────────────────────────────────────
?>
<nav <?php echo get_block_wrapper_attributes(['aria-label' => __('Ruta de navegación', 'makistyle'), 'class' => 'seccion']); ?>>
	<ol class="breadcrumbs__lista">
		<?php foreach ($items as $i => $item): ?>
			<li class="breadcrumbs__item<?php echo $item['enlace'] ? '' : ' breadcrumbs__item--actual'; ?>">
				<?php if ($item['enlace']): ?>
					<a class="breadcrumbs__enlace" href="<?php echo $item['url']; ?>">
						<?php echo esc_html($item['texto']); ?>
					</a>
					<span class="breadcrumbs__separador" aria-hidden="true">&rsaquo;</span>
				<?php else: ?>
					<span class="breadcrumbs__actual" aria-current="page">
						<?php echo esc_html($item['texto']); ?>
					</span>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ol>
</nav>
