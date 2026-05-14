<?php

function new_get_page_by_title($page_title, $output = OBJECT, $post_type = 'page')
{
    $args  = array(
        'title'                  => $page_title,
        'post_type'              => $post_type,
        'post_status'            => get_post_stati(),
        'posts_per_page'         => 1,
        'update_post_term_cache' => false,
        'update_post_meta_cache' => false,
        'no_found_rows'          => true,
        'orderby'                => 'post_date ID',
        'order'                  => 'ASC',
    );
    $query = new WP_Query($args);
    $pages = $query->posts;

    if (empty($pages)) {
        return null;
    }
    wp_reset_postdata();
    return get_post($pages[0], $output);
}

function new_get_author_id_by_post($postId)
{
    global $wpdb;
    $author_id = (int) $wpdb->get_var($wpdb->prepare("SELECT post_author FROM {$wpdb->posts} WHERE ID = %d ", $postId));
    // $author =  new WP_User($author_id);
    return $author_id;
}

function makistyle_mostrar_temas($cantidad = -1, $paginacion = false)
{


    $args = array(
        'post_type' => 'musica_pt',
        'posts_per_page' => $cantidad,
        'paged' => get_query_var('paged', 1),

        'meta_query' => array(
            array(
                'key' => 'makistyle_fecha_lanzamiento',
                'value' => current_time('timestamp'),
                'compare' => '>'
            )
        ),
        'meta_type' => 'text_date_timestamp',
        'orderby'   => 'meta_value_num',
        'order'     => 'DESC'
    );
    $musica = new WP_Query($args);
    $body = '';


    while ($musica->have_posts()): $musica->the_post();
        // the_content();
        $fechaLanzamiento = get_post_meta(get_the_ID(), 'makistyle_fecha_lanzamiento', true);
        $blocks = parse_blocks(get_the_content());
        // var_dump($blocks);
        $blocksChecked = array(
            'h3',
            'autor'
        );
        foreach ($blocksChecked as $blockChecked) {
            $$blockChecked = '';
        }
        foreach ($blocks as $block) {
            // var_dump($block);

            $results = getTextInnerBlocks($block, $blocksChecked);
            foreach ($results as $key => $value) {

                $$key = $value;
            }
            $imageSizes = makistyle_get_image_sizes(get_the_ID());
        }
        $htmlString =
            /*html*/ '
        <div class="wp-block-columns has-background is-layout-flex wp-block-columns-is-layout-flex" style="background:linear-gradient(90deg,rgb(136,77,72) 0%%,rgb(86,66,62) 100%%)">
            <div class="wp-block-column is-layout-flow wp-block-column-is-layout-flow" style="flex-basis:40%%">
            <figure class="wp-block-image size-medium text-center"><img fetchpriority="high" decoding="async" width="400" height="400" src="%2$s" alt="" class="wp-image-210" srcset="%2$s 400w, %1$s.png 150w, %3$s 600w, %4$s 1080w" sizes="(max-width: 400px) 100vw, 400px" /></figure>
             </div>
            <div class="wp-block-column is-vertically-aligned-top is-layout-flow wp-block-column-is-layout-flow" style="flex-basis:60%%">
                <h3 class="wp-block-heading has-text-color has-background has-link-color has-medium-font-size " style="color:#fdfdfd;background-color:#111111">%5$s</h3>
                <p class="has-text-color has-background has-link-color" style="color:#fdfdfd;background-color:#111111">%6$s</p>
                <p class="has-text-color has-link-color" style="color:#fdfdfd">%7$s</p>
                <div class="wp-block-buttons is-content-justification-center is-layout-flex wp-block-buttons-is-layout-flex">
                    <div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline btn-outline-primario is-style-outline--2"><a href="%8$s" class=" wp-block-button__link has-text-color has-background has-link-color has-small-font-size has-custom-font-size wp-element-button" style="border-radius:8px;color:#01fff7;background-color:#00000000">Escuchar</a></div>
                </div>
            </div>
        </div>
        ';
        $body .= sprintf(
            /*html*/
            $htmlString,
            $imageSizes[0],
            $imageSizes[1],
            $imageSizes[2],
            $imageSizes[3],
            esc_html($h3),
            esc_html($autor),
            esc_html($fechaLanzamiento),
            esc_url(get_the_permalink()),

        );

?>

    <?php
    endwhile;
    if ($paginacion) {
        $total_pages = $musica->max_num_pages;
        if ($total_pages > 1) {

            $current_page = max(1, get_query_var('paged'));
            $body .= '<div class="paginacion">';
            $body .=  paginate_links(array(
                'base' => get_pagenum_link(1) . '%_%',
                'format' => '/page/%#%',
                // 'format' => '?paged=%#%',
                'current' => $current_page,
                'total' => $total_pages,
                'prev_text'    => __('« Página anterior', 'palmaverde'),
                'next_text'    => __('Página siguiente »', 'palmaverde'),
            ));
            $body .=  '</div>';
        }
    }
    wp_reset_postdata();
    return $body;
}

