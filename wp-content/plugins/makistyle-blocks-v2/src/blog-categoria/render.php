<?php
$categorias = get_the_terms(get_the_ID(), 'category');
$nombreCategoriaSingular = '';
if ($categorias && ! is_wp_error($categorias)) {
    $categoriaId = $categorias[0]->term_id;
    if ($categorias[0]->slug === 'sin-categoria') {
        $nombreCategoriaSingular = '';
    } else {
        $nombreCategoriaSingular = get_term_meta($categoriaId, 'makistyle_cmb2_category_nombre_singular', true);
        if (!$nombreCategoriaSingular) {
            $nombreCategoriaSingular = $categorias[0]->name;
        }
    }
}
$claseOculto = $nombreCategoriaSingular ? '' : ' categoria__badge--hidden';
$atributos = get_block_wrapper_attributes(array('class' => 'categoria__badge' . $claseOculto));
?>
<div <?php echo $atributos; ?>><?php echo esc_html($nombreCategoriaSingular); ?></div>