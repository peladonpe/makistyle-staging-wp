<?php

/**
 * CMB2
 */

require_once dirname(__FILE__) . '/inc/posttypes.php';
// require_once dirname(__FILE__) . '/example-functions-cmb2.php';
require_once dirname(__FILE__) . '/inc/metaboxes.php';
require_once dirname(__FILE__) . '/inc/taxonomias.php';
// require_once dirname(__FILE__) . '/inc/queries.php';
require_once dirname(__FILE__) . '/inc/imagenes.php';
require_once dirname(__FILE__) . '/inc/opciones.php';
// require_once dirname(__FILE__) . '/inc/custom-functions.php';
require_once dirname(__FILE__) . '/inc/registrar-meta.php';
require_once dirname(__FILE__) . '/inc/admin-ui.php';
require_once dirname(__FILE__) . '/inc/seo.php';

// Quitar la etiqueta que indica que ha sido construido con wordpress
remove_action('wp_head', 'wp_generator');

/*
 * Agrega subida svg
 */
function add_file_types_to_uploads($file_types)
{
	$new_filetypes = [];
	$new_filetypes['svg'] = 'image/svg+xml';
	// $new_filetypes['ico'] = 'image/x-icon';
	$file_types = array_merge($file_types, $new_filetypes);
	return $file_types;
}
add_filter('upload_mimes', 'add_file_types_to_uploads');

/*
 *  Carga los Scripts y CSS del theme
 */
function makistyle_frontend_scripts()
{
	/** Styles */

	wp_enqueue_style(
		'styles',
		// get_template_directory_uri() . "/build/styles.min.css",
		get_template_directory_uri() . '/css/styles.css',
		[],
		'2.0.5',
	);

	wp_enqueue_script_module(
		'main',
		// get_template_directory_uri() . "/build/main.min.js",
		get_template_directory_uri() . '/js/main.js',
		[],
		'2.0.5',
	);
}
add_action('wp_enqueue_scripts', 'makistyle_frontend_scripts');

function makistyle_admin_scripts()
{
	/** Styles */

	wp_enqueue_style(
		'admin_styles',
		// get_template_directory_uri() . "/build/admin-styles.min.css",
		get_template_directory_uri() . '/css/admin-styles.css',
		[],
		'2.0.5',
	);
}
add_action('admin_enqueue_scripts', 'makistyle_admin_scripts');

// remove wp version param from any enqueued scripts / styles
// function vc_remove_wp_ver_css_js($src)
// {
//      $src = remove_query_arg('ver', $src);
//      return $src;
// }
// add_filter('style_loader_src', 'vc_remove_wp_ver_css_js', 10);
// add_filter('script_loader_src', 'vc_remove_wp_ver_css_js', 10);
// add_filter('script_module_loader_src', 'vc_remove_wp_ver_css_js', 10);

/**
 * Funciones que se cargan al activar el theme
 */
function pv_setup()
{
	// add_theme_support('post-thumbnails', array('musica_pt', 'tienda_pt', 'post'));
	// add_theme_support('title-tag');

	// Menu de navegación
	// register_nav_menus([
	//      "menu_general" => esc_html__("Menu General", "makistyle"),
	// ]);

	//Soporte para gutenberg
	//Soporte para los estilos por default de los bloques
	// add_theme_support('wp-block-styles');
	// Soporte a una paleta de colores
	// add_theme_support('editor-color-palette', array(
	//      array(
	//           'name' => 'Negro',
	//           'slug' => 'negro',
	//           'color' => '#111111'
	//      ),
	//      array(
	//           'name' => 'Blanco',
	//           'slug' => 'blanco',
	//           'color' => '#fdfdfd'
	//      ),
	//      array(
	//           'name' => 'Color primario',
	//           'slug' => 'color-primario',
	//           'color' => '#01fff7'
	//      ),
	//      array(
	//           'name' => 'Neon rojo',
	//           'slug' => 'neon-rojo',
	//           'color' => '#f70306'
	//      ),
	//      array(
	//           'name' => 'Neon naranja',
	//           'slug' => 'neon-naranja',
	//           'color' => '#f9c351'
	//      ),
	//      array(
	//           'name' => 'Neon morado',
	//           'slug' => 'neon-morado',
	//           'color' => '#fd89f5'
	//      ),
	//      array(
	//           'name' => 'Neon rosa',
	//           'slug' => 'neon-rosa',
	//           'color' => '#fdc3fb'
	//      ),
	//      array(
	//           'name' => 'Neon amarillo',
	//           'slug' => 'neon-amarillo',
	//           'color' => '#fdfd00'
	//      ),
	//      array(
	//           'name' => 'Fondo oscuro cards',
	//           'slug' => 'fondo-oscuro-cards',
	//           'color' => '#1a1a1a'
	//      )
	// ));
	// add_theme_support(
	//      'editor-gradient-presets',
	//      array(
	//           array(
	//                'name' => 'Degradado 1',
	//                'gradient' => 'linear-gradient(90deg, #816448 0%, #4e473a 100%)',
	//                'slug' => 'degradado-1',
	//           )
	//      )
	// );
	// add_theme_support('block_templates');
	// add_theme_support('responsive-embeds');
	// add_theme_support('align-wide');
}
add_action('after_setup_theme', 'pv_setup');

