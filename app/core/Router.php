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

        $resource = $url[1] ?? null; // e.g. 'orders'
        $param1 = $url[2] ?? null;   // could be 'product' or ID
        $param2 = $url[3] ?? null;   // could be product_id

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
                // 🔹 Custom route: /api/orders/product/{id}
                if ($resource === 'orders' && $param1 === 'product' && $param2) {
                    $controller->getByProduct($param2);
                }
                // 🔹 Normal single resource: /api/orders/{id}
                elseif ($param1) {
                    $controller->show($param1);
                }
                // 🔹 List all: /api/orders
                else {
                    $controller->index();
                }
                break;

            case 'POST':
                $controller->store();
                break;

            case 'PUT':
            case 'PATCH':
                if ($param1) $controller->update($param1);
                else $controller->response(['message' => 'ID required'], 400);
                break;

            case 'DELETE':
                if ($param1) $controller->destroy($param1);
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
