<?php

namespace Arc\Controller;
use \Arc\ArcSystem as Arc;

class AccountController {

    static function index() {
        Arc::setRedirect('account/login');
    }

    static function login() {
        if (isset($_SESSION['UserToken'])) {
            Arc::setRedirect('account/profile');
        }

        Arc::setTitle('Account Login');
        Arc::setFooter('<script src="' . Arc::getAsset('js/account/login.js', false, true) . '"></script>');

        Arc::render();
    }

    static function register() {
        if (isset($_SESSION['UserToken'])) {
            Arc::setRedirect('account/profile');
        }

        Arc::setTitle('Account Register');
        Arc::setFooter('<script src="' . Arc::getAsset('js/account/register.js', false, true) . '"></script>');

        Arc::render();
    }

    static function doLogin() {
        if (Arc::isRequestFromOrigin()) {
            Arc::getModel('user');
            Arc::getModel('log');

            $email = $_POST['email'];
            $password = $_POST['password'];

            $db = Arc::getDBConnection();
            $user = \Arc\Models\UserModel::getUserByEmail($email);

            if ($user == null) {
                echo json_encode(['error' => 'Invalid username and/or password.']);
                \Arc\Models\LogModel::log('Invalid username and/or password (' . $email . ').', 'WARN', 'LOGIN');
                return;
            }

            if (password_verify($password, $user['PasswordHash']) == false) {
                echo json_encode(['error' => 'Invalid username and/or password.']);
                \Arc\Models\LogModel::log('Invalid username and/or password (' . $email . ').', 'WARN', 'LOGIN');
                return;
            }

            \Arc\Models\UserModel::updateLastLogin($user['ID']);
            $token = \Arc\Models\UserModel::updateUserToken($user['ID']);

            $_SESSION['UserToken'] = $token;
                      
            \Arc\Models\LogModel::log('User logged in \'' . $email . '\'', 'INFO', 'LOGIN');
            echo json_encode(['message' => 'Login successful.']);
        } else {
            echo json_encode(['error' => 'Access denied']);
        }
    }

    static function doRegister() {
        if (Arc::isRequestFromOrigin()) {
            Arc::getModel('user');

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
            
            echo json_encode(['message' => 'Account created, you may now <a href="login">login</a>.']);
        } else {
            echo json_encode(['error' => 'Access denied']);
        }
    }

    static function profile() {
        if (!isset($_SESSION['UserToken'])) {
            Arc::setRedirect('account/login');
        }

        Arc::getModel('user');

        $user = \Arc\Models\UserModel::getUserByToken($_SESSION['UserToken']);
        if ($user == null) {
            Arc::setError('Session has expired.', 401);
        }

        Arc::setData('user', $user);
        Arc::setTitle('Account Profile');
        Arc::setFooter('<script src="' . Arc::getAsset('js/account/profile.js', false, true) . '"></script>');
        
        Arc::render();
    }

    static function doprofile() {
        if (Arc::isRequestFromOrigin()) {


            echo json_encode(['message' => 'test']);
        }
    }
}