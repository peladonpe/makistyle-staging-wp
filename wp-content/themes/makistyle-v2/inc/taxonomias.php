<?php

function makistyle_tipo_recurso_taxonomia()
{

    $labels = array(
        'name'                       => _x('Tipos de recurso', 'Taxonomy General Name', 'makistyle'),
        'singular_name'              => _x('Tipo de recurso', 'Taxonomy Singular Name', 'makistyle'),
        'menu_name'                  => __('Tipos de recurso', 'makistyle'),
        'all_items'                  => __('Tipos de recurso', 'makistyle'),
        'parent_item'                => __('Tipo de recurso Padre', 'makistyle'),
        'parent_item_colon'          => __('Tipo de recurso padre:', 'makistyle'),
        'new_item_name'              => __('Agregar Nuevo Tipo de recurso', 'makistyle'),
        'add_new_item'               => __('Agregar Nuevo Tipo de recurso', 'makistyle'),
        'edit_item'                  => __('Editar Tipo de recurso', 'makistyle'),
        'update_item'                => __('Actualizar Tipo de recurso', 'makistyle'),
        'view_item'                  => __('Ver Tipo de recurso', 'makistyle'),
        'separate_items_with_commas' => __('Separado por comas', 'makistyle'),
        'add_or_remove_items'        => __('Agregar o Borrar Tipo de recurso', 'makistyle'),
        'choose_from_most_used'      => __('Elegir de los más usados', 'makistyle'),
        'popular_items'              => __('Tipos de recurso Populares', 'makistyle'),
        'search_items'               => __('Buscar Tipos de recurso', 'makistyle'),
        'not_found'                  => __('No encontrado', 'makistyle'),
        'no_terms'                   => __('Sin Tipos de recurso', 'makistyle'),
        'items_list'                 => __('Lista de Tipos de recurso', 'makistyle'),
        'items_list_navigation'      => __('Navegación de Tipos de recurso', 'makistyle'),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'query_var'                 => true,
        'rewrite'                   => array('slug' => 'tipo-recurso-tienda'),
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'              => true





    );
    register_taxonomy('tipo_recurso_taxonomia', array('tienda_pt'), $args);
}
add_action('init', 'makistyle_tipo_recurso_taxonomia');

function makistyle_promociones_taxonomia()
{

    $labels = array(
        'name'                       => _x('Promociones', 'Taxonomy General Name', 'makistyle'),
        'singular_name'              => _x('Promoción', 'Taxonomy Singular Name', 'makistyle'),
        'menu_name'                  => __('Promociones', 'makistyle'),
        'all_items'                  => __('Promociones', 'makistyle'),
        'parent_item'                => __('Promoción Padre', 'makistyle'),
        'parent_item_colon'          => __('Promoción padre:', 'makistyle'),
        'new_item_name'              => __('Agregar Nueva Promoción', 'makistyle'),
        'add_new_item'               => __('Agregar Nueva Promoción', 'makistyle'),
        'edit_item'                  => __('Editar Promoción', 'makistyle'),
        'update_item'                => __('Actualizar Promoción', 'makistyle'),
        'view_item'                  => __('Ver Promoción', 'makistyle'),
        'separate_items_with_commas' => __('Separado por comas', 'makistyle'),
        'add_or_remove_items'        => __('Agregar o Borrar Promoción', 'makistyle'),
        'choose_from_most_used'      => __('Elegir de los más usados', 'makistyle'),
        'popular_items'              => __('Promociones Populares', 'makistyle'),
        'search_items'               => __('Buscar Promociones', 'makistyle'),
        'not_found'                  => __('No encontrado', 'makistyle'),
        'no_terms'                   => __('Sin Promociones', 'makistyle'),
        'items_list'                 => __('Lista de Promociones', 'makistyle'),
        'items_list_navigation'      => __('Navegación de Promociones', 'makistyle'),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'query_var'                 => true,
        'rewrite'                   => array('slug' => 'promocion-tienda'),
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'              => true
    );
    register_taxonomy('promociones_taxonomia', array('tienda_pt'), $args);
}
add_action('init', 'makistyle_promociones_taxonomia');

function makistyle_etiquetas_tienda_taxonomia()
{

    $labels = array(
        'name'                       => _x('Etiquetas', 'Taxonomy General Name', 'makistyle'),
        'singular_name'              => _x('Etiqueta', 'Taxonomy Singular Name', 'makistyle'),
        'menu_name'                  => __('Etiquetas', 'makistyle'),
        'all_items'                  => __('Todas las Etiquetas', 'makistyle'),
        'parent_item'                => __('Etiqueta Padre', 'makistyle'),
        'parent_item_colon'          => __('Etiqueta padre:', 'makistyle'),
        'new_item_name'              => __('Nombre de Nueva Etiqueta', 'makistyle'),
        'add_new_item'               => __('Agregar Nueva Etiqueta', 'makistyle'),
        'edit_item'                  => __('Editar Etiqueta', 'makistyle'),
        'update_item'                => __('Actualizar Etiqueta', 'makistyle'),
        'view_item'                  => __('Ver Etiqueta', 'makistyle'),
        'separate_items_with_commas' => __('Separado por comas', 'makistyle'),
        'add_or_remove_items'        => __('Agregar o Borrar Etiqueta', 'makistyle'),
        'choose_from_most_used'      => __('Elegir de las más usadas', 'makistyle'),
        'popular_items'              => __('Etiquetas Populares', 'makistyle'),
        'search_items'               => __('Buscar Etiquetas', 'makistyle'),
        'not_found'                  => __('No encontrada', 'makistyle'),
        'no_terms'                   => __('Sin Etiquetas', 'makistyle'),
        'items_list'                 => __('Lista de Etiquetas', 'makistyle'),
        'items_list_navigation'      => __('Navegación de Etiquetas', 'makistyle'),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'query_var'                 => true,
        'rewrite'                   => array('slug' => 'etiqueta-tienda'),
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'              => true
    );
    register_taxonomy('etiquetas_tienda_taxonomia', array('tienda_pt'), $args);
}
add_action('init', 'makistyle_etiquetas_tienda_taxonomia');

