<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../functions_crud/user-functions.php';

class UserTest extends TestCase {
    private $conn;

    protected function setUp(): void {
        $this->conn = new mysqli('localhost', 'root', '', 'test_db');
        if($this->conn->connect_error) die("Connection failed: ".$this->conn->connect_error);

        $this->conn->query("TRUNCATE TABLE users");
    }

    protected function tearDown(): void {
        $this->conn->query("TRUNCATE TABLE users");
        $this->conn->close();
    }

    public function testCreateUser() {
        $result = createUser($this->conn, "Dalmana", "dalmana@example.com", "123456", "admin");
        $this->assertTrue($result['success']);
        $id = $result['id'];
        $this->assertIsInt($id);

        $user = getUserById($this->conn, $id);
        $this->assertEquals("Dalmana", $user['name']);
        $this->assertEquals("dalmana@example.com", $user['email']);
        $this->assertEquals("admin", $user['role']);
    }

    public function testGetAllUsers() {
        createUser($this->conn, "Dalmana", "dalmana@example.com", "123456", "admin");
        createUser($this->conn, "Test User", "test@example.com", "123456", "user");

        $users = getAllUsers($this->conn);
        $this->assertCount(2, $users);
    }

    public function testUpdateUser() {
        $result = createUser($this->conn, "Dalmana", "dalmana@example.com", "123456", "admin");
        $id = $result['id'];
        $updated = updateUser($this->conn, $id, "Dalmana B", "dalmanaB@example.com", "user");
        $this->assertTrue($updated);

        $user = getUserById($this->conn, $id);
        $this->assertEquals("Dalmana B", $user['name']);
        $this->assertEquals("dalmanaB@example.com", $user['email']);
        $this->assertEquals("user", $user['role']);
    }

    public function testDeleteUser() {
        $result = createUser($this->conn, "Dalmana", "dalmana@example.com", "123456", "admin");
        $id = $result['id'];
        $deleted = deleteUser($this->conn, $id);
        $this->assertTrue($deleted);

        $user = getUserById($this->conn, $id);
        $this->assertNull($user);
    }
}
?>


