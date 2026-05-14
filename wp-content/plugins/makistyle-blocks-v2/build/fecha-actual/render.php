<?php

/**
 * Renderizado del lado del servidor para el bloque "Fecha actual".
 *
 * @package makistyle-blocks-v2
 */

?>
<p <?php echo get_block_wrapper_attributes(); ?>>
    <?php echo esc_html(date_i18n('Y')); ?>
</p>