<?php

// Application Settings
define('USE_DB', true);
define('USE_SECURITY', true); // Requires DB to be enabled.

// Debug - errors output system infomration
define('ENABLE_DEBUG', true);

// Database Settings - Only required for advanced features.
// Database type (MySQL, MariaDB, MSSQL, Sybase, PostgreSQL, Oracle, Sqlite)
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'arc2');
define('ADB_CHARSET', 'utf8');