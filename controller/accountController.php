<?php

namespace Arc\Controller;
use \Arc\ArcSystem as Arc;

class AccountController {

    static function index() {
        Arc::setRedirect('account/login');
    }

    static function login() {
        Arc::setTitle('Account Login');

        Arc::getModel('arcUser');
    }

    static function doLogin() {
        if (Arc::isAjaxRequest()) {

        }
    }

}