function makistyle_descuentos_taxonomia()
{

    $labels = array(
        'name'                       => _x('Descuentos', 'Taxonomy General Name', 'makistyle'),
        'singular_name'              => _x('Descuento', 'Taxonomy Singular Name', 'makistyle'),
        'menu_name'                  => __('Descuentos', 'makistyle'),
        'all_items'                  => __('Descuentos', 'makistyle'),
        'parent_item'                => __('Descuento Padre', 'makistyle'),
        'parent_item_colon'          => __('Descuento padre:', 'makistyle'),
        'new_item_name'              => __('Agregar Nuevo Descuento', 'makistyle'),
        'add_new_item'               => __('Agregar Nuevo Descuento', 'makistyle'),
        'edit_item'                  => __('Editar Descuento', 'makistyle'),
        'update_item'                => __('Actualizar Descuento', 'makistyle'),
        'view_item'                  => __('Ver Descuento', 'makistyle'),
        'separate_items_with_commas' => __('Separado por comas', 'makistyle'),
        'add_or_remove_items'        => __('Agregar o Borrar Descuento', 'makistyle'),
        'choose_from_most_used'      => __('Elegir de los más usados', 'makistyle'),
        'popular_items'              => __('Descuentos Populares', 'makistyle'),
        'search_items'               => __('Buscar Descuentos', 'makistyle'),
        'not_found'                  => __('No encontrado', 'makistyle'),
        'no_terms'                   => __('Sin Descuentos', 'makistyle'),
        'items_list'                 => __('Lista de Descuentos', 'makistyle'),
        'items_list_navigation'      => __('Navegación de Descuentos', 'makistyle'),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'query_var'                 => true,
        'rewrite'                   => array('slug' => 'descuento-tienda'),
        'show_ui'                    => true,
        'show_admin_column'          => false,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'              => true
    );
    register_taxonomy('descuentos_taxonomia', array('product'), $args);
}
add_action('init', 'makistyle_descuentos_taxonomia');

// function makistyle_cupones_taxonomia()
// {
//     $labels = array(
//         'name'                       => _x('Cupones', 'Taxonomy General Name', 'makistyle'),
//         'singular_name'              => _x('Cupón', 'Taxonomy Singular Name', 'makistyle'),
//         'menu_name'                  => __('Cupones', 'makistyle'),
//         'all_items'                  => __('Todos los Cupones', 'makistyle'),
//         'parent_item'                => __('Cupón Padre', 'makistyle'),
//         'parent_item_colon'          => __('Cupón padre:', 'makistyle'),
//         'new_item_name'              => __('Agregar Nuevo Cupón', 'makistyle'),
//         'add_new_item'               => __('Agregar Nuevo Cupón', 'makistyle'),
//         'edit_item'                  => __('Editar Cupón', 'makistyle'),
//         'update_item'                => __('Actualizar Cupón', 'makistyle'),
//         'view_item'                  => __('Ver Cupón', 'makistyle'),
//         'separate_items_with_commas' => __('Separado por comas', 'makistyle'),
//         'add_or_remove_items'        => __('Agregar o Borrar Cupón', 'makistyle'),
//         'choose_from_most_used'      => __('Elegir de los más usados', 'makistyle'),
//         'popular_items'              => __('Cupones Populares', 'makistyle'),
//         'search_items'               => __('Buscar Cupones', 'makistyle'),
//         'not_found'                  => __('No encontrado', 'makistyle'),
//         'no_terms'                   => __('Sin Cupones', 'makistyle'),
//         'items_list'                 => __('Lista de Cupones', 'makistyle'),
//         'items_list_navigation'      => __('Navegación de Cupones', 'makistyle'),
//     );
//     $args = array(
//         'labels'                     => $labels,
//         'hierarchical'               => true,
//         'public'                     => true,
//         'query_var'                 => true,
//         'rewrite'                   => array('slug' => 'cupon-tienda'),
//         'show_ui'                    => true,
//         'show_admin_column'          => false,
//         'show_in_nav_menus'          => true,
//         'show_tagcloud'              => true,
//         'show_in_rest'              => true
//     );
//     register_taxonomy('cupones_taxonomia', array('product'), $args);
// }
// add_action('init', 'makistyle_cupones_taxonomia');

