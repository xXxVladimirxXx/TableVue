<?php
namespace Chess\Statistic;

class lichessAuthViewer {

    public $plugin;

    public function __construct($plugin) {

        $this->plugin = $plugin;

        add_action('wp_enqueue_scripts', [$this, 'load_assets']);
    }

    public function load_assets() {

        wp_enqueue_script('auth_handler', $this->plugin . '/assets/js/auth.js', ['jquery']);
        wp_localize_script( 'auth_handler', 'myajax',
            array(
                'url' => admin_url('admin-ajax.php')
            )
        );
    }
}