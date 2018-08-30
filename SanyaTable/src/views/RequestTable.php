<?php
namespace Ignet\Kabinet\Plugin;

use Ignet\WP\Dev_Toolkit\Plugin;
use Ignet\WP\Dev_Toolkit\ACF;
use Ignet\WP\Dev_Toolkit\ViewHelper;

/**
 * Class RequestTable
 * @package Ignet\Kabinet\Plugin
 */
class RequestTable {
	
	/**
	 * @var Plugin
	 */
	private $plugin;
	
	/**
	 * OrdersTable constructor.
	 *
	 * @param Plugin $plugin
	 */
	public function __construct(Plugin $plugin) {
		
		$this->plugin     = $plugin;
		
		add_shortcode(__CLASS__ . '\table', [$this, 'get_table']);
		add_action('wp_enqueue_scripts', [$this, 'load_assets']);
	}
	
	/**
	 * Загружает части фронта
	 */
	public function load_assets() {
		
		wp_register_script(
			'table-request',
			$this->plugin->get('url') . 'assets/js/table-request.js',
			['lib-vue', 'lib-vue-resource', 'lib-vue-router'],
			null,
			true
		);
	}
	
	/**
	 * Возвращает вывод
	 */
	public function get_table() {
		
		return ViewHelper::get_file_output($this->plugin->get('dir') . 'src/templates/table-for-request.php');
	}
	
}