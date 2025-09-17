<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../functions/register-functions.php';

class RegisterTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        $this->conn = new mysqli('localhost', 'root', '', 'test_db');
        if ($this->conn->connect_error) die("Connection failed: ".$this->conn->connect_error);

        // Pastro përdoruesit testues para çdo testi
        $this->conn->query("DELETE FROM users WHERE email IN (
            'testuser@example.com',
            'existing@example.com',
            'shortpass@example.com',
            'mismatch@example.com',
            'invalidemail'
        )");

        // Fut një email ekzistues për testin e ekzistimit
        $email = "existing@example.com";
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $password = password_hash("password123", PASSWORD_DEFAULT);
        $name = "Existing User";
        $stmt->bind_param("sss", $name, $email, $password);
        $stmt->execute();
    }

    protected function tearDown(): void
    {
        // Pastro të dhënat e testimit
        $this->conn->query("DELETE FROM users WHERE email IN (
            'testuser@example.com',
            'existing@example.com',
            'shortpass@example.com',
            'mismatch@example.com',
            'invalidemail'
        )");
        $this->conn->close();
    }

    public function testRegisterWithValidData()
    {
        $result = registerUser($this->conn, "Test User", "testuser@example.com", "password123", "password123");
        $this->assertTrue($result['success']);
    }

    public function testRegisterWithExistingEmail()
    {
        $result = registerUser($this->conn, "Test User", "existing@example.com", "password123", "password123");
        $this->assertEquals("This email is already registered!", $result['error']);
    }

    public function testRegisterWithShortPassword()
    {
        $result = registerUser($this->conn, "Test User", "shortpass@example.com", "123", "123");
        $this->assertEquals("Password must be at least 8 characters long!", $result['error']);
    }

    public function testRegisterWithMismatchedPassword()
    {
        $result = registerUser($this->conn, "Test User", "mismatch@example.com", "password123", "password321");
        $this->assertEquals("Passwords do not match!", $result['error']);
    }

    public function testRegisterWithInvalidEmail()
    {
        $result = registerUser($this->conn, "Test User", "invalidemail", "password123", "password123");
        $this->assertEquals("Please enter a valid email!", $result['error']);
    }
}
?>

