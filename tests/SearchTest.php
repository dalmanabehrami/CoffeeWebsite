<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../admin/product_functions.php';

class SearchTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        $this->conn = new mysqli('localhost','root','','test_db');
        if ($this->conn->connect_error) die("Connection failed: ".$this->conn->connect_error);

        // Pastro tabelën e produkteve dhe fut disa produkte për test
        $this->conn->query("TRUNCATE TABLE products");
        $stmt = $this->conn->prepare("INSERT INTO products (name) VALUES (?)");
        $products = ["Americano", "Latte", "Cappuccino"];
        foreach ($products as $p) {
            $stmt->bind_param("s", $p);
            $stmt->execute();
        }
    }

    protected function tearDown(): void
    {
        $this->conn->query("TRUNCATE TABLE products");
        $this->conn->close();
    }

    public function testSearchProductFound()
    {
        $result = searchProduct($this->conn, "Americano");
        $this->assertContains("Americano", $result);
    }

    public function testSearchProductNotFound()
    {
        $result = searchProduct($this->conn, "NoProduct");
        $this->assertEmpty($result);
    }
}
?>


