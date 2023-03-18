<?php

namespace Arc;

class ArcSystem
{
    private static $version = '2.0.0.0';
    private static $title = 'Arc System';
    private static $controller = 'index';
    private static $view = 'index';
    private static $error = '';

    // Get current path.
    static function getPath()
    {
        return ltrim($_SERVER['REQUEST_URI'], '/');
    }

    // Get Host.
    static function getHost()
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";
    }

    // Get document root.
    static function getDocRoot()
    {
        return $_SERVER['DOCUMENT_ROOT'] . '/';
    }

    // Get View.
    private static function getView($name)
    {
        $view = self::getDocRoot() . 'view/' . $name . '.php';
        if (file_exists($view)) {
            require_once $view;
            return;
        }
        self::setError('Unable to locate view "' . $name . '" in "' . $view . '"');
    }

    // Get path as array.
    static function getPathArray()
    {
        return explode('/', self::getPath());
    }

     // Set site title.
    static function setTitle($title)
    {
        self::$title = $title;
    }

    // Get page title.
    static function getTitle()
    {
        echo self::$title;
    }

    // Get route.
    static function route() {
        $pathParts = self::getPathArray();

         // Not the default route.
         if (self::getPath() !== '') {
            // Get view
            self::$controller = $pathParts[0];
            if (count($pathParts) >= 2) {
                // We have a view in the path.
                self::$view = $pathParts[1];
            }
        }

        // see if the controller file exists and load it.
        $controller = self::getDocRoot() . 'controller/' . self::$controller . 'Controller.php';
        if (file_exists($controller)) {
            require_once $controller;
            $controllerClass = 'Arc\\Controller\\' . self::$controller . 'Controller';
            if (method_exists($controllerClass, self::$view)) {
                // we have the view method also, so execute.
                $method = self::$view;
                $controllerClass::$method();
            } else {
                self::setError('Function "' . self::$view . '" not found in "' . $controllerClass . '"');
            }
        } else {
            // no controller, error 404.
            self::setError('Unable to locate contoller name "' . self::$controller . 'Contoller.php".');
        }

        self::render();
    }

    // Render site skeleton.
    private static function render()
    {
        // Get the default site template.
        self::getView('shared/Template');
    }

    // Set error.
    static function setError($message, $code = '404') {
        http_response_code($code);
        self::$error = $code . ' ' . $message;
    }

    // Get error.
    static function getError() {
        echo self::$error;
    }

    // Get version.
    static function getVersion() {
        echo self::$version;
    }

    // Get site content.
    static function getContent()
    {
        if (empty(self::$error)) {
            // no error
            self::getView(self::$controller . '//' . self::$view);
        } else {
            // error
            self::getView('shared//error');
        }
    }

    static function getAsset($path, $fileSystem = false) {
        switch ($fileSystem) {
            case false:
                echo self::getHost() . 'assets//' . $path;
                break;
            case true:
                echo self::getDocRoot() . 'assets//' . $path;
                break;
        }
    }
}
