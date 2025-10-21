<?php
class Router
{
    public static function handle($url, $method)
    {
        // Check if API route
        if (!isset($url[0]) || $url[0] !== 'api') {
            echo "<h2>Welcome to My Raw PHP API</h2>
                  <p>Try accessing <a href='/api/users'>/api/users</a></p>";
            exit;
        }

        $resource = $url[1] ?? null;
        $id = $url[2] ?? null;

        if (!$resource) {
            echo json_encode(['message' => 'No resource specified']);
            exit;
        }

        $controllerName = ucfirst(rtrim($resource, 's')) . 'Controller';
        $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';

        if (!file_exists($controllerFile)) {
            echo json_encode(['error' => "Controller for '$resource' not found"]);
            exit;
        }

        require_once $controllerFile;
        $controller = new $controllerName();

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
                else $controller->response(['message' => 'ID required'], 400);
                break;
            case 'DELETE':
                if ($id) $controller->destroy($id);
                else $controller->response(['message' => 'ID required'], 400);
                break;
            case 'OPTIONS':
                http_response_code(200);
                exit;
            default:
                $controller->response(['message' => 'Method not allowed'], 405);
        }
    }
}
