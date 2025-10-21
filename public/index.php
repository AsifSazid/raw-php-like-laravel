<?php
// Bootstrap
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Router.php';

// Parse URL
$url = isset($_GET['url']) ? explode('/', trim($_GET['url'], '/')) : [];
$method = $_SERVER['REQUEST_METHOD'];

// Let Router handle it
Router::handle($url, $method);
