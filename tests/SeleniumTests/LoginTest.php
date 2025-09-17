<?php
// Fik warnings deprecated për PHP 8.3+
error_reporting(E_ALL & ~E_DEPRECATED);
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
    echo "🚀 Po lidhem me ChromeDriver...\n";
    $driver = RemoteWebDriver::create($host, $capabilities);
    $wait = new WebDriverWait($driver, 10);

    // Vetëm një user për test
    $email = 'dalmana@gmail.com';
    $password = 'dalmana1';
    $expectedRedirect = 'dashboard.php';

    // Hapi faqen e login
    $driver->get('http://localhost/UEB25_CoffeeWebsite_/admin/login.php');
    echo "🔹 Login page u hap.\n";

    $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::name('email')));
    echo "🔹 Input email u gjet.\n";

    $driver->findElement(WebDriverBy::name('email'))->clear()->sendKeys($email);
    $driver->findElement(WebDriverBy::name('password'))->clear()->sendKeys($password);

    $driver->findElement(WebDriverBy::cssSelector('.login-btn'))->click();
    echo "🔹 Klikova butonin login.\n";

    // Shtoj sleep për debug
    sleep(1);

    // Pris redirect
    $wait->until(WebDriverExpectedCondition::urlContains($expectedRedirect));
    $currentUrl = $driver->getCurrentURL();
    echo "🔗 URL pas login: $currentUrl\n";

    if (strpos($currentUrl, $expectedRedirect) !== false) {
        echo "✅ Login i suksesshëm!\n";
    } else {
        echo "❌ Login dështoi!\n";
    }

    // Mos mbyllim logout për debug
    echo "💡 Debug mbaroi, mund të kontrollosh browserin.\n";

    $driver->quit();

} catch (Exception $e) {
    echo "⚠️ Gabim gjatë testimit: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
