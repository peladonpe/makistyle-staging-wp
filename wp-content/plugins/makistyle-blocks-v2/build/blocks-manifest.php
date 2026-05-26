<?php
// This file is generated. Do not modify it manually.
return array(
	'blog-categoria' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/blog-categoria',
		'version' => '2.0.0',
		'title' => 'Blog / Categoria',
		'category' => 'makistyle-v2',
		'description' => 'Muestra la categoría del artículo (blog)',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'viewScript' => 'file:./view.js',
		'render' => 'file:./render.php'
	),
	'blog-filtrar-categoria' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/blog-filtrar-categoria',
		'version' => '2.0.0',
		'title' => 'Blog / Filtrar categoria',
		'category' => 'makistyle-v2',
		'description' => 'Filtrar artículos del blog por categora',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'blog-home-articulos-destacados' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/blog-home-articulos-destacados',
		'version' => '2.0.0',
		'title' => 'Blog - Home / Articulos destacados',
		'category' => 'makistyle-v2',
		'description' => 'Articulos destacados del blog, que se visualizará en la pagina inicial (Home)',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'numeroArticulosDestacados' => array(
				'type' => 'integer',
				'default' => 6
			)
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'blog-home-categoria' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/blog-home-categoria',
		'version' => '2.0.0',
		'title' => 'Blog - Home / Categoria',
		'category' => 'makistyle-v2',
		'description' => 'Artículos del blog por categoría, para la página inicial (Home)',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'categoriaInicio' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'blog-listado' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/blog-listado',
		'version' => '2.0.0',
		'title' => 'Blog / Listado',
		'category' => 'makistyle-v2',
		'description' => 'Lista de artículos de blog',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'esPaginado' => array(
				'type' => 'boolean',
				'default' => true
			)
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'boilerplate' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/boilerplate',
		'version' => '2.0.0',
		'title' => 'Boilerplate',
		'category' => 'makistyle-v2',
		'description' => 'Boilerplate',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'viewScript' => 'file:./view.js'
	),
	'boilerplate-dynamic' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/boilerplate-dynamic',
		'version' => '2.0.0',
		'title' => 'Boilerplate dynamic',
		'category' => 'makistyle-v2',
		'description' => 'Boilerplate dynamic',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'boton-regresar' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/boton-regresar',
		'version' => '2.0.0',
		'title' => 'Botón Regresar',
		'category' => 'makistyle-v2',
		'description' => 'Botón para regresar a la página anterior del historial',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'breadcrumbs' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/breadcrumbs',
		'version' => '2.0.0',
		'title' => 'Breadcrumbs',
		'category' => 'makistyle-v2',
		'description' => 'Breadcrumbs para Makistyle',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'busqueda-blog-listado' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/busqueda-blog-listado',
		'version' => '2.0.0',
		'title' => 'Busqueda - blog / Listado resultados',
		'category' => 'makistyle-v2',
		'description' => 'Resultados de la búsqueda de artículos blog',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'esPaginado' => array(
				'type' => 'boolean',
				'default' => true
			)
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'busqueda-tienda-listado' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/busqueda-tienda-listado',
		'version' => '2.0.0',
		'title' => 'Busqueda - Tienda / Listado resultados',
		'category' => 'makistyle-v2',
		'description' => 'Resultados de la búsqueda de productos tienda',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'esPaginado' => array(
				'type' => 'boolean',
				'default' => true
			)
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'descripcion-post' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/descripcion-post',
		'version' => '1.0.0',
		'title' => 'Descripción Post',
		'category' => 'makistyle-v2',
		'description' => 'Caja de texto enriquecida para descripción del post',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false,
			'typography' => array(
				'fontSize' => true
			)
		),
		'attributes' => array(
			'fontSize' => array(
				'type' => 'string',
				'default' => 'medium'
			)
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'fecha-actual' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/fecha-actual',
		'version' => '2.0.0',
		'title' => 'Fecha actual',
		'category' => 'makistyle-v2',
		'description' => 'Muestra la fecha actual',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'textdomain' => 'makistyle',
		'render' => 'file:./render.php',
		'editorScript' => 'file:./index.js'
	),
	'menu-principal' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/menu-principal',
		'version' => '2.0.0',
		'title' => 'Menú principal',
		'category' => 'makistyle-v2',
		'description' => 'Menú principal para Makistyle',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'menuItems' => array(
				'type' => 'array',
				'default' => array(
					
				)
			)
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'viewScript' => 'file:./view.js',
		'render' => 'file:./render.php'
	),
	'tienda-autores' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/tienda-autores',
		'version' => '2.0.0',
		'title' => 'Tienda / Autores',
		'category' => 'makistyle-v2',
		'description' => 'Autores del elemento de la tienda',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'tienda-fecha-lanzamiento' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/tienda-fecha-lanzamiento',
		'version' => '2.0.0',
		'title' => 'Tienda / Fecha lanzam.',
		'category' => 'makistyle-v2',
		'description' => 'Fecha lanzamiento artículo tienda',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'tienda-filtrar-tipo-recurso' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/tienda-filtrar-tipo-recurso',
		'version' => '2.0.0',
		'title' => 'Tienda / Filtrar tipo recurso',
		'category' => 'makistyle-v2',
		'description' => 'Filtrar productos de la tienda por tipo de recurso',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'tienda-home-productos-destacados' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/tienda-home-productos-destacados',
		'version' => '2.0.0',
		'title' => 'Tienda - Home / Productos destacados',
		'category' => 'makistyle-v2',
		'description' => 'Productos destacados de la tienda, que se visualizará en la pagina inicial (Home)',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'numeroProductosDestacados' => array(
				'type' => 'integer',
				'default' => 6
			)
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'tienda-home-tipo-recurso' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/tienda-home-tipo-recurso',
		'version' => '2.0.0',
		'title' => 'Tienda - Home / Tipo Recurso',
		'category' => 'makistyle-v2',
		'description' => 'Productos de la tienda por tipo de recurso, para la página inicial (Home)',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'tipoRecursoInicio' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'tienda-listado' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/tienda-listado',
		'version' => '2.0.0',
		'title' => 'Tienda / Listado',
		'category' => 'makistyle-v2',
		'description' => 'Lista de productos en una tienda',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'esPaginado' => array(
				'type' => 'boolean',
				'default' => true
			)
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'tienda-listado-tipo-recurso' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/tienda-listado-tipo-recurso',
		'version' => '2.0.0',
		'title' => 'Tienda / Listado tipo recurso',
		'category' => 'makistyle-v2',
		'description' => 'Lista de productos en la tienda, filtrados por tipo de recurso',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'esPaginado' => array(
				'type' => 'boolean',
				'default' => true
			)
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'tienda-precio' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/tienda-boton-comprar',
		'version' => '2.0.0',
		'title' => 'Tienda / Boton comprar',
		'category' => 'makistyle-v2',
		'description' => 'Botón precio-comprar de la tienda',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'tienda-promociones' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/tienda-promociones',
		'version' => '2.0.0',
		'title' => 'Tienda / Promociones',
		'category' => 'makistyle-v2',
		'description' => 'Muestra la promociones del item tienda',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'viewScript' => 'file:./view.js',
		'render' => 'file:./render.php'
	),
	'tienda-tipo-recurso' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/tienda-tipo-recurso',
		'version' => '2.0.0',
		'title' => 'Tienda / Tipo recurso',
		'category' => 'makistyle-v2',
		'description' => 'Muestra el tipo de recurso del producto (tienda)',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'viewScript' => 'file:./view.js',
		'render' => 'file:./render.php'
	),
	'woocommerce-autores' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/woocommerce-autores',
		'version' => '2.0.0',
		'title' => 'WooCommerce / Autores',
		'category' => 'makistyle-v2',
		'description' => 'Muestra los autores del producto (WooCommerce)',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'woocommerce-boton-comprar' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/woocommerce-boton-comprar',
		'version' => '2.0.0',
		'title' => 'WooCommerce / Botón comprar',
		'category' => 'makistyle-v2',
		'description' => 'Botón de compra o descarga asociado al producto (WooCommerce)',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'woocommerce-categoria' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/woocommerce-categoria',
		'version' => '2.0.0',
		'title' => 'WooCommerce / Categoria',
		'category' => 'makistyle-v2',
		'description' => 'Muestra la categoría del producto (WooCommerce)',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'viewScript' => 'file:./view.js',
		'render' => 'file:./render.php'
	),
	'woocommerce-descripcion' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/woocommerce-descripcion',
		'version' => '2.0.0',
		'title' => 'WooCommerce / Descripcion',
		'category' => 'makistyle-v2',
		'description' => 'Muestra la descripción (larga) del producto (WooCommerce)',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false,
			'typography' => array(
				'fontSize' => true
			)
		),
		'attributes' => array(
			'fontSize' => array(
				'type' => 'string',
				'default' => 'medium'
			)
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'woocommerce-fecha-lanzamiento' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/woocommerce-fecha-lanzamiento',
		'version' => '2.0.0',
		'title' => 'WooCommerce / Fecha lanzam.',
		'category' => 'makistyle-v2',
		'description' => 'Muestra la fecha de lanzamiento del producto (WooCommerce)',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'woocommerce-listado' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/woocommerce-listado',
		'version' => '2.0.0',
		'title' => 'WooCommerce / Listado',
		'category' => 'makistyle-v2',
		'description' => 'Lista de productos de WooCommerce',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'esPaginado' => array(
				'type' => 'boolean',
				'default' => true
			)
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'woocommerce-promociones' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/woocommerce-promociones',
		'version' => '2.0.0',
		'title' => 'WooCommerce / Promociones',
		'category' => 'makistyle-v2',
		'description' => 'Muestra las promociones del producto (WooCommerce)',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php'
	),
	'woocommerce-youtube-destacado' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/woocommerce-youtube-destacado',
		'version' => '2.0.0',
		'title' => 'WooCommerce / Youtube destacado',
		'category' => 'makistyle-v2',
		'description' => 'Video de youtube incrustado para productos de WooCommerce',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'autoplay' => array(
				'type' => 'boolean',
				'default' => true
			)
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'youtube-destacado' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'makistyle-blocks-v2/youtube-destacado',
		'version' => '2.0.0',
		'title' => 'Youtube destacado',
		'category' => 'makistyle-v2',
		'description' => 'Video de youtube incrustado',
		'example' => array(
			
		),
		'supports' => array(
			'html' => false
		),
		'attributes' => array(
			'autoplay' => array(
				'type' => 'boolean',
				'default' => true
			)
		),
		'textdomain' => 'makistyle',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	)
);
