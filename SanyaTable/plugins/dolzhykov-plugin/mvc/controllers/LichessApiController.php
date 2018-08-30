<?php
namespace Chess\Statistic;

class LichessApiController {

    protected $token;

    // URL for authorization in lichess
    protected $authorizeURL = 'https://oauth.lichess.org/oauth/authorize';

    protected $redirect_URI = 'http://l9971350.beget.tech/';

    protected $callback_URL = 'http://l9971350.beget.tech/wp-admin/admin-ajax.php?action=callback_URL';

    protected $client_ID;

    protected $client_secret;

    public function __construct($client_ID, $client_secret) {

        $this->client_ID     = $client_ID;
        $this->client_secret = $client_secret;

        add_action('wp_ajax_start_auth', [$this, 'start_auth']);
        add_action('wp_ajax_nopriv_start_auth', [$this, 'start_auth']);

        add_action('wp_ajax_callback_URL', [$this, 'callback_URL']);
        add_action('wp_ajax_nopriv_callback_URL', [$this, 'callback_URL']);

        add_action('wp_ajax_get_token', [$this, 'get_token']);
        add_action('wp_ajax_nopriv_get_token', [$this, 'get_token']);
    }

    public function start_auth() {
        // http://l9971350.beget.tech/wp-admin/admin-ajax.php?action=start_auth

        $scope = 'game:read preference:read preference:write email:read tournament:write';
        $state = $this->state_generation(24);
        $query['url_for_auth'] = $this->authorizeURL . '?response_type=code&client_id=' . $this->client_ID . '&redirect_uri=' . $this->callback_URL . '&scope=' . $scope . '&state=' . $state;

        wp_send_json($query);
    }

    /**
     * Генерирует случайную строку для параметра 'state'
     *
     * @param int $length
     * @return string
     */
    public function state_generation($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Сюда приходят ответы с lichess
     */
    public function callback_URL() {
        // http://l9971350.beget.tech/wp-admin/admin-ajax.php?action=callback_URL

        $query = 'https://oauth.lichess.org/oauth';

        if( ! empty($_REQUEST['code'])) {

            $code = $_REQUEST['code'];
        }

        // установка URL и других необходимых параметров
        $curl = curl_init();

        $post_data = [
            "grant_type"    => "authorization_code",
            "client_id"     => $this->client_ID,
            "client_secret" => $this->client_secret,
            "redirect_uri"  => "http://l9971350.beget.tech/wp-admin/admin-ajax.php?action=callback_URL",
            "code"          => $code
        ];

        curl_setopt($curl, CURLOPT_URL, $query);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);

        $result = curl_exec($curl);
        $result = json_decode($result);
        curl_close($curl);

        session_start();
        $_SESSION['access_token']  = $result->access_token;
        $_SESSION['refresh_token'] = $result->refresh_token;

        header('Location: ' . $this->redirect_URI . 'account');
        exit();

        wp_die();
    }
}