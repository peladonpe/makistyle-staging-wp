<?php

/**
 * CMB2
 */

require_once dirname(__FILE__) . '/inc/posttypes.php';
// require_once dirname(__FILE__) . '/example-functions-cmb2.php';
require_once dirname(__FILE__) . '/inc/metaboxes/metaboxes.php';
require_once dirname(__FILE__) . '/inc/taxonomias.php';
// require_once dirname(__FILE__) . '/inc/queries.php';
require_once dirname(__FILE__) . '/inc/imagenes.php';
require_once dirname(__FILE__) . '/inc/opciones.php';
// require_once dirname(__FILE__) . '/inc/custom-functions.php';
require_once dirname(__FILE__) . '/inc/registrar-meta.php';
require_once dirname(__FILE__) . '/inc/admin-ui/admin-ui.php';
require_once dirname(__FILE__) . '/inc/seo.php';
require_once dirname(__FILE__) . '/inc/woocommerce.php';
require_once dirname(__FILE__) . '/inc/tienda.php';

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





