<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../functions_crud/cartitems-functions.php';

class CartItemsTest extends TestCase {
    private $conn;

    protected function setUp(): void {
        $this->conn = new mysqli('localhost', 'root', '', 'test_db');
        if($this->conn->connect_error) die("Connection failed: ".$this->conn->connect_error);

        $this->conn->query("TRUNCATE TABLE cart_items");
    }

    protected function tearDown(): void {
        $this->conn->query("TRUNCATE TABLE cart_items");
        $this->conn->close();
    }

    public function testCreateCartItem() {
        $result = createCartItem($this->conn, 1, 3);
        $this->assertTrue($result['success']);
        $id = $result['id'];
        $this->assertIsInt($id);

        $item = getCartItemById($this->conn, $id);
        $this->assertEquals(1, $item['product_id']);
        $this->assertEquals(3, $item['quantity']);
    }

    public function testGetAllCartItems() {
        createCartItem($this->conn, 1, 2);
        createCartItem($this->conn, 2, 5);

        $items = getAllCartItems($this->conn);
        $this->assertCount(2, $items);
    }

    public function testGetCartItemById() {
        $result = createCartItem($this->conn, 1, 2);
        $id = $result['id'];

        $item = getCartItemById($this->conn, $id);
        $this->assertEquals(1, $item['product_id']);
        $this->assertEquals(2, $item['quantity']);
    }

    public function testUpdateCartItem() {
        $result = createCartItem($this->conn, 1, 2);
        $id = $result['id'];
        $updated = updateCartItem($this->conn, $id, 1, 10);
        $this->assertTrue($updated);

        $item = getCartItemById($this->conn, $id);
        $this->assertEquals(10, $item['quantity']);
    }

    public function testDeleteCartItem() {
        $result = createCartItem($this->conn, 1, 2);
        $id = $result['id'];
        $deleted = deleteCartItem($this->conn, $id);
        $this->assertTrue($deleted);

        $item = getCartItemById($this->conn, $id);
        $this->assertNull($item);
    }
}
?>

