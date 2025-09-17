<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../functions/cart-functions.php';

class CartTest extends TestCase
{
    private $conn;
    private $cart;

    protected function setUp(): void
    {
        $this->conn = new mysqli('localhost','root','','test_db');
        if ($this->conn->connect_error) die("Connection failed: ".$this->conn->connect_error);

        $this->cart = new Cart($this->conn);
        $this->cart->clearCart(); // pastro tabelën para çdo testi
    }

    protected function tearDown(): void
    {
        $this->cart->clearCart();
        $this->conn->close();
    }

    public function testAddProductToCart()
    {
        $result = $this->cart->addProduct(1, 2);
        $this->assertTrue($result);

        $items = $this->cart->getItems();
        $this->assertCount(1, $items);
        $this->assertEquals(1, $items[0]['product_id']);
        $this->assertEquals(2, $items[0]['quantity']);
    }

    public function testRemoveProductFromCart()
    {
        $this->cart->addProduct(1, 2);
        $this->cart->addProduct(2, 1);

        $result = $this->cart->removeProduct(1);
        $this->assertTrue($result);

        $items = $this->cart->getItems();
        $this->assertCount(1, $items);
        $this->assertEquals(2, $items[0]['product_id']);
    }
}
?>
