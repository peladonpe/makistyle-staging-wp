<?php
$autores = get_post_meta(get_the_ID(), 'makistyle_cmb2_tienda_autores', true);
$atributos = get_block_wrapper_attributes();
?>
<div <?php echo $atributos ?>><?php echo $autores; ?></div>