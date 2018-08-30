<?php
namespace wp\badauthentication;

/**
 * Чтобы добавить авторизацию с фронта, достаточно лишь в тему добавить следующее:
 * wp_nav_menu(array('theme_location' => 'authentication') );
 *
 * Class AuthenticationController
 * @package wp\authentication
 */

class AuthenticationController {

    public $plugin;

    public function __construct($plugin)
    {
        $this->plugin = $plugin;

        add_action('wp_enqueue_scripts', [$this, 'load_assets']);
        add_action('after_setup_theme', [$this, 'menu_for_authentication']);
        add_filter('wp_nav_menu_items', [$this, 'add_item_in_menu'], 10, 2);

        add_action('wp_ajax_url_registration', [$this, 'url_registration']);
        add_action('wp_ajax_nopriv_url_registration', [$this, 'url_registration']);

        add_action('wp_ajax_url_authentication', [$this, 'url_authentication']);
        add_action('wp_ajax_nopriv_url_authentication', [$this, 'url_authentication']);
    }

    /**
     * Подключаем скрипт
     */
    public function load_assets()
    {
        wp_enqueue_script('authentication-js', $this->plugin .'assets/js/authentication.js', array('jquery'), '1.0.0', true);
        wp_localize_script( 'authentication-js', 'ajaxurl',
            array(
                'url' => admin_url('admin-ajax.php')
            )
        );

    }

    /**
     * Create menu for authentication
     */
    public function menu_for_authentication()
    {
        register_nav_menu('authentication', 'Menu for authentication');
    }

    /**
     * This method add items in menu
     *
     * @param $items
     * @param $args
     * @return string
     */
    public function add_item_in_menu($items, $args)
    {

        if ('authentication' == $args->theme_location) {

            if (is_user_logged_in()) {
                // Если юзер залогинен
                $items .= '<li><a  id="output" href=" ' . wp_logout_url(home_url()) . ' " title="Выход">Выход</a></li>';
            
                
            } else { 
                // Если не залогинен
                $items .= '<li><a id="loginForm" href="/login/">Вход / Регистрация</a></li>';
            }

        }

        return $items;
    }

    public function url_authentication() {
        
        $login =  $_POST['login'];
        $password = $_POST['password'];
        $rememberme = $_POST['rememberme'];

        $AuthenticationModels = new AuthenticationModels;
        $AuthenticationModels->authentication_user($login, $password, $rememberme);

        wp_die();
    }

    public function url_registration() {

        $login =  $_POST['login'];
        $password = $_POST['password'];
        $email = $_POST['email'];

        $RegisterModels = new RegisterModels;
        $RegisterModels->create_user($login, $password, $email);

        wp_die();
    }
}