<?php
class CreateProductsTable
{
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function up()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,

            name VARCHAR(100) NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            stock DECIMAL(10) NOT NULL,

            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;
        ";
        $this->pdo->exec($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS products;";
        $this->pdo->exec($sql);
    }
}