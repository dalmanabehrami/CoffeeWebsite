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

    $driver->get('http://localhost/UEB25_CoffeeWebsite_/order.php');
    echo "🌐 U hap faqja: order.php\n";

    $wait = new WebDriverWait($driver, 10);

    // Mbush formën
    $driver->findElement(WebDriverBy::id('name'))->sendKeys('Test User');
    $driver->findElement(WebDriverBy::id('email'))->sendKeys('test@example.com');
    $driver->findElement(WebDriverBy::id('address'))->sendKeys('Test Address');
    $driver->findElement(WebDriverBy::id('payment-method'))->sendKeys('Cash');

    // Zgjedh produktin e parë
    $firstOption = $driver->findElement(WebDriverBy::cssSelector('#product option:not([disabled])'));
    $firstOption->click();
    echo "➡️ Produkti i zgjedhur në dropdown\n";

    // Kliko Add to Cart
    $wait->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('btn-add-to-cart')));
    $addButton = $driver->findElement(WebDriverBy::id('btn-add-to-cart'));
    $driver->executeScript("arguments[0].click();", [$addButton]);
    echo "➡️ Produkti u shtua në shportë\n";

    // Prit që produkti të shfaqet në shportë
    $wait->until(
        WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('#cart-items .cart-item'))
    );

    // Zgjidh checkbox-in e terms & conditions
    $termsCheckbox = $driver->findElement(WebDriverBy::id('accept-terms'));
    if (!$termsCheckbox->isSelected()) {
        $driver->executeScript("arguments[0].click();", [$termsCheckbox]);
    }
    echo "✅ Kushtet u pranuan\n";

    // Scroll te butoni Place Order dhe prit që të jetë klikues
    $placeOrderLocator = WebDriverBy::cssSelector('form#order-form button[type="submit"]');
    $wait->until(WebDriverExpectedCondition::presenceOfElementLocated($placeOrderLocator));
    $placeOrderBtn = $driver->findElement($placeOrderLocator);
    $driver->executeScript("arguments[0].scrollIntoView({block: 'center'});", [$placeOrderBtn]);
    $wait->until(WebDriverExpectedCondition::elementToBeClickable($placeOrderLocator));

    // Kliko Place Order
    $driver->executeScript("arguments[0].click();", [$placeOrderBtn]);
    echo "✅ Porosia u procesua me sukses\n";

    $driver->quit();
} catch (Exception $e) {
    echo "⚠️ Gabim gjatë testimit: " . $e->getMessage() . "\n";
    echo "🛑 Testimi përfundoi.\n";
}
