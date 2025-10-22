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

        // Share same PDO across both models
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

        // Validate input except order_id (auto-generated)
        $required = ['customer_name', 'product_id', 'qty', 'total_price'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                return $this->response(['message' => "Missing field: {$field}"], 400);
            }
        }

        // Load product model if not loaded globally
        require_once __DIR__ . '/../models/Product.php';
        $this->productModel = new Product();

        // Fetch product
        $product = $this->productModel->find($input['product_id']);
        if (!$product) {
            return $this->response(['message' => 'Product not found'], 404);
        }

        // Check stock availability
        if ($product['stock'] < $input['qty']) {
            return $this->response([
                'message' => 'Insufficient stock',
                'available_stock' => $product['stock']
            ], 400);
        }

        try {
            $this->order->beginTransaction();

            // 🔹 Generate new sequential order ID
            $lastOrder = $this->order->getLastOrder();
            if ($lastOrder && preg_match('/ORD-(\d+)/', $lastOrder['order_id'], $matches)) {
                $nextNumber = intval($matches[1]) + 1;
            } else {
                $nextNumber = 1001; // starting number if table empty
            }
            $newOrderId = "ORD-" . $nextNumber;

            // 🔹 Create new order
            $this->order->create(
                $newOrderId,
                $input['customer_name'],
                $input['product_id'],
                $input['qty'],
                $input['total_price']
            );

            // 🔹 Update product stock
            $newStock = $product['stock'] - $input['qty'];
            $this->productModel->update(
                $product['id'],
                $product['name'],
                $product['price'],
                $newStock
            );

            $this->order->commit();
            return $this->response([
                'message' => 'Order created successfully',
                'order_id' => $newOrderId
            ], 201);
        } catch (Exception $e) {
            $this->order->rollBack();
            return $this->response([
                'message' => 'Transaction failed',
                'error' => $e->getMessage()
            ], 500);
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
