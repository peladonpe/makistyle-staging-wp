<?php
// Obtener el precio del post actual
$precio = get_post_meta(get_the_ID(), 'makistyle_cmb2_tienda_precio', true);

// Si el precio es 0 (post gratuito), evitamos obtener y mostrar promociones
if (is_numeric($precio) && floatval($precio) == 0) {
    $promociones = false;
} else {
    // Obtener los términos de la taxonomía promociones_taxonomia asociados al post actual
    $promociones = get_the_terms(get_the_ID(), 'promociones_taxonomia');
}

// Encontrar las promociones con mayor descuento de cada grupo (cupones y descuentos)
$promocionCuponMaxima = null;
$maxDescuentoCupones = 0;
$promocionDescuentoMaxima = null;
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

        $porcentajeDescuento = get_term_meta($promocion->term_id, 'makistyle_cmb2_promociones_taxonomia_porcentaje_descuento', true);

        if ($porcentajeDescuento && $promocion->parent != 0) {
            $termPadre = get_term($promocion->parent, 'promociones_taxonomia');

            if ($termPadre && !is_wp_error($termPadre)) {
                if ($termPadre->slug === 'cupones') {
                    if ($porcentajeDescuento > $maxDescuentoCupones) {
                        $maxDescuentoCupones = $porcentajeDescuento;
                        $promocionCuponMaxima = $promocion;
                    }
                } elseif ($termPadre->slug === 'descuentos') {
                    if ($porcentajeDescuento > $maxDescuentoDescuentos) {
                        $maxDescuentoDescuentos = $porcentajeDescuento;
                        $promocionDescuentoMaxima = $promocion;
                    }
                }
            }
        }
    }
}

// Crear array con las promociones a mostrar
$promocionesAMostrar = array_filter([$promocionCuponMaxima, $promocionDescuentoMaxima]);

?>
<div <?php echo get_block_wrapper_attributes() ?>>
    <?php if (!empty($promocionesAMostrar)) : ?>
        <?php foreach ($promocionesAMostrar as $promocion) : ?>
            <?php
            $nombrePromocion = get_term_meta($promocion->term_id, 'makistyle_cmb2_promociones_taxonomia_nombre_promocion', true);
            $porcentajeDescuento = get_term_meta($promocion->term_id, 'makistyle_cmb2_promociones_taxonomia_porcentaje_descuento', true);
            $codigoDescuento = get_term_meta($promocion->term_id, 'makistyle_cmb2_promociones_taxonomia_codigo_descuento', true);
            ?>
            <p>
                Promoción: <?php echo esc_html($nombrePromocion); ?>
                <?php if ($porcentajeDescuento) : ?>
                    <?php echo ' ( -' . esc_html($porcentajeDescuento) . '% )'; ?>
                <?php endif; ?>
                <?php if ($codigoDescuento) : ?>
                    . CÓDIGO:
                    <span
                        role="button"
                        tabindex="0"
                        class="codigo-descuento-copy"
                        data-codigo="<?php echo esc_attr($codigoDescuento); ?>"
                        data-promocion-id="<?php echo esc_attr($promocion->term_id); ?>">
                        <?php echo esc_html($codigoDescuento); ?>
                    </span>
                <?php endif; ?>
            </p>
        <?php endforeach; ?>
    <?php else : ?>
        <p></p>
    <?php endif; ?>
</div>

<script>
    (function() {
        const codigoElements = document.querySelectorAll('.codigo-descuento-copy');

        codigoElements.forEach(function(element) {
            // Crear icono SVG de copiar
            const tooltipInicial = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            tooltipInicial.setAttribute('viewBox', '0 0 24 24');
            tooltipInicial.setAttribute('fill', 'none');
            tooltipInicial.setAttribute('stroke', 'currentColor');
            tooltipInicial.setAttribute('stroke-width', '2');
            tooltipInicial.setAttribute('stroke-linecap', 'round');
            tooltipInicial.setAttribute('stroke-linejoin', 'round');
            tooltipInicial.setAttribute('aria-label', 'Haz click para copiar');
            tooltipInicial.setAttribute('class', 'tooltip-inicial codigo-copy-icon');
            const svgRect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
            svgRect.setAttribute('x', '9');
            svgRect.setAttribute('y', '9');
            svgRect.setAttribute('width', '13');
            svgRect.setAttribute('height', '13');
            svgRect.setAttribute('rx', '2');
            const svgPath = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            svgPath.setAttribute('d', 'M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1');
            tooltipInicial.appendChild(svgRect);
            tooltipInicial.appendChild(svgPath);
            element.appendChild(tooltipInicial);

            const handleCopyCode = function() {
                const codigo = element.getAttribute('data-codigo');
                const promocionId = element.getAttribute('data-promocion-id');

                // Eliminar tooltip inicial si existe
                const tooltipExistente = element.querySelector('.tooltip-inicial');
                if (tooltipExistente) {
                    element.removeChild(tooltipExistente);
                }

                navigator.clipboard.writeText(codigo).then(function() {
                    // Crear tooltip de confirmación
                    const tooltip = document.createElement('span');
                    tooltip.textContent = 'Código copiado';
                    tooltip.className = 'codigo-tooltip';

                    element.appendChild(tooltip);

                    // Eliminar tooltip después de 2 segundos
                    setTimeout(function() {
                        if (element.contains(tooltip)) {
                            element.removeChild(tooltip);
                        }
                    }, 2000);
                });
            };

            // Click event
            element.addEventListener('click', handleCopyCode);

            // Keyboard event
            element.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    handleCopyCode();
                }
            });
        });
    })();
</script>