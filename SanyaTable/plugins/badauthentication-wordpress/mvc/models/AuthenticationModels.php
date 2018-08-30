<?php

namespace wp\authentication;

class AuthenticationModels{

    public function authentication_user($login, $password, $rememberme) {

        $user_log = array();
        $user_log['user_login'] = $login;
        $user_log['user_password'] = $password;
        $user_log['remember'] = $rememberme;

        $user = wp_signon( $user_log, false );

        if ( is_wp_error($user) ) {
            echo '404';
        } else {
            echo '200';
        }
    }
}
?>