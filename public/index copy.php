<?php
// Front controller for API
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Controller.php';

// Parse URL
$url = isset($_GET['url']) ? explode('/', trim($_GET['url'], '/')) : [];

$method = $_SERVER['REQUEST_METHOD'];

// Simple router for /api/* routes
if (isset($url[0]) && $url[0] === 'api') {
    $resource = $url[1] ?? null;
    $id = $url[2] ?? null;
    switch ($resource) {
        case 'users':
            require_once __DIR__ . '/../app/controllers/UserController.php';
            $controller = new UserController();

            switch ($method) {
                case 'GET':
                    if ($id) $controller->show($id);
                    else $controller->index();
                    break;
                case 'POST':
                    $controller->store();
                    break;
                case 'PUT':
                case 'PATCH':
                    if ($id) $controller->update($id);
                    else $controller->response(['message' => 'User ID required'], 400);
                    break;
                case 'DELETE':
                    if ($id) $controller->destroy($id);
                    else $controller->response(['message' => 'User ID required'], 400);
                    break;
                case 'OPTIONS':
                    // Preflight CORS
                    http_response_code(200);
                    exit;
                default:
                    $controller->response(['message' => 'Method not allowed'], 405);
            }
            break;

        default:
            echo json_encode(['message' => 'Resource not found']);
    }
} else {
    echo json_encode(['message' => 'Invalid API route. Example: /api/users']);
    
    // Default non-API route
    echo "<h2>Welcome to My Raw PHP API</h2>
          <p>Try accessing <a href='/api/users'>/api/users</a></p>";
}
