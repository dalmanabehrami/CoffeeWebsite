<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../vendor/autoload.php';

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverWait;

$host = 'http://localhost:9515';
$options = new ChromeOptions();
$options->addArguments(['--start-maximized']);
$capabilities = DesiredCapabilities::chrome();
$capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

try {
    $driver = RemoteWebDriver::create($host, $capabilities);
    $wait = new WebDriverWait($driver, 10);

    echo "🚀 Filloi testimi i regjistrimit dhe reset password...\n";

    // ===== REGISTER USER =====
    $driver->get('http://localhost/UEB25_CoffeeWebsite_/admin/register.php');

    $driver->findElement(WebDriverBy::name('name'))->sendKeys('Test User');
    $email = 'test' . time() . '@test.com';
    $driver->findElement(WebDriverBy::name('email'))->sendKeys($email);
    $driver->findElement(WebDriverBy::name('password'))->sendKeys('test1234');
    $driver->findElement(WebDriverBy::name('confirm_password'))->sendKeys('test1234');

    $registerBtn = $wait->until(
        WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::className('login-btn'))
    );
    $driver->executeScript("arguments[0].scrollIntoView(true); arguments[0].click();", [$registerBtn]);
    $wait->until(WebDriverExpectedCondition::urlContains('login.php'));

    echo "✅ Regjistrimi u krye me sukses: $email\n";

    // ===== SET RESET TOKEN + SESSION =====
    $resetCode = rand(1000, 9999); // kodi unik 4-shifror çdo herë

    // Lidhu me DB – siguro që emri DB ekziston!
    $mysqli = new mysqli('localhost', 'root', '', 'coffee_shope'); 
    if ($mysqli->connect_error) {
        throw new Exception('DB connection failed: ' . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare(
        "UPDATE users SET reset_token_hash=?, reset_token_expires_at=DATE_ADD(NOW(), INTERVAL 10 MINUTE) WHERE email=?"
    );
    $stmt->bind_param('ss', $resetCode, $email);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();

    // Vendos session për test
    $driver->get("http://localhost/UEB25_CoffeeWebsite_/includes/set_reset_session.php?email=$email");
    echo "🔹 Session reset_email vendosur për user: $email\n";

    // ===== RESET PASSWORD =====
    $driver->get("http://localhost/UEB25_CoffeeWebsite_/auth/reset-password.php");

    $resetCodeInput = $wait->until(
        WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath("//input[@name='reset_code']"))
    );
    $passwordInput = $driver->findElement(WebDriverBy::xpath("//input[@name='password']"));
    $confirmPasswordInput = $driver->findElement(WebDriverBy::xpath("//input[@name='confirm_password']"));
    $submitButton = $driver->findElement(WebDriverBy::xpath("//button[text()='Reset Password']"));

    $resetCodeInput->sendKeys($resetCode);
    $passwordInput->sendKeys('NewPass123');
    $confirmPasswordInput->sendKeys('NewPass123');
    $submitButton->click();

    $wait->until(WebDriverExpectedCondition::urlContains('login.php'));
    $successMsg = $driver->findElement(WebDriverBy::cssSelector('.success'))->getText();
    echo "✅ Mesazhi i suksesit pas reset: $successMsg\n";

    $driver->quit();
    echo "🛑 Driver u mbyll\n";

} catch (Exception $e) {
    echo "⚠️ Gabim gjatë testimit: " . $e->getMessage() . "\n";
    echo "📝 Trace:\n" . $e->getTraceAsString() . "\n";
}
