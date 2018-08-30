<?php
namespace MailPoet\WP;

class Notice {

  const TYPE_ERROR = 'error';
  const TYPE_WARNING = 'warning';
  const TYPE_SUCCESS = 'success';
  const TYPE_INFO = 'info';

  private $type;
  private $message;

  function __construct($type, $message, $classes = '', $data_notice_name = '') {
    $this->type = $type;
    $this->message = $message;
    $this->classes = $classes;
    $this->data_notice_name = $data_notice_name;
  }

  static function displayError($message, $classes = '', $data_notice_name = '') {
    $message = sprintf(
      "<b>%s </b> %s",
      __('MailPoet Error:', 'mailpoet'),
      $message
    );
    self::createNotice(self::TYPE_ERROR, $message, $classes, $data_notice_name);
  }

  static function displayWarning($message, $classes = '', $data_notice_name = '') {
    self::createNotice(self::TYPE_WARNING, $message, $classes, $data_notice_name);
  }

  static function displaySuccess($message, $classes = '', $data_notice_name = '') {
    self::createNotice(self::TYPE_SUCCESS, $message, $classes, $data_notice_name);
  }

  static function displayInfo($message, $classes = '', $data_notice_name = '') {
    self::createNotice(self::TYPE_INFO, $message, $classes, $data_notice_name);
  }

  protected static function createNotice($type, $message, $classes, $data_notice_name) {
    $notice = new Notice($type, $message, $classes, $data_notice_name);
    add_action('admin_notices', array($notice, 'displayWPNotice'));
  }

  function displayWPNotice() {
    $class = sprintf('notice notice-%s mailpoet_notice_server %s', $this->type, $this->classes);
    $message = nl2br($this->message);
    $data_notice_name = !empty($this->data_notice_name) ? sprintf('data-notice="%s"', $this->data_notice_name) : '';

    printf('<div class="%1$s" %3$s><p>%2$s</p></div>', $class, $message, $data_notice_name);
  }
}
