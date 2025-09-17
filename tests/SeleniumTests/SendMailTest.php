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

$driver = null;

try {
    echo "🚀 Filloi testimi i Login dhe Feedback...\n";

    $driver = RemoteWebDriver::create($host, $capabilities);
    $wait = new WebDriverWait($driver, 10);

    // ===== LOGIN =====
    $email = 'dalmana@gmail.com';
    $password_plain = 'dalmana1';

    $driver->get('http://localhost/UEB25_CoffeeWebsite_/admin/login.php');
    $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::name('email')));

    $driver->findElement(WebDriverBy::name('email'))->sendKeys($email);
    $driver->findElement(WebDriverBy::name('password'))->sendKeys($password_plain);
    $driver->findElement(WebDriverBy::className('login-btn'))->click();

    $wait->until(WebDriverExpectedCondition::urlContains('index.php'));
    echo "✅ Login i suksesshëm!\n";

    // ===== FEEDBACK =====
    $driver->get('http://localhost/UEB25_CoffeeWebsite_/contact.php');

    // Pris derisa feedback form të jetë i dukshëm
    $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('feedback-form')));

    // Shkruaj feedback
    $feedbackTextarea = $driver->findElement(WebDriverBy::name('custom_message'));
    $feedbackTextarea->clear();
    $feedbackTextarea->sendKeys("Ky është një feedback test nga Selenium.");

    // Kliko butonin submit
    $submitBtn = $driver->findElement(WebDriverBy::cssSelector('#feedback-form button[type="submit"]'));
    $driver->executeScript("arguments[0].click();", [$submitBtn]);

    // Pris që të shfaqet ndonjë mesazh në #feedback-response
    $wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('feedback-response')));

    $responseText = $driver->findElement(WebDriverBy::id('feedback-response'))->getText();
    echo "💬 Teksti i response: $responseText\n";

    if (trim($responseText) !== '') {
        echo "✅ Feedback u dërgua dhe u mor përgjigje!\n";
    } else {
        echo "❌ Feedback nuk u dërgua ose s’ka përgjigje!\n";
    }

    echo "\n🛑 Testi për Login dhe Feedback u përfundua.\n";

} catch (Exception $e) {
    echo "⚠️ Gabim gjatë testimit: " . $e->getMessage() . "\n";

} finally {
    if ($driver) {
        $driver->quit();
        echo "🛑 ChromeDriver mbyllur me sukses.\n";
    }
}
