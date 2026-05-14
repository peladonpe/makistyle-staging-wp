<?php
/**
 * Title: makistyle-blog
 * Slug: makistyle-v2/makistyle-patron-blog
 * Categories: Makistyle
 * Post Types: post
 */
?>
<!-- wp:group {"metadata":{"name":"makistyle-patron-blog"},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:post-title {"textAlign":"center","className":"monoton-regular titulo","style":{"elements":{"link":{"color":{"text":"var:preset|color|color-primario"}}}},"textColor":"color-primario","fontSize":"x-large"} /-->

<!-- wp:group {"tagName":"section","metadata":{"name":"hero"},"className":"position-relative h-600 p-0 overflow-hidden","layout":{"type":"constrained"}} -->
<section id="hero" class="wp-block-group position-relative h-600 p-0 overflow-hidden"><!-- wp:makistyle-blocks-v2/youtube-destacado /--></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"main","metadata":{"name":"main"},"backgroundColor":"negro","layout":{"type":"constrained"}} -->
<main id="main" class="wp-block-group has-negro-background-color has-background"><!-- wp:group {"className":"meta","style":{"elements":{"link":{"color":{"text":"var:preset|color|blanco"}}}},"backgroundColor":"negro","textColor":"blanco","layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group meta has-blanco-color has-negro-background-color has-text-color has-background has-link-color"><!-- wp:makistyle-blocks-v2/blog-categoria /-->

<!-- wp:post-date {"textAlign":"right","metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}},"className":"fecha-publicacion","style":{"elements":{"link":{"color":{"text":"var:preset|color|blanco"}}},"typography":{"fontSize":"1em"}},"textColor":"blanco"} /--></div>
<!-- /wp:group -->

<!-- wp:post-title {"level":3,"style":{"elements":{"link":{"color":{"text":"var:preset|color|blanco"}}}},"textColor":"blanco","fontSize":"x-large"} /-->

<!-- wp:group {"metadata":{"name":"autor"},"className":"autor","style":{"spacing":{"blockGap":"1rem"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"}} -->
<div class="wp-block-group autor"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|blanco"}}}},"textColor":"blanco","fontSize":"medium"} -->
<p class="has-blanco-color has-text-color has-link-color has-medium-font-size">Escrito por:</p>
<!-- /wp:paragraph -->

<!-- wp:post-author-name {"style":{"elements":{"link":{"color":{"text":"var:preset|color|blanco"}}}},"textColor":"blanco","fontSize":"medium"} /--></div>
<!-- /wp:group -->

<!-- wp:makistyle-blocks-v2/descripcion-post -->
<div class="wp-block-makistyle-blocks-v2-descripcion-post has-medium-font-size"><div class="wp-block-makistyle-blocks-v2-descripcion-post-content"></div></div>
<!-- /wp:makistyle-blocks-v2/descripcion-post -->

<!-- wp:separator {"backgroundColor":"blanco"} -->
<hr class="wp-block-separator has-text-color has-blanco-color has-alpha-channel-opacity has-blanco-background-color has-background"/>
<!-- /wp:separator -->

<!-- wp:group {"metadata":{"name":"enlaces-articulo-redes-sociales"},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"left"}} -->
<div class="wp-block-group"><!-- wp:group {"metadata":{"name":"instagram"},"className":"enlace-social-articulo","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group enlace-social-articulo"><!-- wp:social-links {"openInNewTab":true,"showLabels":true} -->
<ul class="wp-block-social-links has-visible-labels"><!-- wp:social-link {"url":"","service":"instagram","label":"Ver en Instagram"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"youtube"},"className":"enlace-social-articulo","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group enlace-social-articulo"><!-- wp:social-links {"openInNewTab":true,"showLabels":true} -->
<ul class="wp-block-social-links has-visible-labels"><!-- wp:social-link {"url":"","service":"youtube","label":"Ver en Youtube"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"tiktok"},"className":"enlace-social-articulo","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group enlace-social-articulo"><!-- wp:social-links {"openInNewTab":true,"showLabels":true,"metadata":{"name":"iconos sociales"}} -->
<ul class="wp-block-social-links has-visible-labels"><!-- wp:social-link {"url":"","service":"tiktok","label":"Ver en Tiktok","className":"border border-white"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></main>
<!-- /wp:group --></div>
<!-- /wp:group -->