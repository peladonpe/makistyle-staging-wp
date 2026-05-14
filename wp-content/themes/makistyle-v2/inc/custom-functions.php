<?php
function getTextBlock($block)
{
    $block_html = sanitize_text_field($block['innerHTML']);



    // echo $block_html;
    // echo '<br>';
    // switch ($tipo) {
    //     case 'texto':
    //         $pos_start = strpos($block_html, '&gt;') + 4;
    //         break;
    //     case 'boton':
    //         $pos_a = strpos($block_html, '&lt;a');
    //         $pos_start = strpos($block_html, '&gt;', $pos_a) + 4;
    //         break;
    //     default:
    //         break;
    // }
    // $pos_end = strpos($block_html, '&lt;', $pos_start);
    // $text_block = substr($block_html, $pos_start, $pos_end - $pos_start);
    // $text_block = htmlentities($block_html);
    $text_block = strip_tags($block_html);
    return $text_block;
}

function getHtmlPersonalizado($block)
{
    // echo '<div class="text-white">';
    // echo ($block['innerHTML']);
    // echo '</div>';
    return $block['innerHTML'];
}


function getTextInnerBlocks($block, $blocksChecked, $results = [])
{
    $innerBlocks = $block['innerBlocks'];
    if (empty($innerBlocks)) return $results;
    foreach ($innerBlocks as $innerBlock) {
        foreach ($blocksChecked as $nombre => $tipo) {
            switch ($tipo) {
                case 'texto':
                    if ($innerBlock['attrs']['metadata']['name'] === $nombre) {
                        $results[$nombre] = getTextBlock($innerBlock);
                    }
                    break;
                case 'html_personalizado':
                    if ($innerBlock['attrs']['metadata']['name'] === $nombre) {
                        $results[$nombre] = getHtmlPersonalizado($innerBlock);
                    }
                    break;
                // case 'pr':
                //     if ($innerBlock['attrs']['metadata']['name'] === $nombre) {
                //         echo '<div class="text-white">';
                //         var_dump($innerBlock);
                //         echo '</div>';
                //     }
                //     break;
                default:
                    break;
            }
        }
        $results = getTextInnerBlocks($innerBlock, $blocksChecked, $results);
    }
    return $results;
}

function mostrarBotonPrecio($precio, $enlaceCompra)
{
    if ($precio == 0) {
        $textoBoton = 'DESCARGA GRATUITA';
    } else {
        $textoBoton = $precio . ' € - COMPRAR';
    }
    $htmlBoton =
        /*html*/ '
            <div class="wp-block-buttons mb-4 is-layout-flex wp-block-buttons-is-layout-flex">
                <div class="wp-block-button btn-outline-neon-naranja is-style-outline is-style-outline--1" >
                    <a class="wp-block-button__link has-neon-naranja-color has-text-color has-link-color has-medium-font-size has-custom-font-size wp-element-button" href="%2$s" target="_blank" style="border-radius:8px">%1$s
                    </a>
                </div>
            </div>
    ';
    $htmlBotonFinal = sprintf(
        $htmlBoton,
        $textoBoton,
        $enlaceCompra
    );
    return $htmlBotonFinal;
}

function debuguear($elemento)
{
    echo '<p class="color-blanco mb-3">';
    var_dump(($elemento));
    echo '</p>';
}

