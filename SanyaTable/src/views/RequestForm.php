<?php
namespace Ignet\Kabinet\Plugin;

use Ignet\WP\Dev_Toolkit\Plugin;
use Ignet\WP\Dev_Toolkit\ACF;
use Ignet\WP\Dev_Toolkit\ViewHelper;

/**
 * Class RequestForm
 * @package Ignet\Kabinet\Plugin
 */
class RequestForm {
	
	/**
	 * @var Plugin
	 */
	private $plugin;
	
	/**
	 * @var ACF\FormExtended
	 */
	private $acf_form;

	/**
	 * @var array
	 */
	private $acf_field_group = ['group_5a7ad27b0542a'];
	
	/**
	 * Сообщение о добавление заказа
	 * @var string
	 */
	public $updated_message = '<p>Новый запрос добавлен!</p>';
	
	/**
	 * @var string
	 */
	private $post_type = 'inquiries';
	
	/**
	 * OrderForm constructor.
	 *
	 * @param Plugin $plugin
	 * @param ACF\FormExtended $acf_form
	 */
	public function __construct(Plugin $plugin, ACF\FormExtended $acf_form) {
		
		$this->plugin = $plugin;
		$this->acf_form = $acf_form;
	}
	
	public function init() {
		
		// Создание формы и привязка к шорткоду
		add_action('wp', [$this, 'set_config']);
		add_shortcode(__CLASS__ . '\request', [$this, 'get_rendered']);

		// Ajax сохранение
		add_action('wp_enqueue_scripts', [$this, 'load_assets']);
		
		// Добавление bootstrap классов
		add_filter('acf/load_field', [ACF\Helper::class, 'add_bootstrap_classes']);
		
		// Добавление двоеточия к лейблам полей
		add_filter('acf/prepare_field', [ACF\Helper::class, 'add_label_colon']);
		
		// Отрисовывает поля по кастомному шаблону
		add_action('WPDT/ACF/FormExtended/render_fields/form_id=' . $this->post_type . '_create_form', [$this, 'render_fields']);
		
		add_filter('acf/pre_save_post', [$this, 'save_new_post'], 10, 1 );
	}
	
	public function set_config() {
		
		global $post;
		
		// Настройки для всех режимов
		$this->acf_form->args = [
			'field_group'          => $this->acf_field_group,
			'post_title'           => true,
			'post_content'         => true,
			'html_updated_message' => sprintf($this->acf_form->success_message_html, $this->updated_message),
			/*'return'               => '%post_url%/?updated=1',*/
			'form'                 => false,
			'html_submit_button'   => false,
		];
		
		// Редактирование объекта
		if (is_a($post, 'WP_Post') and $this->post_type == $post->post_type) {
			
			$this->acf_form->args['id'] = $this->post_type . '_edit_form';
			$this->acf_form->args['post_id'] = $post->ID;
			$this->acf_form->args['html_updated_message'] = sprintf($this->acf_form->success_message_html, '<p>Запрос обновлен!</p>');
			$this->acf_form->args['return'] ='%post_url%/?updated_inquiries=1';
		}
		
		// Создание нового объекта
		else {
			
			$this->acf_form->args['id'] = $this->post_type . '_create_form';

			$this->acf_form->args['new_post'] = [
				'post_type'   => $this->post_type,
				'post_status' => 'publish',
			];

			$this->acf_form->args['post_id'] = 'new_post';
		}
	}
	
	public function save_new_post($post_id) {
		
		$save = array(
			'ID' => 0
		);
		
		if( is_numeric($post_id) ) {
			
			$save['ID']           = $post_id;
			$save['post_type']    = $this->post_type;
			$save['post_status']  = 'publish';
			
		} elseif($post_id == 'new_post') {
			
			// merge in new post data
			$save = array_merge($save, $form['new_post']);
		} else {
			
			return $post_id;
		}
		
		// validate
		if(count($save) == 1) {
			
			return $post_id;
		}
		
		// save
		if($save['ID']) {
			
			wp_update_post($save);
		} /*
    Из-за этого вонючего куска кода данные из профиля сохраняются в мой post_type
    else {
			
			$post_id = wp_insert_post( $save );
			
		}*/
		
		return $post_id;
	}
	
	/**
	 * Подключение ресурсов фронта
	 */
	public function load_assets() {

        // Подключение Ajax обработчика форм редактирования
        wp_register_script(
            'acf-ajax-request-form-handler',
            IGNET_DT_URL . 'vendor/teamignet/dt-acf/assets/js/AcfAjaxFormHandler.js',
            ['jquery']
        );

        wp_register_script(
            'acf-request-ajax-handler',
            $this->plugin->get('url') . 'assets/js/acf-request-ajax-handler.js',
            ['jquery', 'acf-ajax-request-form-handler']
        );
	}
	
	/**
	 * Возвращает вывод формы
	 *
	 * @return bool|string
	 */
	public function get_rendered() {
		
		return ViewHelper::get_file_output($this->plugin->get('dir') . 'src/templates/form-request.php', [
			'acf_form'  => $this->acf_form,
		]);
	}
	
	/**
	 * Отрисовывает поля по кастомному шаблону
	 * @param $fields
	 */
	public function render_fields($fields) {
		
		echo ViewHelper::get_file_output($this->plugin->get('dir') . 'src/templates/form-request-fields.php', [
			'acf_ext_form' => $this->acf_form,
			'fields'       => $fields,
		]);
	}
}