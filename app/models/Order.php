<?php
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/Product.php'; // for relationship

class Order
{
    private $conn;
    private $table = 'orders';

    public function __construct($pdo = null)
    {
        $this->conn = $pdo ?: (new Database())->connect();
    }

    public function getAll()
    {
        $sql = "
            SELECT 
                o.id, o.order_id, o.customer_name, o.qty, o.total_price, o.created_at,
                p.name AS product_name, p.price AS product_price
            FROM {$this->table} o
            JOIN products p ON o.product_id = p.id
            ORDER BY o.id DESC
        ";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $sql = "
            SELECT 
                o.*, p.name AS product_name, p.price AS product_price
            FROM {$this->table} o
            JOIN products p ON o.product_id = p.id
            WHERE o.id = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($orderId, $customerName, $productId, $qty, $totalPrice)
    {
        $sql = "INSERT INTO {$this->table} (order_id, customer_name, product_id, qty, total_price)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$orderId, $customerName, $productId, $qty, $totalPrice]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id=?");
        return $stmt->execute([$id]);
    }

    // Transaction controls
    public function beginTransaction()
    {
        $this->conn->beginTransaction();
    }
    public function commit()
    {
        $this->conn->commit();
    }
    public function rollBack()
    {
        $this->conn->rollBack();
    }

    public function getLastOrder()
    {
        $stmt = $this->conn->prepare("SELECT order_id FROM {$this->table} ORDER BY id DESC LIMIT 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
