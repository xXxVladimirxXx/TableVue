<?php

return [
	'label'                 => 'Типы запросов', // определяется параметром $labels->name
	'labels'                => [
		'name'              => 'Типы запросов',
		'singular_name'     => 'Тип запроса',
		'search_items'      => 'Искать',
		'all_items'         => 'Все',
		'view_item '        => 'Смотреть',
		'parent_item'       => 'Родитель',
		'parent_item_colon' => 'Родитель:',
		'edit_item'         => 'Редактировать',
		'update_item'       => 'Обновить',
		'add_new_item'      => 'Добавить',
		'new_item_name'     => 'Новый',
		'menu_name'         => 'Типы запросов',
	],
	'description'           => '', // описание таксономии
	'public'                => true,
	'publicly_queryable'    => null, // равен аргументу public
	'show_in_nav_menus'     => true, // равен аргументу public
	'show_ui'               => true, // равен аргументу public
	'show_tagcloud'         => true, // равен аргументу show_ui
	'show_in_rest'          => null, // добавить в REST API
	'rest_base'             => null, // $taxonomy
	'hierarchical'          => true,
	'update_count_callback' => '',
	'rewrite'               => true, //'query_var' => $taxonomy, // название параметра запроса
	'capabilities'          => [],
	// callback функция. Отвечает за html код метабокса (с версии 3.8): post_categories_meta_box или post_tags_meta_box.
	// Если указать false, то метабокс будет отключен вообще
	'meta_box_cb'           => null,
	// Позволить или нет авто-создание колонки таксономии в таблице ассоциированного типа записи. (с версии 3.5)
	'show_admin_column'     => false,
	'_builtin'              => false,
	'show_in_quick_edit'    => null,    // по умолчанию значение show_ui
	'post_types'            => ['inquiries'] // типы постов, для второго аргумента функции register_taxonomy()
];