<?php
// Global headers for API responses
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Database configuration - update if Laragon MySQL differs
define('DB_HOST', 'localhost');
define('DB_NAME', 'my_raw_php_task');
define('DB_USER', 'root');
define('DB_PASS', '');
