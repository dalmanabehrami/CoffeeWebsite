<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../functions/funfact-functions.php';

class FunFactsTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        $this->conn = new mysqli('localhost','root','','test_db');
        if ($this->conn->connect_error) die("Connection failed: ".$this->conn->connect_error);

        // Pastro tabelën dhe fut disa fakte për test
        $this->conn->query("TRUNCATE TABLE fun_facts");
        $stmt = $this->conn->prepare("INSERT INTO fun_facts (fact) VALUES (?)");

        $facts = [
            "Coffee was first discovered in Ethiopia.",
            "Espresso has less caffeine than regular coffee.",
            "Finland is the biggest coffee consumer per capita."
        ];

        foreach ($facts as $fact) {
            $stmt->bind_param("s", $fact);
            $stmt->execute();
        }
    }

    protected function tearDown(): void
    {
        $this->conn->query("TRUNCATE TABLE fun_facts");
        $this->conn->close();
    }

    public function testFunFactDisplayed()
    {
        // Thërrasim funksionin me $conn
        $fact = getFunFact($this->conn);

        $this->assertNotEmpty($fact, "Fun fact should not be empty");
        $this->assertIsString($fact, "Fun fact should be a string");
    }
}
?>
