<?php
namespace Ignet\Kabinet\Plugin;

use Ignet\WP\Dev_Toolkit\ACF;
use Ignet\WP\Dev_Toolkit\Plugin;
use Ignet\WP\Dev_Toolkit\UserHelper;

class RequestController {
	
	/**
	 * @var plugin
	 */
	public $plugin;
	
	/**
	 * @var request_list
	 */
	public $request_list;
	
	/**
	 * RequestController constructor.
	 *
	 * @param Plugin $plugin
	 * @param RequestMeneger $request_list
	 */
	public function __construct(Plugin $plugin, RequestMeneger $request_list) {
	
	 $this->plugin               = $plugin;
	 $this->request_list         = $request_list;
	}
	
	/**
	 * Инициализирует хуки
	 */
	public function init() {
		
		// Получение списка запросов
		add_action('wp_ajax_get_request_data', [$this, 'get_request_data']);
	}

	public function get_request_data() {

		echo json_encode($this->request_list->get_posts_data());

		die();
	}
}