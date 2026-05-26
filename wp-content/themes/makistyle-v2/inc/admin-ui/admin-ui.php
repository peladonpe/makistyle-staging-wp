
<?php
// Imports parciales UI
require_once dirname(__FILE__) . '/tienda-ui.php';
require_once dirname(__FILE__) . '/woocommerce-ui.php';

// function posts_link_attributes()
// {
//      return 'class="page-link"';
// }
// add_filter('next_posts_link_attributes', 'posts_link_attributes');
// add_filter('previous_posts_link_attributes', 'posts_link_attributes');

/** Agrega un mensaje personalizado a la página en el admin */

// Añadir columna personalizada para información de posts
function makistyle_add_post_info_column($columns)
{
	$new_columns = [];
	foreach ($columns as $key => $value) {
		$new_columns[$key] = $value;
		// Insertar después de la columna de título
		if ($key === 'title') {
			$new_columns['post_info'] = __('Información', 'makistyle');
		}
	}
	return $new_columns;
}
add_filter('manage_post_posts_columns', 'makistyle_add_post_info_column');
add_filter('manage_page_posts_columns', 'makistyle_add_post_info_column');

function makistyle_show_post_info_column($column_name, $post_id)
{
	if ($column_name === 'post_info') {
		$post = get_post($post_id);
		$current_slug = $post->post_name;

		$mensajeSlug =
			'<p style="margin: 5px 0; color: #3180FC;"><strong>SLUG:</strong> <span style="color: #3180FC;">';
		if (is_home()) {
			$mensajeSlug .= 'blog';
		} else {
			$mensajeSlug .= $current_slug;
		}
		$mensajeSlug .= '</span></p>';

		$post_status = get_post_status($post_id);
		if ($post_status === 'draft') {
			echo $mensajeSlug;
			return;
		}

		$msgImgDestacada = '';
		$msgSeo          = '';

		if (get_post_type($post_id) === 'post') {

			$msgImgDestacada =
				'<p style="margin: 5px 0; color: #3180FC;"><strong>IMG DESTACADA:</strong> ';
			if (has_post_thumbnail($post_id)) {
				$thumb_id   = get_post_thumbnail_id($post_id);
				$thumb_path = get_attached_file($thumb_id);
				if ($thumb_path && file_exists($thumb_path)) {
					$size_kb  = round(filesize($thumb_path) / 1024);
					$img_type = ' - ' . strtoupper(pathinfo($thumb_path, PATHINFO_EXTENSION));
					$img_info = $size_kb . ' KB ' . $img_type;
					if ($size_kb < 200) {
						$msgImgDestacada .= '<span style="color: #00a32a;">' . $img_info;
					} else {
						$msgImgDestacada .= '<span style="color: #d63638; font-size:1.3em;">' . $img_info;
					}
				} else {
					$msgImgDestacada .= '<span style="color: #00a32a;">SI';
				}
			} else {
				$msgImgDestacada .= '<span style="color: #d63638; font-size:1.3em;">NO';
			}
			$msgImgDestacada .= '</span></p>';

			$meta_key_yt = 'makistyle_cmb2_todos_pt_id_youtube_destacado';
			$meta_key_local = 'makistyle_cmb2_todos_pt_url_video_local';

			$linkVideo = get_post_meta($post_id, $meta_key_yt, true);
			if (empty($linkVideo)) {
				$linkVideo = get_post_meta($post_id, $meta_key_local, true);
			}
			$msgLinkVideo =
				'<p style="margin: 5px 0; color: #3180FC;"><strong>LINK VIDEO:</strong> ';
			$msgLinkVideo .= !empty($linkVideo)
				? '<span style="color: #00a32a;">SI'
				: '<span style="color: #d63638; font-size:1.3em;">NO';
			$msgLinkVideo .= '</span></p>';

			$seoDescription = get_post_meta(
				$post_id,
				'makistyle_cmb2_seo_description',
				true,
			);
			$msgSeo =
				'<p style="margin: 5px 0; color: #3180FC;"><strong>SEO:</strong> ';
			$msgSeo .= !empty($seoDescription)
				? '<span style="color: #00a32a;">SI'
				: '<span style="color: #d63638; font-size:1.3em;">NO';
			$msgSeo .= '</span></p>';
		}

		// SEO para páginas (page)
		if (get_post_type($post_id) === 'page') {
			$seoDescription = get_post_meta(
				$post_id,
				'makistyle_cmb2_seo_description',
				true,
			);
			$msgSeo  = '<p style="margin: 5px 0; color: #3180FC;"><strong>SEO:</strong> ';
			$msgSeo .= !empty($seoDescription)
				? '<span style="color: #00a32a;">SI'
				: '<span style="color: #d63638; font-size:1.3em;">NO';
			$msgSeo .= '</span></p>';
		}

		echo $mensajeSlug .
			$msgImgDestacada .
			$msgLinkVideo .
			$msgSeo;
	}
}
add_action(
	'manage_post_posts_custom_column',
	'makistyle_show_post_info_column',
	10,
	2,
);
add_action(
	'manage_page_posts_custom_column',
	'makistyle_show_post_info_column',
	10,
	2,
);
