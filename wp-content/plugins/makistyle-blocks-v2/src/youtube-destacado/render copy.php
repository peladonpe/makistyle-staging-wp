<?php
$postId = get_the_ID();
$idVideo = get_post_meta(
	$postId,
	'makistyle_cmb2_todos_pt_id_youtube_destacado',
	true,
);

$htmlYoutubeFinal = null;
$playerId = '';
$spinnerId = '';
$esAutoplay = '';
$height = 600;

$imageSizes = mkv2_post_get_image_sizes($postId);
$idImagen = get_post_thumbnail_id($postId);
$altImagen = esc_attr(
	get_post_meta($idImagen, '_wp_attachment_image_alt', true),
);

$fallback = sprintf(
	'<figure class="mkv2-yt-fallback">
		<img width="400" height="%6$s"
			src="%2$s" alt="%5$s"
			srcset="%2$s 400w, %1$s 150w, %3$s 600w, %4$s 1020w"
			sizes="(max-width:400px) 100vw, 400px">
	</figure>',
	$imageSizes[0], // %1$s thumbnail
	$imageSizes[1], // %2$s medium
	$imageSizes[2], // %3$s medium_large
	$imageSizes[3], // %4$s large
	$altImagen, // %5$s
	$height, // %6$s
);

if ($idVideo) {
	static $counter = 0;
	$counter++;
	$playerId = 'youtubeDestacado-' . $counter;
	$spinnerId = 'spinner-youtube-' . $counter;
	$esAutoplay = esc_attr($attributes['autoplay']);
	$htmlYoutubeFinal = sprintf(
		'<div id="%4$s" data-youtube-destacado data-videoid="%1$s" data-autoplay="%2$s" data-height="%3$s" data-spinner-id="%5$s"></div>',
		esc_attr($idVideo),
		$esAutoplay,
		$height,
		$playerId,
		$spinnerId,
	);
}
?>

<div <?php echo get_block_wrapper_attributes(); ?>>
	<?php if ($htmlYoutubeFinal): ?>
		<div class="mkv2-yt-wrapper">
			<div id="<?php echo esc_attr($spinnerId); ?>" class="mk-spinner-div">
				<div class="mk-spinner" role="status">
					<span class="mk-visually-hidden">Loading…</span>
				</div>
			</div>
			<?php echo $htmlYoutubeFinal; ?>
			<?php if ($esAutoplay === '1') { ?>
				<button class="mkv2-unmute-btn" type="button">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
						<line x1="23" y1="9" x2="17" y2="15"></line>
						<line x1="17" y1="9" x2="23" y2="15"></line>
					</svg>
					<?php esc_html_e('Activar audio', 'makistyle'); ?>
				</button>
			<?php } ?>
		</div>
	<?php else: ?>
		<?php echo $fallback; ?>
	<?php endif; ?>
</div>