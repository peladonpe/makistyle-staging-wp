<?php
/**
 * Renderizado dinámico del bloque woocommerce-descripcion
 */

$post_id = get_the_ID();
$product = wc_get_product($post_id);
$description_content = '';
if ($product) {
	$description_content = $product->get_description();
	$description_content = apply_filters('the_content', $description_content);
}

// Wrapper del bloque (se asegura de imprimir las clases, como fontSize si se especifican)
$wrapper_attributes = get_block_wrapper_attributes();

?>
<div <?php echo $wrapper_attributes; ?>>
	<div class="wp-block-makistyle-blocks-v2-woocommerce-descripcion-content">
		<?php echo wp_kses_post($description_content); ?>
	</div>
</div>
