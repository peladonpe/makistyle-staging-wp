<?php
$fechaLanzamiento2 = get_post_meta(get_the_ID(), 'makistyle_cmb2_tienda_fecha_lanzamiento2', true);
$fechaFormateada = null;
if ($fechaLanzamiento2) {
	$fechaFormateada = date('d-m-Y', $fechaLanzamiento2);
}
?>
<p <?php echo get_block_wrapper_attributes(); ?>>
	<?php echo $fechaFormateada; ?>
</p>