function makistyle_mostrar_entradas($cantidad = -1, $paginacion = false)
{


    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $cantidad,
        'paged' => get_query_var('paged', 1),


    );
    $entradas = new WP_Query($args);
    $body = '';


    while ($entradas->have_posts()): $entradas->the_post();
        $body .= makistyleGetPostBlog();

    endwhile;
    if ($paginacion) {
        $total_pages = $entradas->max_num_pages;
        if ($total_pages > 1) {

            $current_page = max(1, get_query_var('paged'));
            $body .= '<div class="paginacion">';
            $body .=  paginate_links(array(
                'base' => get_pagenum_link(1) . '%_%',
                'format' => '/page/%#%',
                // 'format' => '?paged=%#%',
                'current' => $current_page,
                'total' => $total_pages,
                'prev_text'    => __('« Página anterior', 'makistyle'),
                'next_text'    => __('Página siguiente »', 'makistyle'),
            ));
            $body .=  '</div>';
        }
    }
    wp_reset_postdata();
    return $body;
}

function makistyle_mostrar_tienda($cantidad = -1, $paginacion = false)
{

    $precioQueryString = get_query_var('precio');
    $filtroMetaQuery = null;
    if ($precioQueryString == '0') {
        // debuguear("el precio es 0");
        $filtroMetaQuery = array(
            array(
                'key' => 'makistyle_cmb2_tienda_precio',
                'value' => $precioQueryString,
                'compare' => '='
            )
        );
    }




    $args = array(
        'post_type' => 'tienda_pt',
        'posts_per_page' => $cantidad,
        'paged' => get_query_var('paged', 1),

        // 'meta_query' => array(
        //     array(
        //         'key' => 'makistyle_cmb2_posts_fecha_lanzamiento',
        //         'value' => current_time('timestamp'),
        //         'compare' => '>'
        //     )
        // ),
        // 'meta_type' => 'text_date_timestamp',
        'meta_query' => $filtroMetaQuery,
        'orderby'   => 'meta_value_num',
        'meta_key' => 'makistyle_cmb2_tienda_fecha_lanzamiento2',
        'order'     => 'DESC',
        // 'offset' => 1
        // 'suppress_filters' => true
    );
    $tienda = new WP_Query($args);
    $body = '';

    $counterProductos = 0;
    $nuevaColumna = true;

    while ($tienda->have_posts()): $tienda->the_post();
        if ($nuevaColumna) {
            $body .= /*html*/ '<div class="wp-block-columns is-layout-flex wp-block-columns-is-layout-flex columna">';
            $nuevaColumna = false;
        }
        $counterProductos++;
        $body .= makistyleGetArticuloTienda($counterProductos);
        if ($counterProductos % 3 === 0) {
            $nuevaColumna = true;
            $body .= '</div>';
        }
    endwhile;
    if ($counterProductos !== 0 && $counterProductos < 3) {
        $body .= '</div>';
    }
    if ($paginacion) {
        $total_pages = $tienda->max_num_pages;
        if ($total_pages > 1) {

            $current_page = max(1, get_query_var('paged'));
            $body .= '<div class="paginacion">';
            $body .=  paginate_links(array(
                'base' => get_pagenum_link(1) . '%_%',
                'format' => '/page/%#%',
                // 'format' => '?paged=%#%',
                'current' => $current_page,
                'total' => $total_pages,
                'prev_text'    => __('« Página anterior', 'makistyle'),
                'next_text'    => __('Página siguiente »', 'makistyle'),
            ));
            $body .=  '</div>';
        }
    }
    wp_reset_postdata();
    return $body;
}
