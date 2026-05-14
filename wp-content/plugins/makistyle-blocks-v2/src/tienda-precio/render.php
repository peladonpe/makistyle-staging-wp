<?php

$precio = get_post_meta(get_the_ID(), 'makistyle_cmb2_tienda_precio', true);
$enlaceCompra = get_post_meta(get_the_ID(), 'makistyle_cmb2_tienda_enlace_compra', true);

// Obtener las promociones del post
$promociones = get_the_terms(get_the_ID(), 'promociones_taxonomia');
$maxDescuentoCupones = 0;
$maxDescuentoDescuentos = 0;

if ($promociones && !is_wp_error($promociones)) {
	foreach ($promociones as $promocion) {
		// Verificar que la promoción esté activa y dentro del rango de fechas
		$promocionActiva = get_term_meta($promocion->term_id, 'makistyle_cmb2_promociones_taxonomia_promocion_activa', true);
		$fechaInicio     = (int) get_term_meta($promocion->term_id, 'makistyle_cmb2_promociones_taxonomia_fecha_inicio', true);
		$fechaFinal      = (int) get_term_meta($promocion->term_id, 'makistyle_cmb2_promociones_taxonomia_fecha_final', true);
		$ahora           = time();
		$dentroDeRango   = $fechaInicio && $fechaFinal && $ahora >= $fechaInicio && $ahora <= $fechaFinal;

		if ($promocionActiva !== 'on' || !$dentroDeRango) {
			continue;
		}

		// Obtener el porcentaje de descuento
		$porcentajeDescuento = get_term_meta($promocion->term_id, 'makistyle_cmb2_promociones_taxonomia_porcentaje_descuento', true);

		if ($porcentajeDescuento && $promocion->parent != 0) {
			// Obtener el término padre
			$termPadre = get_term($promocion->parent, 'promociones_taxonomia');

			if ($termPadre && !is_wp_error($termPadre)) {
				// Verificar si es hijo de cupones
				if ($termPadre->slug === 'cupones') {
					if ($porcentajeDescuento > $maxDescuentoCupones) {
						$maxDescuentoCupones = $porcentajeDescuento;
					}
				}
				// Verificar si es hijo de descuentos
				elseif ($termPadre->slug === 'descuentos') {
					if ($porcentajeDescuento > $maxDescuentoDescuentos) {
						$maxDescuentoDescuentos = $porcentajeDescuento;
					}
				}
			}
		}
	}
}

// Calcular el precio con descuentos
$precioFinal = $precio;
$hayDescuentos = false;

if ($precio > 0 && ($maxDescuentoCupones > 0 || $maxDescuentoDescuentos > 0)) {
	$hayDescuentos = true;
	$descuentoCupones = ($precio * $maxDescuentoCupones) / 100;
	$descuentoDescuentos = ($precio * $maxDescuentoDescuentos) / 100;
	$precioFinal = $precio - $descuentoCupones - $descuentoDescuentos;

	// Asegurar que el precio no sea negativo
	if ($precioFinal < 0) {
		$precioFinal = 0;
	}
}

$htmlBotonFinal = null;
if (($precio || $precio == 0) && $enlaceCompra) {
	if ($precio == 0) {
		$textoBoton = 'DESCARGA GRATUITA';
	} else {
		if ($hayDescuentos) {
			$textoBoton = '<span class="precio-tachado">' . $precio . ' €</span>' . number_format($precioFinal, 2, ',', '.') . ' € - COMPRAR';
		} else {
			$textoBoton = $precio . ' € - COMPRAR';
		}
	}
	$htmlBoton =
		/*html*/ '
		<div class="btn-precio-tienda-wrap">
			<a class="btn-precio-tienda" href="%2$s" target="_blank">%1$s</a>
		</div>
    ';
	$htmlBotonFinal = sprintf(
		$htmlBoton,
		$textoBoton,
		$enlaceCompra
	);
}
?>
<div <?php echo get_block_wrapper_attributes(); ?>><?php echo $htmlBotonFinal; ?></div>