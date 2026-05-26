<?php
/**
 * Renderizado dinámico del bloque woocommerce-boton-comprar
 */

$product = wc_get_product(get_the_ID());

if (!$product) {
	return;
}

// Obtener el precio normal
$precio_regular = $product->get_regular_price();
$precio =
	$precio_regular !== ''
		? (float) $precio_regular
		: (float) $product->get_price();

// Obtener descuentos de la taxonomía descuentos_taxonomia
$promociones = get_the_terms(get_the_ID(), 'descuentos_taxonomia');
$maxDescuento = 0;

if ($promociones && !is_wp_error($promociones)) {
	foreach ($promociones as $promocion) {
		$promocionActiva = get_term_meta(
			$promocion->term_id,
			'makistyle_cmb2_descuentos_taxonomia_descuento_activo',
			true,
		);
		$fechaInicio = (int) get_term_meta(
			$promocion->term_id,
			'makistyle_cmb2_descuentos_taxonomia_fecha_inicio',
			true,
		);
		$fechaFinal = (int) get_term_meta(
			$promocion->term_id,
			'makistyle_cmb2_descuentos_taxonomia_fecha_final',
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

		$porcentajeDescuento = (float) get_term_meta(
			$promocion->term_id,
			'makistyle_cmb2_descuentos_taxonomia_porcentaje_descuento',
			true,
		);

		if ($porcentajeDescuento > $maxDescuento) {
			$maxDescuento = $porcentajeDescuento;
		}
	}
}

// Calcular precio final
$hayDescuento = false;
$precioFinal = $precio;

if ($precio > 0 && $maxDescuento > 0) {
	$hayDescuento = true;
	$descuentoCalculado = ($precio * $maxDescuento) / 100;
	$precioFinal = $precio - $descuentoCalculado;

	if ($precioFinal < 0) {
		$precioFinal = 0;
	}
}

// Función auxiliar para formatear precios sin ceros innecesarios (ej: 10.00 -> 10, 10.50 -> 10,5)
function makistyle_formatear_precio($valor)
{
	if (floor($valor) == $valor) {
		return number_format($valor, 0, ',', '.');
	} else {
		return number_format($valor, 2, ',', '.');
	}
}

$precio_str = makistyle_formatear_precio($precio);
$precioFinal_str = makistyle_formatear_precio($precioFinal);

$htmlBotones = '';

// Lógica de Producto Externo / Afiliado
if ($product->is_type('external')) {
	$enlace_afiliado = $product->get_product_url();
	if (empty($enlace_afiliado)) {
		$enlace_afiliado = get_post_meta(
			$product->get_id(),
			'makistyle_cmb2_woocommerce_enlace_compra',
			true,
		);
	}
	$texto_base_wc = $product->get_button_text();
	if (empty($texto_base_wc)) {
		$texto_base_wc = get_post_meta(
			$product->get_id(),
			'makistyle_cmb2_woocommerce_texto_enlace_compra',
			true,
		);
		if (empty($texto_base_wc)) {
			$texto_base_wc = 'TIENDA EXTERNA';
		}
	}

	$texto_boton = '';

	if ($precioFinal == 0) {
		$texto_boton = 'DESCARGA - ' . $texto_base_wc;
	} else {
		if ($hayDescuento) {
			$texto_boton =
				'<span class="precio-tachado">' .
				$precio_str .
				' &euro;</span> ' .
				$precioFinal_str .
				' &euro; - ' .
				$texto_base_wc;
		} else {
			$texto_boton = $precio_str . ' &euro; - ' . $texto_base_wc;
		}
	}

	if ($enlace_afiliado) {
		$htmlBotones .= sprintf(
			'<a class="btn-precio-tienda" href="%s" target="_blank">%s</a>',
			esc_url($enlace_afiliado),
			$texto_boton,
		);
	}
}
// Lógica de Producto No Externo (Simple, Variable, etc.)
else {
	// 1. Botón Principal (Añadir a carrito)
	$texto_boton_principal = '';
	if ($precioFinal == 0) {
		$texto_boton_principal = '0 &euro; - Añadir a carrito';
	} else {
		if ($hayDescuento) {
			$texto_boton_principal =
				'<span class="precio-tachado">' .
				$precio_str .
				' &euro;</span>' .
				$precioFinal_str .
				' &euro; - Añadir a carrito';
		} else {
			$texto_boton_principal = $precio_str . ' &euro; - Añadir a carrito';
		}
	}

	// Calcular cantidad ya existente en el carrito (sesión actual)
	$qty_in_cart = 0;
	if (function_exists('WC') && WC()->cart) {
		foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
			if ($cart_item['product_id'] == $product->get_id()) {
				$qty_in_cart += $cart_item['quantity'];
			}
		}
	}

	$display_feedback = $qty_in_cart > 0 ? 'inline-block' : 'none';
	$text_feedback = $qty_in_cart > 0 ? "({$qty_in_cart} en carrito)" : '';

	$htmlBotones .= sprintf(
		'<button class="btn-precio-tienda makistyle-add-to-cart-btn" data-product-id="%d" data-qty="%d">
			<span class="btn-text">%s</span>
			<span class="cart-feedback" style="display:%s; font-size: 0.8em; margin-left: 8px; font-weight: normal; opacity: 0.9;">%s</span>
		</button>',
		$product->get_id(),
		$qty_in_cart,
		$texto_boton_principal,
		$display_feedback,
		$text_feedback,
	);

	// 2. Botón Secundario (Alternativo de Tienda Externa)
	$enlace_alternativo = get_post_meta(
		$product->get_id(),
		'makistyle_cmb2_woocommerce_enlace_compra',
		true,
	);
	if (!empty($enlace_alternativo)) {
		$texto_alternativo = get_post_meta(
			$product->get_id(),
			'makistyle_cmb2_woocommerce_texto_enlace_compra',
			true,
		);
		if (empty($texto_alternativo)) {
			$texto_alternativo = 'TIENDA EXTERNA';
		}

		$htmlBotones .= sprintf(
			'<a class="btn-precio-tienda btn-alternativo" href="%s" target="_blank">%s</a>',
			esc_url($enlace_alternativo),
			esc_html($texto_alternativo),
		);
	}
}
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<div class="botones-compra-wrapper">
		<?php echo $htmlBotones; ?>
	</div>
</div>