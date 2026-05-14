<?php

// ── Utilidades de imagen ─────────────────────────────────────────────────────

function mkv2_post_maybe_webp($url)
{
	if (!$url) {
		return $url;
	}

	// Si ya es webp, no hay nada que hacer
	$ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
	if ($ext === 'webp') {
		return $url;
	}

	// Solo verificamos en disco para los formatos que se optimizan
	if (!in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
		return $url;
	}

	// $upload_dir = wp_upload_dir();
	// $file_path  = str_replace(
	// 	$upload_dir['baseurl'],
	// 	$upload_dir['basedir'],
	// 	$url,
	// );
	// $webp_path = preg_replace('/\.[^.]+$/', '.webp', $file_path);
	// if (file_exists($webp_path)) {
	// 	return preg_replace('/\.[^.]+$/', '.webp', $url);
	// }
	// return $url;
	return preg_replace('/\.[^.]+$/', '.webp', $url);
}

function mkv2_post_get_image_sizes($idPost)
{
	return array_map('mkv2_post_maybe_webp', [
		get_the_post_thumbnail_url($idPost, 'thumbnail'),
		get_the_post_thumbnail_url($idPost, 'medium'),
		get_the_post_thumbnail_url($idPost, 'medium_large'),
		get_the_post_thumbnail_url($idPost, 'large'),
	]);
}

/**
 * Genera el HTML de la card de imagen: <a><figure><img></figure></a>.
 * Usado por los bloques de tienda y blog en su rama "sin YouTube".
 *
 * @param int    $postId      ID del post (para obtener imágenes y alt)
 * @param string $permalink   URL de destino del enlace
 * @param string $linkClass   Clase CSS del <a>
 * @param string $figureClass Clase CSS del <figure> (vacío = sin clase)
 * @param string $imgClass    Clase CSS del <img>   (vacío = sin clase)
 * @param int    $height      Altura de la imagen
 * @return string HTML generado
 */
function mkv2_post_get_card_figure(
	$postId,
	$permalink,
	$linkClass,
	$figureClass = '',
	$imgClass = '',
	$height = 400,
) {
	$imageSizes = mkv2_post_get_image_sizes($postId);
	$idImagen = get_post_thumbnail_id($postId);
	$altImagen = esc_attr(
		get_post_meta($idImagen, '_wp_attachment_image_alt', true),
	);
	$figureAttr = $figureClass ? ' class="' . esc_attr($figureClass) . '"' : '';
	$imgAttr = $imgClass ? ' class="' . esc_attr($imgClass) . '"' : '';

	return sprintf(
		'<a href="%1$s" class="%2$s">
		<figure%3$s>
			<img%8$s fetchpriority="high" decoding="async" width="400" height="%7$s"
				src="%5$s" alt="%6$s"
				srcset="%5$s 400w, %4$s 150w, %9$s 600w, %10$s 1020w"
				sizes="(max-width:400px) 100vw, 400px">
		</figure>
		</a>',
		esc_url($permalink), // %1$s  href del enlace
		esc_attr($linkClass), // %2$s  clase del enlace
		$figureAttr, // %3$s  atributo class del figure (o vacío)
		$imageSizes[0], // %4$s  thumbnail  (150w)
		$imageSizes[1], // %5$s  medium     (400w) — src principal
		$altImagen, // %6$s  alt
		$height, // %7$s  height
		$imgAttr, // %8$s  atributo class del img (o vacío)
		$imageSizes[2], // %9$s  medium_large (600w)
		$imageSizes[3], // %10$s large        (1020w)
	);
}

/**
 * Genera el HTML del figure fallback de YouTube para las cards de blog.
 * Se muestra oculto bajo el player cuando el post tiene video de YouTube.
 *
 * @param int $postId ID del post
 * @param int $height Altura de la imagen
 * @return string HTML generado
 */
function mkv2_post_get_card_figure_fallback($postId, $height = 400)
{
	$imageSizes = mkv2_post_get_image_sizes($postId);
	$idImagen = get_post_thumbnail_id($postId);
	$altImagen = esc_attr(
		get_post_meta($idImagen, '_wp_attachment_image_alt', true),
	);

	return sprintf(
		'<div class="blog-card__fallback">
			<figure>
				<img loading="lazy" width="400" height="%6$s" src="%2$s" alt="%5$s"
					srcset="%2$s 400w, %1$s 150w, %3$s 600w, %4$s 1020w"
					sizes="(max-width:400px) 100vw, 400px">
			</figure>
		</div>',
		$imageSizes[0], // %1$s  thumbnail  (150w)
		$imageSizes[1], // %2$s  medium     (400w) — src principal
		$imageSizes[2], // %3$s  medium_large (600w)
		$imageSizes[3], // %4$s  large        (1020w)
		$altImagen, // %5$s  alt
		$height, // %6$s  height
	);
}
