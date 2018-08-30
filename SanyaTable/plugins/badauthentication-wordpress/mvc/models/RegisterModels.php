<?php

namespace wp\authentication;

class RegisterModels{

    public function create_user($login, $password, $email) {

    $user_id = wp_create_user($login, $password, $email);

        if (is_wp_error($user_id)) {

            echo '404';

        } else {

            $user_log = array();
            $user_log['user_login'] = $login;
            $user_log['user_password'] = $password;
            $user_log['remember'] = true;

            wp_signon( $user_log, false );

            echo '200';
        }
    }
}