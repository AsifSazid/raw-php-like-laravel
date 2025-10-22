<?php
require_once __DIR__ . '/../core/Controller.php';

class ProductController extends Controller {
    private $product;

    public function __construct() {
        $this->product = $this->model('Product');
    }

    // GET /api/products
    public function index() {
        $products = $this->product->getAll();
        $this->response(['data' => $products]);
    }

    // GET /api/products/{id}
    public function show($id) {
        $product = $this->product->find($id);
        if ($product) {
            $this->response(['data' => $product]);
        } else {
            $this->response(['message' => 'Product not found'], 404);
        }
    }

    // POST /api/products
    public function store() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['name']) || !isset($input['price']) || !isset($input['stock']) ){
            $this->response(['message' => 'Invalid input'], 400);
        }
        if ($this->product->create($input['name'], $input['price'], $input['stock'])) {
            $this->response(['message' => 'Product created successfully'], 201);
        } else {
            $this->response(['message' => 'Failed to create product'], 500);
        }
    }

    // PUT /api/products/{id}
    public function update($id) {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['name']) || !isset($input['price']) || !isset($input['stock'])) {
            $this->response(['message' => 'Invalid input'], 400);
        }
        if ($this->product->update($id, $input['name'], $input['email'])) {
            $this->response(['message' => 'Product updated']);
        } else {
            $this->response(['message' => 'Update failed'], 500);
        }
    }

    // DELETE /api/products/{id}
    public function destroy($id) {
        if ($this->product->delete($id)) {
            $this->response(['message' => 'Product deleted']);
        } else {
            $this->response(['message' => 'Delete failed'], 500);
        }
    }
}
