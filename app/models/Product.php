<?php
require_once __DIR__ . '/../core/Database.php';

class Product {
    private $conn;
    private $table = 'products';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT id, name, price, stock FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->conn->prepare("SELECT id, name, price, stock  FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $price, $stock) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (name, price, stock) VALUES (?, ?, ?)");
        return $stmt->execute([$name, $price, $stock]);
    }

    public function update($id, $name, $price, $stock) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET name=?, price=?, stock=? WHERE id=?");
        return $stmt->execute([$name, $price, $stock , $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id=?");
        return $stmt->execute([$id]);
    }
}