function makistyleGetArticuloTienda($counterProductos)
{
    $fechaLanzamiento2 = get_post_meta(get_the_ID(), 'makistyle_cmb2_tienda_fecha_lanzamiento2', true);
    $fechaFormateada = date('d-m-Y', $fechaLanzamiento2);
    $precio = get_post_meta(get_the_ID(), 'makistyle_cmb2_tienda_precio', true);
    $enlaceCompra = get_post_meta(get_the_ID(), 'makistyle_cmb2_tienda_enlace_compra', true);
    $idYoutubeDestacado = get_post_meta(get_the_ID(), 'makistyle_cmb2_todos_pt_id_youtube_destacado', true);
    // $tipoRecursoPost = get_the_term_list(get_the_ID(), 'tipo_recurso_taxonomia', '', ',', '');
    $tipoRecursoPost = get_the_terms(get_the_ID(), 'tipo_recurso_taxonomia');
    // $tipoRecursoPorSlug = get_term_by('slug', 'productos-fisicos', 'tipo_recurso_taxonomia');
    $tipoRecursoId = $tipoRecursoPost[0]->term_id;
    $nombreTipoRecursoSingular = get_term_meta($tipoRecursoId, 'makistyle_cmb2_tipo_recurso_taxonomia_nombre_singular', true);

    $blocks = parse_blocks(get_the_content());
    // var_dump($blocks);
    $blocksChecked = array(
        'h3' => 'texto',
        'promo' => 'texto',
        'autor' => 'texto',
        'soundcloud_destacado' => 'html_personalizado',
    );
    foreach ($blocksChecked as $key => $tipo) {
        $$key = '';
    }
    foreach ($blocks as $block) {
        // var_dump($block);

        $results = getTextInnerBlocks($block, $blocksChecked);

        foreach ($results as $key => $value) {
            $$key = $value;
        }
    }
    $imageSizes = makistyle_get_image_sizes(get_the_ID());
    $idImagen = get_post_thumbnail_id(get_the_ID());
    $altImagen = get_post_meta($idImagen, '_wp_attachment_image_alt', true);
    $htmlImagen =
        /*html*/ '
            <a href="%5$s" class="wp-block-image text-decoration-none" >
                <figure class="aligncenter size-medium" style="width: 100%%;">
                    <img  fetchpriority="high" decoding="async" width="400" height="400" src="%2$s" alt="%6$s" class="wp-image-440" srcset="%2$s 400w, %1$s 150w, %3$s 600w, %4$s 1020w" sizes="(max-width: 400px) 100vw, 400px" style="width: 100%%; height:400px; object-fit:cover">

                </figure>
            </a>
        ';
    $htmlImagenFinal = sprintf(
        $htmlImagen,
        $imageSizes[0],
        $imageSizes[1],
        $imageSizes[2],
        $imageSizes[3],
        esc_url(get_the_permalink()),
        esc_html($altImagen)
    );

    $htmlSpinner =
        /*html*/ '
            <div id="spinner-youtube" class="position-absolute inset-0 d-flex justify-content-center align-items-center">
                <div class="spinner-border has-color-primario-color " role="status">
                     <span class="visually-hidden">
                     Loading…
                     </span>
                </div>
            </div>
        ';
    $elementoSuperior = null;
    if ($idYoutubeDestacado) {
        $htmlYoutube =
            /*html*/ '
                <div class="position-absolute inset-0">
                <iframe width="100%%" height="400" src="https://www.youtube.com/embed/%1$s?playsinline=1&enablejsapi=1&origin=https%%3A%%2F%%2Fmakistyle.miafoma.com&widgetid=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            ';
        $htmlYoutubeFinal = sprintf(
            $htmlYoutube,
            $idYoutubeDestacado
        );
        $htmlFinal = sprintf(
            /*html*/
            '
                <div class="wp-block-group position-relative h-400 p-0 overflow-hidden">
                    <div class="wp-block-group__inner-container is-layout-constrained wp-block-group-is-layout-constrained">
                        %1$s
                        %2$s
                    </div>
                </div>
                ',


            $htmlSpinner,
            $htmlYoutubeFinal
        );
        $elementoSuperior = $htmlFinal;
    } else if ($soundcloud_destacado) {
        $htmlSoundCloud = sprintf(
            /*html*/
            '
                <div class="position-absolute inset-0">
                    %1$s
                </div>',
            $soundcloud_destacado

        );
        $htmlFinal = sprintf(
            /*html*/
            '
                <div class="wp-block-group position-relative h-400 p-0 overflow-hidden">
                    <div class="wp-block-group__inner-container is-layout-constrained wp-block-group-is-layout-constrained">
                        %1$s
                        %2$s
                    </div>
                </div>
                ',

            $htmlSpinner,
            $htmlSoundCloud
        );
        $elementoSuperior = $htmlFinal;
    } else {
        $htmlFinal = sprintf(
            /*html*/
            '
                <div class="wp-block-group position-relative h-400 p-0 overflow-hidden">
                    <div class="wp-block-group__inner-container is-layout-constrained wp-block-group-is-layout-constrained">
                            %1$s
                    </div>
                </div>
                ',

            $htmlImagenFinal,
        );
        // $elementoSuperior = htmlentities($htmlImagenFinal);
        $elementoSuperior = $htmlImagenFinal;
        // echo '<div class="text-white">';
        // echo (htmlentities($elementoSuperior));
        // echo '</div>';
    }
    $badgePrecio = null;
    if (($precio || $precio == 0)) {
        if ($precio == 0) {
            $textoBoton = 'GRATUITO';
        } else {
            $textoBoton = $precio . ' €';
        }
        $htmlPrecio =
            /*html*/ '
                <div class="wp-block-group text-center mb-3">
                    <div class="wp-block-group__inner-container is-layout-constrained wp-block-group-is-layout-constrained">
                        <div class="has-text-align-center badge p-2 fs-5 has-negro-color has-neon-naranja-background-color has-text-color has-background has-link-color">%1$s
                        </div>
                    </div>
                </div>
            ';
        $htmlPrecioFinal = sprintf(
            $htmlPrecio,
            $textoBoton
        );
        $badgePrecio = $htmlPrecioFinal;
    };
    $htmlString =
        /*html*/ '


                <div class="wp-block-column is-layout-flow wp-block-column-is-layout-flow mw-sm-4">
                    <div class="wp-block-group p-3 has-fondo-oscuro-cards-background-color has-background border-card-hover-primario h-100">
                        <div class="d-block wp-block-group__inner-container is-layout-constrained wp-block-group-is-layout-constrained text-decoration-none">
                            <div class="mb-4">
                                %1$s
                            </div>
                            <a href="%8$s" class="text-decoration-none">

                                <div class="wp-block-group justify-content-center justify-content-xl-between mb-3 gap-3 gap-xl-5 is-layout-flex wp-block-group-is-layout-flex">
                                    <div class="has-text-align-left badge has-negro-color has-color-primario-background-color has-text-color has-background has-link-color has-small-font-size fw-normal text-uppercase">%2$s</div>
                                    <p class="has-text-align-left has-blanco-color has-text-color has-link-color has-small-font-size">%3$s</p>
                                </div>



                            
                                <p class="has-text-align-center has-blanco-color has-text-color has-link-color has-medium-font-size">%4$s</p>
                                <p class="has-text-align-center has-color-primario-color has-text-color has-link-color has-small-font-size">%5$s</p>    
                                %6$s
                                <p class="has-text-align-center has-blanco-color has-text-color has-link-color has-small-font-size tres-lineas">%7$s</p>
                            </a>
                        </div>
                    </div>
                </div>
        ';
    $body = sprintf(
        $htmlString,
        $elementoSuperior,
        $nombreTipoRecursoSingular,
        $fechaFormateada,
        esc_html($h3) . " - " . esc_html($autor),
        esc_html($promo),
        $badgePrecio,
        esc_html(get_the_excerpt()),
        esc_url(get_the_permalink()),
    );
    return $body;
}

