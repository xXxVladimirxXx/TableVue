<?php
namespace Ignet\Kabinet\Plugin;

use Ignet\WP\Dev_Toolkit\Plugin;

/**
 * Class CommonView
 * @package Ignet\SAIP\Core
 */
class CommonView {
	
	/**
	 * @var Plugin
	 */
	private $plugin;
	
	/**
	 * CommonView constructor.
	 *
	 * @param Plugin $plugin
	 */
	public function __construct(Plugin $plugin) {
		
		$this->plugin = $plugin;
		
		add_action('wp_enqueue_scripts', [$this, 'load_assets']);
	}
	
	/**
	 * Загружает части фронта
	 */
	public function load_assets() {

    wp_register_script('lib-vue', 'https://unpkg.com/vue');
		wp_register_script('lib-vue-resource', 'https://unpkg.com/vue-resource@1.3.4/dist/vue-resource.min.js', ['lib-vue']);
		wp_register_script('lib-vue-router', 'https://unpkg.com/vue-router@2.0.0/dist/vue-router.js', ['lib-vue']);
    wp_enqueue_script('quill-help', 'https://cdn.quilljs.com/1.3.4/quill.js');
    wp_enqueue_script('quill-editor', $this->plugin->get('url') . 'assets/js/vue-quill-editor.js', ['lib-vue']);
    wp_enqueue_script('google_charts', 'https://www.gstatic.com/charts/loader.js');
    wp_enqueue_script('chart',  $this->plugin->get('url') . 'assets/js/google_chart.js', ['google_charts']);

    wp_enqueue_style('quill.core', 'https://cdn.quilljs.com/1.3.4/quill.core.css');
    wp_enqueue_style('quill.snow', 'https://cdn.quilljs.com/1.3.4/quill.snow.css');
    wp_enqueue_style('quill.bubble', 'https://cdn.quilljs.com/1.3.4/quill.bubble.css');

    wp_enqueue_style('twitter-bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css');

		//wp_register_script('script-vue-filter-divide-number', $this->plugin->get('url') . 'assets/js/vue-filter-divide-number.js', ['lib-vue']);
		wp_register_script('config-bootstrap-daterangepicker', $this->plugin->get('url') . 'assets/js/config-bootstrap-daterangepicker.js');

		wp_enqueue_script('script-iem-common', $this->plugin->get('url') . 'assets/js/main.js', ['jquery'], null, true);
		wp_localize_script('script-iem-common', 'ajax_data', ['url' => admin_url('admin-ajax.php')]);
		
		if (!is_front_page()) {
			wp_enqueue_style('twitter-bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css');
		}
		
		//wp_enqueue_style('style-iem-common', $this->plugin->get('url') . 'assets/css/common.css');
		wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/font-awesome-4.7.0/css/font-awesome.css' );
	}
}