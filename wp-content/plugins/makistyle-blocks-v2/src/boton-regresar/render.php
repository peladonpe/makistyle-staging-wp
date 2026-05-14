<?php
$t = get_query_var('t');

// Detectar clases del body para entradas individuales de blog o tienda
$body_classes = get_body_class();

if ($t === 'tienda' && in_array('search-results', $body_classes)) {
    $page  = get_page_by_path('tienda');
    $url   = $page ? get_permalink($page->ID) : home_url('/tienda/');
    $texto = __('Ir a la Tienda', 'makistyle');
    $inner = sprintf(
        '<a class="boton-regresar__enlace" href="%s"><span class="boton-regresar__icono" aria-hidden="true">&#8592;</span><span class="boton-regresar__texto">%s</span></a>',
        esc_url($url),
        esc_html($texto)
    );
} elseif ($t === 'blog' && in_array('search-results', $body_classes)) {
    $page  = get_page_by_path('blog');
    $url   = $page ? get_permalink($page->ID) : home_url('/blog/');
    $texto = __('Ir al Blog', 'makistyle');
    $inner = sprintf(
        '<a class="boton-regresar__enlace" href="%s"><span class="boton-regresar__icono" aria-hidden="true">&#8592;</span><span class="boton-regresar__texto">%s</span></a>',
        esc_url($url),
        esc_html($texto)
    );
} else {
    $inner = sprintf(
        '<button type="button" class="boton-regresar__enlace boton-regresar__historial"><span class="boton-regresar__icono" aria-hidden="true">&#8592;</span><span class="boton-regresar__texto">%s</span></button>',
        esc_html__('Regresar', 'makistyle')
    );
}
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
    <?php echo $inner; ?>
</div>
