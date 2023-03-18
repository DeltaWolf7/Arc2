<?php

namespace Arc\Controller;

class IndexController {

    static function index() {
        \Arc\ArcSystem::setTitle('This is the index page.');
    }

    static function error() {
        
    }

}