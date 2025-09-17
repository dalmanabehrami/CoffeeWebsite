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

    // Hap faqen e produkteve
    $driver->get('http://localhost/UEB25_CoffeeWebsite_/products.php');
    echo "🌐 U hap faqja: products.php\n";

    $wait = new WebDriverWait($driver, 10);
    $productId = 1; // ID e produktit të testit

    // Pris pak që faqja të ngarkohet
    sleep(2);

    // Siguro që produkti është në viewport
    $driver->executeScript("
        const prod = document.querySelector('div.box[data-id=\"$productId\"]');
        if(prod) prod.scrollIntoView();
    ");

    // Kliko Add me JS
    $driver->executeScript("
        const btn = document.querySelector('div.box[data-id=\"$productId\"] button[onclick^=\"addToCart\"]');
        if(btn) btn.click();
    ");
    echo "➡️ Produkti me ID $productId u shtua në shportë\n";

    sleep(1); // prit pak për JS/AJAX

    // Kliko Remove me JS
    $driver->executeScript("
        const btn = document.querySelector('div.box[data-id=\"$productId\"] button[onclick^=\"deleteFromCartItems\"]');
        if(btn) btn.click();
    ");
    echo "➡️ Produkti me ID $productId u hoq nga shporta\n";

    $driver->quit();
    echo "🛑 Testimi përfundoi.\n";

} catch (Exception $e) {
    echo "⚠️ Gabim gjatë testimit: " . $e->getMessage() . "\n";
}
