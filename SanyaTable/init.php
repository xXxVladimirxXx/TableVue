<?php
namespace Ignet\Kabinet\Plugin;

use Ignet\WP\Dev_Toolkit\RolesRegistry;
use Ignet\WP\Dev_Toolkit\TaxonomyRegistry;
use Ignet\WP\Dev_Toolkit\PostTypeRegistry;
use Composer\Autoload\ClassLoader;
use Ignet\WP\Dev_Toolkit\Plugin;
use Ignet\WP\Dev_Toolkit\ACF;

/*
Plugin Name: Ignet Kabinet Plugin
Description: Описание плагина, заменить на настроящее. Пишите кратко но полно и по сути о назначении плагина.
Author: Dolzhikov Alexandr
Version: 1.0.0.
*/
// Деактивация при отсутствии зависимостей
require_once 'src/models/DependencyManager.php';

$dependency_manager = new \Ignet\WP\Dev_Toolkit\DependencyManager();
$dependency_manager->add('ignet-dev-toolkit/init.php', '- Ignet Dev Toolkit');

if ($dependency_manager->is_plugin_deactivated(__FILE__)) {
	return;
}

add_action('plugins_loaded', function() {
	
	// Автозагрузка
	$loader = new ClassLoader();
	$loader->addPsr4(__NAMESPACE__ . '\\', __DIR__ . '/src/controllers');
	$loader->addPsr4(__NAMESPACE__ . '\\', __DIR__ . '/src/models');
	$loader->addPsr4(__NAMESPACE__ . '\\', __DIR__ . '/src/views');
	$loader->addPsr4(__NAMESPACE__ . '\\', __DIR__ . '/src/helpers');
	$loader->register(true);
	
	// Запуск плагина
	$plugin = new Plugin(__FILE__);
	
	// Запуск компонентов
	
	// Создаем кастомный тип записи - Запросы
	PostTypeRegistry::getInstance()->attach('inquiries', include $plugin->get('dir') . 'config/post-types/inquiries.php');
	
	// Создаем кастомную таксономию - Типы запросов
	TaxonomyRegistry::getInstance()->attach('type_inquiries', include $plugin->get('dir') . 'config/taxonomy/type_inquiries.php');
	
	// Создаем кастомную роль пользователя - Клиент
	RolesRegistry::getInstance()->attach('client', include $plugin->get('dir') . 'config/roles/client.php');
	
	// Класс добавляет нужные для работы скрипты и стили
	new CommonView($plugin);

	$request_manager = new RequestMeneger();

	(new RequestController(
		$plugin,
        $request_manager)
	)->init();

	new RegisterAjaxController($request_manager);

	// Таблица запросов
	new RequestTable($plugin);
	
	// Форма создания запроса
	(new RequestForm($plugin, new ACF\FormExtended))->init();
});