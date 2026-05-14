<?php


function makistyle_posttype_tienda()
{
    $labels = array(
        'name'                  => _x('Tienda', 'makistyle'),
        'singular_name'         => _x('Producto',  'makistyle'),
        'menu_name'             => _x('Tienda', 'Admin Menu text', 'makistyle'),
        'name_admin_bar'        => _x('Producto', 'Add New on Toolbar', 'makistyle'),
        'add_new'               => __('Agregar Producto', 'makistyle'),
        'add_new_item'          => __('Agregar Nuevo Producto', 'makistyle'),
        'new_item'              => __('Nuevo Producto', 'makistyle'),
        'edit_item'             => __('Editar Producto', 'makistyle'),
        'view_item'             => __('Ver Producto', 'makistyle'),
        'all_items'             => __('Toda la tienda', 'makistyle'),
        'search_items'          => __('Buscar productos de la tienda', 'makistyle'),
        'parent_item_colon'     => __('Padre producto de la tienda:', 'makistyle'),
        'not_found'             => __('No se encontraron productos.', 'makistyle'),
        'not_found_in_trash'    => __('No se encontrar productos en la Papelera', 'makistyle'),
        'featured_image'        => _x('Imagen Destacada', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'makistyle'),
        'set_featured_image'    => _x('Agregar imagen Destacada', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'makistyle'),
        'remove_featured_image' => _x('Borrar imagen destacada', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'makistyle'),
        'use_featured_image'    => _x('Usar Imagen destacada', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'makistyle'),
        'archives'              => _x('Archivo de Tienda', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'makistyle'),
        'insert_into_item'      => _x('Insertar en Tienda', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'makistyle'),
        'uploaded_to_this_item' => _x('Cargadas En Producto', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post). Added in 4.4', 'makistyle'),
        'filter_items_list'     => _x('Filtrar Lista de productos', 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/"Filter pages list". Added in 4.4', 'makistyle'),
        'items_list_navigation' => _x('Tienda navegación', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'makistyle'),
        'items_list'            => _x('Lista tienda', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'makistyle'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'articulo-tienda'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-controls-volumeon',
        // true como paginas (pueden tener hijos), false como posts (no tienen hijos)
        'hierarchical'       => false,
        'menu_position'      => 7,
        'supports'           => array('title', 'editor',  'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
        'rest_base'          => 'tienda',
        // 'taxonomies'         => array('tipo_recurso_taxonomia')
        'template'           => array(
            array('core/pattern', array(
                'slug' => 'makistyle-v2/makistyle-patron-tienda'
            ))
        )
    );

    register_post_type('tienda_pt', $args);
    flush_rewrite_rules();
}

add_action('init', 'makistyle_posttype_tienda');


function makistyle_post_blog_template()
{
    global $wp_post_types;
    if (isset($wp_post_types['post'])) {
        $wp_post_types['post']->template = array(
            array('core/pattern', array(
                'slug' => 'makistyle-v2/makistyle-patron-blog'
            ))
        );
    }
}

add_action('init', 'makistyle_post_blog_template', 20);
