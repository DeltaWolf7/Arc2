<?php

namespace Arc;

class ArcSystem
{
    private static $systemData = [
        'version' => '2.0.0.0',
        'title' => 'Arc System',
        'controller' => 'index',
        'view' => 'index',
        'error' => '',
        'errorCode' => '',
        'pathParts' => [],
        'redirect' => '',
    ];

    private static $errorMessages = [
        '404' => 'The page you are looking for cannot be found.',
        '401' => 'Authentication is required.',
        '403' => 'Forbidden.',
    ];

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
        $url = strtok(self::getPath(), '?');
        return explode('/', $url);
    }

     // Set site title.
    static function setTitle($title)
    {
        self::$systemData['title'] = $title;
    }

    // Get page title.
    static function getTitle()
    {
        echo self::$systemData['title'];
    }

    // Get route.
    static function route() {
        self::$systemData['pathParts'] = self::getPathArray();

         // Not the default route.
         if (self::getPath() !== '') {
            // Get view
            self::$systemData['controller'] = self::$systemData['pathParts'][0];
            if (count(self::$systemData['pathParts']) >= 2) {
                // We have a view in the path.
                self::$systemData['view'] = self::$systemData['pathParts'][1];
            }
        }

        // Check if we are using security
        if (USE_SECURITY == TRUE) {
            // if yes, then check the route against the user

        }

        // see if the controller file exists and load it.
        $controller = self::getDocRoot() . 'controller/' . self::$systemData['controller'] . 'Controller.php';

        if (file_exists($controller)) {
            require_once $controller;
            $controllerClass = 'Arc\Controller\\' . self::$systemData['controller'] . 'Controller';
            if (method_exists($controllerClass, self::$systemData['view'])) {
                // we have the view method also, so execute.
                $method = self::$systemData['view'];
                $controllerClass::$method();
            } else {
                self::setError('404');
            }
        } else {
            // no controller, error 404.
            self::setError('404');
        }

        self::render();
    }

    // Render site skeleton.
    private static function render()
    {
        // Get the default site template.
        self::getView('shared/template');
    }

    // Set error.
    static function setError($message, $code = '404') {
        http_response_code($code);
        self::$systemData['errorCode'] = $code;

        if (isset(self::$errorMessages[$message])) {
            self::$systemData['error'] = self::$errorMessages[$message];
        } else {
            self::$systemData['error'] = $message;
        } 
    }

    // Get error.
    static function getError() {
        if (ENABLE_DEBUG == true) {
            self::$systemData['error'] .= '<p><pre>' . var_export(self::$systemData, true) . '</pre></p>'
                . '<p><pre>' . var_export(get_defined_constants(true)['user'], true) . '</pre></p>';
        }
        echo self::$systemData['error'];
    }

    // Get error.
    static function getErrorCode() {
        echo self::$systemData['errorCode'];
    }

    // Get version.
    static function getVersion() {
        echo self::$systemData['version'];
    }

    // Get site content.
    static function getContent()
    {
        if (empty(self::$systemData['error'])) {
            // no error
            self::getView(self::$systemData['controller'] . '/' . self::$systemData['view']);
        } else {
            // error
            self::getView('shared/error');
        }
    }

    static function getAsset($path, $fileSystem = false) {
        switch ($fileSystem) {
            case false:
                echo self::getHost() . 'assets/' . $path;
                break;
            case true:
                echo self::getDocRoot() . 'assets/' . $path;
                break;
        }
    }

    static function getModel($name) {
        require_once(self::getDocRoot() . 'model/' . $name . 'Model.php');
    }

    static function isAjaxRequest() {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
            && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                return true;
        }
        self::setError('404');
    }

    static function setRedirect($location) {
        header('Location: ' . $location, false, 301);
        exit();
    }
}
