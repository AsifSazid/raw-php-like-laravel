<?php
class CreateOrdersTable
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function up()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id VARCHAR(100) NOT NULL,
            customer_name VARCHAR(100) NOT NULL,
            product_id INT NOT NULL,
            qty INT NOT NULL,
            total_price DECIMAL(10,2) NOT NULL,

            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

            CONSTRAINT fk_orders_products FOREIGN KEY (product_id) 
                REFERENCES products(id) 
                ON DELETE CASCADE 
                ON UPDATE CASCADE

        ) ENGINE=InnoDB;
        ";
        $this->pdo->exec($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS orders;";
        $this->pdo->exec($sql);
    }
}
