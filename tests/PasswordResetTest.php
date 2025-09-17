<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../functions/password-functions.php';

class PasswordResetTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        $this->conn = new mysqli("localhost", "root", "", "test_db");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        // Shto një user testues në DB
        $email = "test@example.com";
        $stmt = $this->conn->prepare("INSERT IGNORE INTO users (email) VALUES (?)");
        $stmt->bind_param("s", $email);
        $stmt->execute();
    }

    protected function tearDown(): void
    {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE email=?");
        $email = "test@example.com";
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $this->conn->close();
    }

    public function testResetPasswordEmailSent()
    {
        $email = "test@example.com";
        $result = sendResetCode($this->conn, $email);

        $this->assertEquals(true, $result['success']);
        $this->assertEquals("Reset code sent", $result['message']);
    }

    public function testResetPasswordInvalidEmail()
    {
        $email = "noone@example.com";
        $result = sendResetCode($this->conn, $email);

        $this->assertEquals(false, $result['success']);
        $this->assertEquals("Email not registered", $result['message']);
    }
}
?>

