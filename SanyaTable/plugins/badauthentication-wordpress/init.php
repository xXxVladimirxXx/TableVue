<?php
namespace wp\badauthentication;

use composer\Autoload\ClassLoader;

/**
 * Plugin Name: authentication-wordpress
 * Description: Плагин добавляет возможность авторизации фронта.
 * Author:  Vladimir and Alexandr Dolzhykov
 * Version: 1.0.0
 */

require __DIR__ . '/vendor/autoload.php';

add_action( 'plugins_loaded', function() {

    // Подключает классы
    $class_loader = new ClassLoader();

    $class_loader->addPsr4(__NAMESPACE__ . '\\', __DIR__.'/mvc/controllers');
    $class_loader->addPsr4(__NAMESPACE__ . '\\', __DIR__.'/mvc/models');
    $class_loader->addPsr4(__NAMESPACE__ . '\\', __DIR__.'/mvc/views');
    $class_loader->register(true);

    $plugin = plugin_dir_url(__FILE__);

    new AuthenticationController($plugin);

    new CreatingPostController($plugin);
});