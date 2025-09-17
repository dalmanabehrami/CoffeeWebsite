<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../functions/login-functions.php';

class LoginTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        // Lidhja me databazën e testimit
        $this->conn = new mysqli('localhost', 'root', '', 'test_db');
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        // Shto një përdorues testues në databazë (nëse nuk ekziston)
        $email = "test@example.com";
        $password = password_hash("password123", PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("INSERT IGNORE INTO users (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
    }

    protected function tearDown(): void
    {
        // Fshij përdoruesin testues pas testimit
        $stmt = $this->conn->prepare("DELETE FROM users WHERE email=?");
        $email = "test@example.com";
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $this->conn->close();
    }

    public function testLoginSuccess()
    {
        $email = "test@example.com";
        $password = "password123";
        $result = loginUser($this->conn, $email, $password);
        $this->assertTrue($result['success']);
    }

    public function testLoginWrongPassword()
    {
        $email = "test@example.com";
        $password = "wrongpass";
        $result = loginUser($this->conn, $email, $password);
        $this->assertEquals("Your password is incorrect", $result['message']);
    }

    public function testLoginUnregisteredEmail()
    {
        $email = "noone@example.com";
        $password = "password123";
        $result = loginUser($this->conn, $email, $password);
        $this->assertEquals("Your password is incorrect", $result['message']);
    }
}
?>