/**
 * Añadir clases en body
 */

function my_class_names($classes)
{
	global $post;
	$current_slug = $post->post_name;
	if (is_home()) {
		$classes[] = 'blog';
	} else {
		$classes[] = $current_slug;
	}
	return $classes;
}
add_filter('body_class', 'my_class_names');

// Excluir ciertos tamaños de imagen de ser generados al cargar
function remove_default_img_sizes($sizes)
{
	$targets = ['1536x1536', '2048x2048'];

	foreach ($sizes as $size_index => $size) {
		if (in_array($size, $targets)) {
			unset($sizes[$size_index]);
		}
	}

	return $sizes;
}
add_filter('intermediate_image_sizes', 'remove_default_img_sizes', 10, 1);

// Añadir tamaño intermedio de imagen
function update_medium_large_size()
{
	update_option('medium_large_size_w', 600);
	update_option('medium_large_size_h', 0);
	update_option('medium_large_crop', 0);
}
add_action('init', 'update_medium_large_size');

// Agregar una tabla con los tamaños de imagen en Ajustes > Medios
add_action(
	'admin_init',
	function () {
		add_settings_section(
			'image_sizes_info',
			esc_html__('Tamaños de Imagen Registrados', 'makistyle'),
			function () {
				global $_wp_additional_image_sizes;
				echo '<table class="wp-list-table widefat fixed striped">';
				echo '<thead><tr><th>' .
					esc_html__('Nombre', 'makistyle') .
					'</th><th>' .
					esc_html__('Dimensiones', 'palmaverde') .
					'</th></tr></thead>';
				foreach (
					(array) wp_get_registered_image_subsizes()
					as $size => $dims
				) {
					if (
						!in_array($size, ['thumbnail', 'medium', 'large'], true)
					) {
						$width = $dims['width'] ?? 0;
						$height = $dims['height'] ?? 0;
						echo "<tr><td><strong>{$size}</strong></td><td>{$width}x{$height}</td>";
					}
				}
			},
			'media',
		);
	},
	PHP_INT_MAX,
);

/**
 * FORMA MOSTRAR URL BLOG
 */
function dcms_posts_add_rewrite_rules($wp_rewrite)
{
	$slug = 'blog';
	$new_rules = [
		$slug . '/page/([0-9]{1,})/?$' =>
			'index.php?post_type=post&paged=' . $wp_rewrite->preg_index(1),
		$slug . '/(.+?)/?$' =>
			'index.php?post_type=post&name=' . $wp_rewrite->preg_index(1),
	];
	$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
	return $wp_rewrite->rules;
}
add_action('generate_rewrite_rules', 'dcms_posts_add_rewrite_rules');

function dcms_posts_change_blog_links($post_link, $id = 0)
{
	$slug = 'blog';
	$post = get_post($id);
	if (is_object($post) && $post->post_type == 'post') {
		return home_url('/' . $slug . '/' . $post->post_name . '/');
	}
	return $post_link;
}
add_filter('post_link', 'dcms_posts_change_blog_links', 1, 3);

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

/**
 * Arreglo para la API de WooCommerce en entorno local (HTTP)
 * Permite la autenticación por parámetros de URL si fallan las cabeceras.
 */
add_filter(
	'woocommerce_rest_check_permissions',
	function ($permission) {
		if (isset($_GET['consumer_key']) && isset($_GET['consumer_secret'])) {
			// Aquí podrías incluso validar las claves contra la DB si quisieras,
			// pero para local, el simple hecho de que se envíen suele ser suficiente
			// para que WC entienda que es una petición autorizada.
			return true;
		}
		return $permission;
	},
	10,
);

/**
 * Ajustar la cantidad de productos por página para la consulta principal de WooCommerce.
 * Esto alinea la paginación global de la tienda con la de nuestro bloque personalizado "woocommerce-listado" (6 por página),
 * evitando el error 404 al navegar a páginas secundarias (ej. /shop/page/2/).
 */
function mkv2_ajustar_paginacion_tienda_woocommerce($query) {
	if (!is_admin() && $query->is_main_query()) {
		if (is_shop() || $query->is_post_type_archive('product') || $query->is_tax('product_cat') || $query->is_tax('product_tag')) {
			$cantidad = (int) get_option('posts_per_page', 6);
			$query->set('posts_per_page', $cantidad);
		}
	}
}
add_action('pre_get_posts', 'mkv2_ajustar_paginacion_tienda_woocommerce', 9999);

add_filter('loop_shop_per_page', function($cols) {
	return (int) get_option('posts_per_page', 6);
}, 9999);


