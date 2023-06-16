<?php

// Check PHP version
if (version_compare(phpversion(), '8.1.0', '<') == true) {
    die('PHP 8.1.0 or newer required');
}

// Required config file.
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/vendor/Arc/ArcSystem.php';

if (USE_DB == true) {
    require_once __DIR__ . '/vendor/Medoo/Medoo.php';
    require_once __DIR__ . '/vendor/Arc/ArcDataProvider.php';
}

if (USE_SECURITY == true && USE_DB == false) {
    Arc\ArcSystem::setError('Security module requires database to be enabled in config.', 200);
    
}

// Initilise Arc System.
Arc\ArcSystem::route();