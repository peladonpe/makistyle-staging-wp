<?php

/**
 * Plugin Name:       Makistyle Blocks V2
 * Description:       Bloques personalizados para Makistyle versión 2
 * Version:           2.0.1
 * Requires at least: 6.8
 * Requires PHP:      7.4
 * Author:            Papo Ladón
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       makistyle-blocks-v2
 *
 * @package CreateBlock
 */

if (!defined('ABSPATH')) {
	exit(); // Exit if accessed directly.
}

/** Categorias personalizadas */
function makistyle_nueva_categoria_2($categorias, $post)
{
	return array_merge($categorias, [
		[
			'slug' => 'makistyle-v2',
			'title' => 'Makistyle-v2',
			'icon' => 'awards',
		],
	]);
}
add_filter('block_categories', 'makistyle_nueva_categoria_2', 10, 2);

/**
 * Pasar la URL / logo personalizado a JavaScript
 */
// function makistyle_enqueue_logo_script()
// {
// 	// Obtener el ID y URL del logo personalizado
// 	$custom_logo_id = get_theme_mod('custom_logo');
// 	$custom_logo_url = $custom_logo_id ? wp_get_attachment_image_url($custom_logo_id, 'full') : '';

// 	// Localizar el script con la URL del logo
// 	wp_localize_script(
// 		'makistyle-blocks-v2-menu-principal-view-script',
// 		'makistyleData',
// 		array(
// 			'logoUrl' => $custom_logo_url,
// 			'homeUrl' => home_url('/')
// 		)
// 	);
// }
// add_action('wp_enqueue_scripts', 'makistyle_enqueue_logo_script');

function create_block_makistyle_blocks_v2_block_init()
{
	wp_register_block_types_from_metadata_collection(
		__DIR__ . '/build',
		__DIR__ . '/build/blocks-manifest.php',
	);
}
add_action('init', 'create_block_makistyle_blocks_v2_block_init');
