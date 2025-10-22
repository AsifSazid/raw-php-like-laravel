<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Product.php';

class OrderController extends Controller
{
    private $order;
    private $productModel;

    public function __construct()
    {
        $database = new Database();
        $pdo = $database->connect();

        $this->order = new Order($pdo);
        $this->productModel = new Product($pdo);
    }

    // GET /api/orders
    public function index()
    {
        $orders = $this->order->getAll();
        $this->response(['data' => $orders]);
    }

    // GET /api/orders/{id}
    public function show($id)
    {
        $order = $this->order->find($id);
        if ($order) {
            $this->response(['data' => $order]);
        } else {
            $this->response(['message' => 'Order not found'], 404);
        }
    }

    // POST /api/orders
    public function store()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $required = ['customer_name', 'product_id', 'qty', 'total_price'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                return $this->response(['message' => "Missing field: {$field}"], 400);
            }
        }

        require_once __DIR__ . '/../models/Product.php';
        $this->productModel = new Product();

        $product = $this->productModel->find($input['product_id']);
        if (!$product) {
            return $this->response(['message' => 'Product not found'], 404);
        }

        if ($product['stock'] < $input['qty']) {
            return $this->response([
                'message' => 'Insufficient stock',
                'available_stock' => $product['stock']
            ], 400);
        }

        $lastOrder = $this->order->getLastOrder();
        if ($lastOrder && preg_match('/ORD-(\d+)/', $lastOrder['order_id'], $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1001;
        }
        $newOrderId = "ORD-" . $nextNumber;

        $this->order->create(
            $newOrderId,
            $input['customer_name'],
            $input['product_id'],
            $input['qty'],
            $input['total_price']
        );

        $newStock = $product['stock'] - $input['qty'];
        $this->productModel->update(
            $product['id'],
            $product['name'],
            $product['price'],
            $newStock
        );

        return $this->response([
            'message' => 'Order created successfully',
            'order_id' => $newOrderId
        ], 201);
    }

    // GET /api/orders/product/{product_id}
    public function getByProduct($productId)
    {
        $orders = $this->order->getByProductId($productId);

        if ($orders && count($orders) > 0) {
            return $this->response(['data' => $orders]);
        } else {
            return $this->response(['message' => 'No orders found for this product'], 404);
        }
    }

    // DELETE /api/orders/{id}
    public function destroy($id)
    {
        if ($this->order->delete($id)) {
            $this->response(['message' => 'Order deleted']);
        } else {
            $this->response(['message' => 'Delete failed'], 500);
        }
    }
}
