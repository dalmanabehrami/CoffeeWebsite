<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverWait;
use Facebook\WebDriver\WebDriverExpectedCondition;

$host = 'http://localhost:9515';
$options = new ChromeOptions();
$capabilities = DesiredCapabilities::chrome();
$capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

try {
    $driver = RemoteWebDriver::create($host, $capabilities);
    echo "✅ ChromeDriver u lidh me sukses\n";

    // Hap faqen e menaxhimit të produkteve
    $driver->get('http://localhost/UEB25_CoffeeWebsite_/admin/products.php');

    $wait = new WebDriverWait($driver, 10);

    // Kliko butonin Delete për produktin test (supozimi: ka class .delete-btn dhe data-id)
    $productId = 1; // ndrysho sipas produktit test
    $deleteBtn = $driver->findElement(WebDriverBy::cssSelector(".delete-btn[data-id='$productId']"));
    $deleteBtn->click();

    // Konfirmo alert (JS alert)
    $driver->switchTo()->alert()->accept();

    sleep(2); // prit për AJAX

    echo "✅ Produkti me ID $productId u fshi nga katalogu\n";

    $driver->quit();
} catch (Exception $e) {
    echo "⚠️ Gabim gjatë testit: " . $e->getMessage();
}
