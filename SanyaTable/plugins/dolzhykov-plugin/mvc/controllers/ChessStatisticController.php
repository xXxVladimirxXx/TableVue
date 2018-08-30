<?php
namespace Chess\Statistic;

class ChessStatisticController {

    protected $endpoint = 'https://lichess.org';

    protected $token;

    public $plugin;

    public function __construct($plugin) {

        $this->plugin = $plugin;

        $auth = new LichessApiController('Nb6HRZxU9hfz1taH', 'X3QkTivIfMAirwpZtpJ4ttWO86IYeOmG');

        add_action('wp_ajax_get_profile', [$this, 'get_profile']);
        add_action('wp_ajax_nopriv_get_profile', [$this, 'get_profile']);
        add_action('wp_ajax_get_games', [$this, 'get_games']);
        add_action('wp_ajax_nopriv_get_games', [$this, 'get_games']);
    }

    public function get_games() {
        // http://l9971350.beget.tech/wp-admin/admin-ajax.php?action=get_games

        $token = $_SESSION['access_token'];
        $user_name = json_decode(file_get_contents('php://input'));

        $query = $this->endpoint . '/api/games/user/' . $user_name->username;

        $curl = curl_init();

        $data_for_user = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        ];

        curl_setopt($curl, CURLOPT_URL, $query);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $data_for_user);

        $data_user = curl_exec($curl);
        curl_close($curl);

        wp_send_json($data_user);
    }

    public function get_profile() {
        // http://l9971350.beget.tech/wp-admin/admin-ajax.php?action=get_profile

        $token = $_SESSION['access_token'];

        $query = $this->endpoint . '/api/account';

        $curl = curl_init();

        $data_for_user = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        ];

        curl_setopt($curl, CURLOPT_URL, $query);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $data_for_user);

        $data_user = curl_exec($curl);
        curl_close($curl);

        wp_send_json($data_user);
    }
}