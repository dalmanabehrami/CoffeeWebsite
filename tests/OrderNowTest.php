<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../functions/order-functions.php';

class OrderNowTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        // Lidhja me databazën e testimit
        $this->conn = new mysqli('localhost','root','','test_db');
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        // Pastro tabelën e porosive para çdo testi
        $this->conn->query("TRUNCATE TABLE orders");
    }

    protected function tearDown(): void
    {
        // Pastro tabelën pas çdo testi dhe mbyll lidhjen
        $this->conn->query("TRUNCATE TABLE orders");
        $this->conn->close();
    }

    public function testOrderPlacement()
    {
        $order = [
            "user_id" => 1,
            "product_id" => 1,
            "quantity" => 2
        ];

        $result = placeOrder($this->conn, $order);
        $this->assertTrue($result['success']);
        $this->assertEquals('Order placed successfully', $result['message']);

        // Kontrollo që është futur në databazë
        $res = $this->conn->query("SELECT * FROM orders WHERE user_id=1 AND product_id=1");
        $this->assertEquals(1, $res->num_rows);
    }

    public function testInvalidOrder()
    {
        $order = [
            "user_id" => 1,
            "product_id" => 1,
            "quantity" => 0
        ];

        $result = placeOrder($this->conn, $order);
        $this->assertFalse($result['success']);
        $this->assertEquals('Invalid order', $result['message']);
    }
}
?>



