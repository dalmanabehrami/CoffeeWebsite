<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../vendor/autoload.php';

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverWait;
use Facebook\WebDriver\WebDriverExpectedCondition;

$host = 'http://localhost:9515'; // ChromeDriver URL

$options = new ChromeOptions();
$capabilities = DesiredCapabilities::chrome();
$capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

try {
    echo "🚀 Filloi testi i logout-only...\n";

    $driver = RemoteWebDriver::create($host, $capabilities);
    echo "✅ ChromeDriver u lidh me sukses\n";

    $wait = new WebDriverWait($driver, 10);

    // 1️⃣ Shko direkt te logout.php
    $driver->get('http://localhost/UEB25_CoffeeWebsite_/admin/logout.php');

    // Pris për redirect në homepage (index.php)
    $wait->until(WebDriverExpectedCondition::urlContains('index.php'));
    $currentUrl = $driver->getCurrentURL();

    if (strpos($currentUrl, 'index.php') !== false) {
        echo "✅ Logout funksionoi: Përdoruesi ridrejtohet te index.php\n";
    } else {
        echo "❌ Logout dështoi: URL aktuale = $currentUrl\n";
    }

    $driver->quit();
    echo "🛑 Testi u përfundua\n";

} catch (Exception $e) {
    echo "⚠️ Gabim gjatë testimit: " . $e->getMessage() . "\n";
    echo "📝 Trace:\n" . $e->getTraceAsString() . "\n";
}
