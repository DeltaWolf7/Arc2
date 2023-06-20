<?php

// Application Settings
define('USE_DB', true);
define('USE_SECURITY', true); // Requires DB to be enabled.

// Debug - errors output system infomration
// WARNING!!! Do not enable this setting on production servers, this will cause security issues.
define('ENABLE_DEBUG', false);

// Database Settings - Only required for advanced features.
// Database type (MySQL, MariaDB, MSSQL, Sybase, PostgreSQL, Oracle, Sqlite)
// https://medoo.in/api/new
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'password');
define('DB_NAME', 'arc2');
define('ADB_CHARSET', 'utf8');