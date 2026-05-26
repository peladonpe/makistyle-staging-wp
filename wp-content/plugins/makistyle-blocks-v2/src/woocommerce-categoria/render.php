<?php
$tipoRecursoPost = get_the_terms(get_the_ID(), 'product_cat');
$nombreTipoRecursoSingular = '';
if ($tipoRecursoPost && ! is_wp_error($tipoRecursoPost)) {
    $tipoRecursoId = $tipoRecursoPost[0]->term_id;
    $nombreTipoRecursoSingular = get_term_meta($tipoRecursoId, 'makistyle_cmb2_woocommerce_categorias_nombre_singular', true);
    if (!$nombreTipoRecursoSingular) {
        $nombreTipoRecursoSingular = $tipoRecursoPost[0]->name;
    }
}
$claseOculto = $nombreTipoRecursoSingular ? '' : ' categoria__badge--hidden';
$atributos = get_block_wrapper_attributes(array('class' => 'categoria__badge' . $claseOculto));
?>
<div <?php echo $atributos; ?>><?php echo esc_html($nombreTipoRecursoSingular); ?></div>