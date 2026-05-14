<?php


/**
 * Register the custom meta fields
 */
function meta_fields_register_meta()
{
     function sanitize_precio_meta($value)
     {
          if ($value === null || $value === '' || $value === false) {
               return null;
          }
          $float_value = floatval($value);
          return $float_value;
     }

     function registrar_campos_personalizados_posts($metafield, $type = 'string', $sanitize_callback = 'sanitize_text_field', $post_type = '')
     {
          register_post_meta($post_type, $metafield, array(
               'single' => true,
               'type' => $type,
               'show_in_rest' => true,
               'sanitize_callback' => $sanitize_callback,
               'auth_callback' => function () {
                    return current_user_can('edit_posts');
               }
          ));
     }
     function registrar_campos_personalizados_terms($metafield)
     {
          register_term_meta('', $metafield, array(
               'single' => true,
               'type' => 'string',
               'show_in_rest' => true,
          ));
     }

     // Registrar meta fields con sus tipos específicos
     registrar_campos_personalizados_posts('makistyle_cmb2_tienda_fecha_lanzamiento2', 'number', 'absint', 'tienda_pt');
     registrar_campos_personalizados_posts('makistyle_cmb2_tienda_precio', 'number', 'sanitize_precio_meta', 'tienda_pt');
     registrar_campos_personalizados_posts('makistyle_cmb2_tienda_enlace_compra', 'string', 'esc_url_raw', 'tienda_pt');
     registrar_campos_personalizados_posts('makistyle_cmb2_tienda_autores', 'string', 'sanitize_text_field', 'tienda_pt');
     registrar_campos_personalizados_posts('makistyle_cmb2_todos_pt_id_youtube_destacado', 'string', 'sanitize_text_field', 'tienda_pt');
     registrar_campos_personalizados_posts('makistyle_cmb2_todos_pt_id_youtube_destacado', 'string', 'sanitize_text_field', 'post');
     registrar_campos_personalizados_posts('makistyle_cmb2_todos_pt_url_video_local', 'string', 'esc_url_raw', 'tienda_pt');
     registrar_campos_personalizados_posts('makistyle_cmb2_todos_pt_url_video_local', 'string', 'esc_url_raw', 'post');

     $metafields_terms = [
          'makistyle_cmb2_tipo_recurso_taxonomia_nombre_singular',
          'makistyle_cmb2_category_nombre_singular',
          'makistyle_cmb2_promociones_taxonomia_nombre_promocion',
          'makistyle_cmb2_promociones_taxonomia_codigo_descuento',
          'makistyle_cmb2_promociones_taxonomia_porcentaje_descuento',
          'makistyle_cmb2_promociones_taxonomia_fecha_inicio',
          'makistyle_cmb2_promociones_taxonomia_fecha_final',
          'makistyle_cmb2_promociones_taxonomia_promocion_activa'
     ];
     foreach ($metafields_terms as $metafield) {
          registrar_campos_personalizados_terms($metafield);
     }
}
add_action('init', 'meta_fields_register_meta');