function makistyleGetPostBlog()
{

    // the_content();
    $fechaPublicacion = get_the_date();
    // $autorId = new_get_author_id_by_post(get_the_ID());
    // $nombreAutor = get_the_author_meta('display_name', $autorId);
    $idYoutubeDestacado = get_post_meta(get_the_ID(), 'makistyle_cmb2_todos_pt_id_youtube_destacado', true);
    $blocks = parse_blocks(get_the_content());
    // var_dump($blocks);
    $blocksChecked = array(
        'h3' => 'texto',
        'soundcloud_destacado' => 'html_personalizado',
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
    }
    $imageSizes = makistyle_get_image_sizes(get_the_ID());
    $idImagen = get_post_thumbnail_id(get_the_ID());
    $altImagen = get_post_meta($idImagen, '_wp_attachment_image_alt', true);
    $htmlImagen =
        /*html*/ '
                <a href="%5$s" class="wp-block-image fallback-youtube" >
                    <figure class=" size-medium text-center" >
                        <img fetchpriority="high" decoding="async" width="400" height="400" src="%2$s" alt="%6$s" class="wp-image-210" srcset="%2$s 400w, %1$s.png 150w, %3$s 600w, %4$s 1080w" sizes="(max-width: 400px) 100vw, 400px" style="height:400px;width:100%%;object-fit:cover;" />
                    </figure>
                </a>
            ';
    $htmlImagenFinal = sprintf(
        $htmlImagen,
        $imageSizes[0],
        $imageSizes[1],
        $imageSizes[2],
        $imageSizes[3],
        esc_url(get_the_permalink()),
        esc_html($altImagen)
    );
    $htmlSpinner =
        /*html*/ '
            <div id="spinner-youtube" class="position-absolute inset-0 d-flex justify-content-center align-items-center">
                <div class="spinner-border has-color-primario-color " role="status">
                    <span class="visually-hidden">
                    Loading…
                    </span>
                </div>
            </div>
        ';
    $elementoSuperior = null;
    if ($idYoutubeDestacado) {

        $htmlYoutube =
            /*html*/ '
                <div class="position-absolute inset-0">
                <iframe width="100%%" height="400" src="https://www.youtube.com/embed/%1$s?playsinline=1&enablejsapi=1&origin=https%%3A%%2F%%2Fmakistyle.miafoma.com&widgetid=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            ';
        $htmlYoutubeFinal = sprintf(
            $htmlYoutube,
            $idYoutubeDestacado
        );
        $htmlFinal = sprintf(
            /*html*/
            '
                <div class="wp-block-column is-layout-flow wp-block-column-is-layout-flow position-relative" style="flex:0 0 40%%;">
                   
                        <div class="invisible">
                            %1$s
                        </div>
                        %2$s
                        %3$s
                </div>
                ',
            $htmlImagenFinal,
            $htmlSpinner,
            $htmlYoutubeFinal
        );
        $elementoSuperior = $htmlFinal;
    } elseif ($soundcloud_destacado) {
        $htmlSoundCloud = sprintf(
            /*html*/
            '
                <div class="position-absolute inset-0">
                %1$s
                </div>',
            $soundcloud_destacado
        );
        $htmlFinal = sprintf(
            /*html*/
            '
                    <div class="wp-block-column is-layout-flow wp-block-column-is-layout-flow position-relative" style="flex:0 0 40%%">
                        <div class="invisible">
                            %1$s
                        </div>
                        %2$s
                        %3$s
                    </div>',
            $htmlImagenFinal,
            $htmlSpinner,
            $htmlSoundCloud
        );

        $elementoSuperior = $htmlFinal;
    } else {
        $htmlFinal = sprintf(
            /*html*/
            '
                    <div class="wp-block-column is-layout-flow wp-block-column-is-layout-flow position-relative" style="flex:0 0 40%%">
                            %1$s
                    </div>',
            $htmlImagenFinal,
        );
        $elementoSuperior = $htmlFinal;
    };

    $htmlString =
        /*html*/ '
            <a href="%5$s" class="text-decoration-none">
                <div class="wp-block-columns has-background is-layout-flex wp-block-columns-is-layout-flex border-card-hover-primario justify-content-center has-fondo-oscuro-cards-background-color p-3 p-sm-4">              
                    %1$s
                    
                    <div class="wp-block-column is-vertically-aligned-center is-layout-flow wp-block-column-is-layout-flow p-3 p-md-5" style="flex:0 0 60%%">
                        <a href="%5$s" class="text-decoration-none">
                            <p class="has-text-color has-link-color has-text-align-right has-small-font-size" style="color:#fdfdfd">%2$s</p>
                            <h3 class="wp-block-heading has-text-color has-link-color has-medium-font-size mb-3" style="color:#fdfdfd">%3$s</h3>
                        
                            <p class="has-text-color has-link-color" style="color:#fdfdfd;">
                                <span class="tres-lineas">%4$s</span>
                            
                            </p>
                        </a>
                    </div>
                </div>
                </a>
        ';
    $body = sprintf(
        /*html*/
        $htmlString,
        $elementoSuperior,
        $fechaPublicacion,
        esc_html($h3),
        esc_html(get_the_excerpt()),
        esc_url(get_the_permalink()),
    );

    return $body;
}
