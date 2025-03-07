<?php 
// Environment Configuration
define('APP_ENV', 'development');
define('APP_NAME', 'PHP MVC');
define('BASE_URL', 'http://localhost');

if (APP_ENV === 'development') {
    // Enable strict error reporting during development
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Enable error logging in development
    ini_set('log_errors', 1);
} else {
    // Disable error display in production
    error_reporting(0);
    ini_set('display_errors', 0);
}

ini_set('error_log', __DIR__ . '/../logs/error.log');

// Start session with strict security
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
session_start();

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'mywebsite');

// Security Settings
define('CSRF_TOKEN_NAME', 'csrf_token');