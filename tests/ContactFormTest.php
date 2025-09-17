<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../functions/contact-functions.php';

class ContactFormTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        $this->conn = new mysqli('localhost','root','','test_db');
        if ($this->conn->connect_error) die("Connection failed: ".$this->conn->connect_error);

        // Pastro tabelën para çdo testi
        $this->conn->query("TRUNCATE TABLE contact_messages");
    }

    protected function tearDown(): void
    {
        $this->conn->query("TRUNCATE TABLE contact_messages");
        $this->conn->close();
    }

    public function testSendContactFormSuccess()
    {
        $form = [
            "name" => "Dalmana",
            "email" => "dalmana@example.com",
            "subject" => "Hello",
            "message" => "Hello!"
        ];

        // Përdor $conn si parametër
        $result = sendContactMessage($this->conn, $form);
        $this->assertTrue($result['success']);

        // Kontrollo që është futur në databazë
        $res = $this->conn->query("SELECT * FROM contact_messages WHERE email='dalmana@example.com'");
        $this->assertEquals(1, $res->num_rows);
    }

    public function testSendContactFormEmptyFields()
    {
        $form = [
            "name" => "",
            "email" => "",
            "subject" => "",
            "message" => ""
        ];

        // Përdor $conn si parametër
        $result = sendContactMessage($this->conn, $form);
        $this->assertEquals("Please fill in all fields", $result['error']);
    }
}
?>
