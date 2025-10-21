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
        $stmt = $this->conn->prepare("SELECT id, name, email FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->conn->prepare("SELECT id, name, email FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $email) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (name, email) VALUES (?, ?)");
        return $stmt->execute([$name, $email]);
    }

    public function update($id, $name, $email) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET name=?, email=? WHERE id=?");
        return $stmt->execute([$name, $email, $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id=?");
        return $stmt->execute([$id]);
    }
}
