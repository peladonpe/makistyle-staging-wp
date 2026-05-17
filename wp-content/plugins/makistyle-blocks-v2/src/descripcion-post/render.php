<?php
/**
 * Renderizado dinámico del bloque descripcion-post
 * 
 * @param array $attributes Atributos del bloque.
 * @param string $content Contenido del bloque.
 * @param WP_Block $block Objeto del bloque.
 */

$post_id = get_the_ID();
$description_content = get_post_meta($post_id, 'makistyle_cmb2_descripcion_post', true);

// Wrapper del bloque (se asegura de imprimir las clases, como fontSize si se especifican)
$wrapper_attributes = get_block_wrapper_attributes();

?>
<div <?php echo $wrapper_attributes; ?>>
	<div class="wp-block-makistyle-blocks-v2-descripcion-post-content">
		<?php echo wp_kses_post($description_content); ?>
	</div>
</div>
