<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../functions_crud/product-functions.php';

class ProductTest extends TestCase {
    private $conn;

    protected function setUp(): void {
        // Lidhu me databazën test
        $this->conn = new mysqli('localhost', 'root', '', 'test_db');
        if ($this->conn->connect_error) die("Connection failed: " . $this->conn->connect_error);

        // Pastro tabelën products para çdo testi
        $this->conn->query("TRUNCATE TABLE products");
    }

    protected function tearDown(): void {
        // Pastro tabelën products pas çdo testi
        $this->conn->query("TRUNCATE TABLE products");
        $this->conn->close();
    }

    public function testCreateProduct() {
        $result = createProduct($this->conn, "Coffee Mug", "White ceramic mug", 12.50, "mug.jpg");
        $this->assertTrue($result['success']);
        $id = $result['id'];
        $this->assertIsInt($id);

        $product = getProductById($this->conn, $id);
        $this->assertEquals("Coffee Mug", $product['name']);
        $this->assertEquals("White ceramic mug", $product['description']);
        $this->assertEquals(12.50, (float)$product['price']);
        $this->assertEquals("mug.jpg", $product['image']);
    }

    public function testGetAllProducts() {
        createProduct($this->conn, "Coffee Mug", "White ceramic mug", 12.50, "mug.jpg");
        createProduct($this->conn, "Tea Cup", "Glass cup", 8.00, "tea.jpg");

        $products = getAllProducts($this->conn);
        $this->assertCount(2, $products);
        $this->assertEquals("Tea Cup", $products[0]['name']); // ORDER BY id DESC
        $this->assertEquals("Coffee Mug", $products[1]['name']);
    }

    public function testUpdateProduct() {
        $result = createProduct($this->conn, "Coffee Mug", "White ceramic mug", 12.50, "mug.jpg");
        $id = $result['id'];
        $updated = updateProduct($this->conn, $id, "Coffee Mug XL", "Bigger mug", 15.00, "new_mug.jpg");
        $this->assertTrue($updated);

        $product = getProductById($this->conn, $id);
        $this->assertEquals("Coffee Mug XL", $product['name']);
        $this->assertEquals("Bigger mug", $product['description']);
        $this->assertEquals(15.00, (float)$product['price']);
        $this->assertEquals("new_mug.jpg", $product['image']);
    }

    public function testDeleteProduct() {
        $result = createProduct($this->conn, "Coffee Mug", "White ceramic mug", 12.50, "mug.jpg");
        $id = $result['id'];
        $deleted = deleteProduct($this->conn, $id);
        $this->assertTrue($deleted);

        $product = getProductById($this->conn, $id);
        $this->assertNull($product);
    }
}
?>



