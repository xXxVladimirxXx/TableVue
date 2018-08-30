<?php
namespace wp\authentication;

/**
 * Чтобы добавить авторизацию с фронта, достаточно лишь в тему добавить следующее:
 * wp_nav_menu(array('theme_location' => 'authentication') );
 *
 * Class AuthenticationController
 * @package wp\authentication
 */

class PostController {

    public $plugin;

    public function __construct($plugin) {
        
        $this->plugin = $plugin;

        add_action('wp_enqueue_scripts', [$this, 'load_assets']);

        add_action('wp_ajax_get_posts', [$this, 'get_posts']);
        add_action('wp_ajax_nopriv_get_posts', [$this, 'get_posts']);
    }

    /**
     * Подключаем скрипт
     */
    public function load_assets() {

        wp_enqueue_script('create-js', $this->plugin .'assets/js/create.js', array('jquery'), '1.0.0', true);
        wp_localize_script( 'create-js', 'ajaxurl',
            array(
                'url' => admin_url('admin-ajax.php')
            )
        );

    }

    public function get_posts() {
        //http://l9971350.beget.tech/wp-admin/admin-ajax.php?action=get_posts

        header('Access-Control-Allow-Origin: *');

        $GetPostsModels = new GetPostsModels;
        $result = $GetPostsModels->get_posts_models();

        wp_send_json( $result );
    }
    
        public function update_post($id) {
        //http://l9971350.beget.tech/wp-admin/admin-ajax.php?action=update_post

        header('Access-Control-Allow-Origin: *');

        $UpdatePostModels = new UpdatePostModels;
        $result = $UpdatePostModels->update_post_models($id);

        wp_send_json( $result );
    }
}