<?php

namespace Arc\Controller;
use \Arc\ArcSystem as Arc;

class AccountController {

    static function index() {
        Arc::setRedirect('account/login');
    }

    static function login() {
        Arc::setTitle('Account Login');
        Arc::setFooter('<script src="' . Arc::getAsset('js/account.js', false, true) . '"></script>');

        Arc::render();
    }

    static function doLogin() {
        if (Arc::isRequestFromOrigin()) {

            $email = $_POST['email'];
            $password = $_POST['password'];

            $db = Arc::getDBConnection();
            $user = $db->get('arc_users',
             ['ID', 'Firstname', 'Lastname', 'EmailAddress', 'PasswordHash', 'LastLogin', 'AccountCreated'],
             ['EmailAddress' => $email]);

            if ($user == null) {
                echo json_encode(['error' => 'Invalid username and/or password.']);
                return;
            }
            
//password_hash($_POST['password'], PASSWORD_DEFAULT)
            
            echo json_encode($user);
        } else {
            echo json_encode(['error' => 'Access denied']);
        }
    }

}