<?php
/**
 * Renderizado dinámico del bloque woocommerce-promociones
 */

$product = wc_get_product(get_the_ID());

// Si el producto es gratuito, no se muestran promociones
if ($product && floatval($product->get_price()) == 0) {
	$promociones = false;
} else {
	// Obtener los términos de la taxonomía descuentos_taxonomia asociados al producto actual
	$promociones = get_the_terms(get_the_ID(), 'descuentos_taxonomia');
}

$promocionDescuentoMaxima = null;
$maxDescuentoDescuentos = 0;

if ($promociones && !is_wp_error($promociones)) {
	foreach ($promociones as $promocion) {
		// Verificar que el descuento esté activo y dentro del rango de fechas
		$promocionActiva = get_term_meta($promocion->term_id, 'makistyle_cmb2_descuentos_taxonomia_descuento_activo', true);
		$fechaInicio     = (int) get_term_meta($promocion->term_id, 'makistyle_cmb2_descuentos_taxonomia_fecha_inicio', true);
		$fechaFinal      = (int) get_term_meta($promocion->term_id, 'makistyle_cmb2_descuentos_taxonomia_fecha_final', true);
		$ahora           = time();
		$dentroDeRango   = $fechaInicio && $fechaFinal && $ahora >= $fechaInicio && $ahora <= $fechaFinal;

		if ($promocionActiva !== 'on' || !$dentroDeRango) {
			continue;
		}

		$porcentajeDescuento = (int) get_term_meta($promocion->term_id, 'makistyle_cmb2_descuentos_taxonomia_porcentaje_descuento', true);

		if ($porcentajeDescuento > $maxDescuentoDescuentos) {
			$maxDescuentoDescuentos = $porcentajeDescuento;
			$promocionDescuentoMaxima = $promocion;
		}
	}
}

?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<?php if ($promocionDescuentoMaxima) : ?>
		<?php
		$nombrePromocion = get_term_meta($promocionDescuentoMaxima->term_id, 'makistyle_cmb2_descuentos_taxonomia_nombre_descuento', true);
		$porcentajeDescuento = get_term_meta($promocionDescuentoMaxima->term_id, 'makistyle_cmb2_descuentos_taxonomia_porcentaje_descuento', true);
		?>
		<p>
			Promoción: <?php echo esc_html($nombrePromocion); ?>
			<?php if ($porcentajeDescuento) : ?>
				<?php echo ' ( -' . esc_html($porcentajeDescuento) . '% )'; ?>
			<?php endif; ?>
		</p>
	<?php else : ?>
		<p></p>
	<?php endif; ?>
</div>