<?php

namespace Arc\Controller;
use \Arc\ArcSystem as Arc;

class IndexController {

    static function index() {
        Arc::setTitle('This is the index page.');
    }

    static function error() {
        
    }

}