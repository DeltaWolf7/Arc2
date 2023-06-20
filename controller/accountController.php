<?php

namespace Arc\Controller;
use \Arc\ArcSystem as Arc;

class AccountController {

    static function index() {
        Arc::setRedirect('account/login');
    }

    static function login() {
        Arc::setTitle('Account Login');
        Arc::setFooter('<script src="' . Arc::getAsset('js/login.js', false, true) . '"></script>');

        Arc::render();
    }

    static function register() {
        Arc::setTitle('Account Register');
        Arc::setFooter('<script src="' . Arc::getAsset('js/register.js', false, true) . '"></script>');

        Arc::render();
    }

    static function doLogin() {
        if (Arc::isRequestFromOrigin()) {

            $email = $_POST['email'];
            $password = $_POST['password'];

            Arc::getModel('userModel');

            $db = Arc::getDBConnection();
            $user = \Arc\Models\UserModel::getUserByEmail($email);

            if ($user == null) {
                echo json_encode(['error' => 'Invalid username and/or password.']);
                return;
            }

            if (password_verify($password, $user['PasswordHash']) == false) {
                echo json_encode(['error' => 'Invalid username and/or password.']);
                return;
            }

            \Arc\Models\UserModel::updateLastLogin($user['ID']);
            $token = \Arc\Models\UserModel::updateUserToken($user['ID']);
            $_SESSION['UserToken'] = $token;
                      
            echo json_encode(['userid' => $user['ID']]);
        } else {
            echo json_encode(['error' => 'Access denied']);
        }
    }

    static function doRegister() {
        if (Arc::isRequestFromOrigin()) {

            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $db = Arc::getDBConnection();
            $user = $db->get('arc_users',
             ['ID', 'Firstname', 'Lastname', 'EmailAddress', 'PasswordHash', 'LastLogin', 'AccountCreated'],
             ['EmailAddress' => $email]);

            if ($user != null) {
                echo json_encode(['error' => 'Email address already registered.']);
                return;
            }

            $userid = \Arc\Models\UserModel::registerUser($firstname, $lastname, $email, $password);
            
            echo json_encode(['userid' => $userid]);
        } else {
            echo json_encode(['error' => 'Access denied']);
        }
    }
}