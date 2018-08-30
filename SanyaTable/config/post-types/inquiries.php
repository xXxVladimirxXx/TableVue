<?php

return [
	'label'   => 'Запросы',
	'labels'  => [
		'name'               => 'Запросы',
		'singular_name'      => 'Запрос',
		'add_new'            => 'Добавить',
		'add_new_item'       => 'Добавление',
		'edit_item'          => 'Редактирование',
		'new_item'           => 'Новый',
		'view_item'          => 'Смотреть',
		'search_items'       => 'Искать',
		'not_found'          => 'Не найдено',
		'not_found_in_trash' => 'Не найдено в корзине',
		'parent_item_colon'  => '',
		'menu_name'          => 'Запросы',
	],
	'description'         => '',
	'public'              => true,
	'publicly_queryable'  => true,
	'exclude_from_search' => true,
	'show_ui'             => null,
	'show_in_menu'        => null, // показывать ли в меню консоли
	'show_in_admin_bar'   => null, // по умолчанию значение show_in_menu
	'show_in_nav_menus'   => null,
	'show_in_rest'        => null, // добавить в REST API. C WP 4.7
	'rest_base'           => null, // $post_type. C WP 4.7
	'menu_position'       => 21,
	'menu_icon'           => 'dashicons-id',
	'hierarchical'        => false,
	
	// 'title','editor','author','thumbnail','excerpt','trackbacks',
	// 'custom-fields','comments','revisions','page-attributes','post-formats'
	'supports'            => ['title','editor','author','thumbnail','excerpt'],
	'taxonomies'          => [],
	'has_archive'         => false,
	'rewrite'             => true,
	'query_var'           => true,
];