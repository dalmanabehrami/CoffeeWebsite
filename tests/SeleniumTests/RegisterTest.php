<?php
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
    echo "🚀 Filloi testimi i regjistrimit...\n";

    $driver = RemoteWebDriver::create($host, $capabilities);
    $wait = new WebDriverWait($driver, 20); // pritje më e gjatë

    // ===== REGISTER TEST =====
    $driver->get('http://localhost/UEB25_CoffeeWebsite_/admin/register.php');
    echo "🌐 U hap faqja: register.php\n";

    $driver->findElement(WebDriverBy::name('name'))->sendKeys('Test User');

    // Email unik
    $email = 'test' . time() . '@test.com';
    $driver->findElement(WebDriverBy::name('email'))->sendKeys($email);

    $driver->findElement(WebDriverBy::name('password'))->sendKeys('test1234');
    $driver->findElement(WebDriverBy::name('confirm_password'))->sendKeys('test1234');

    // Zgjidh rolin për test
    $role = 'user'; // mund të bësh 'admin' po dëshiron
    $select = $driver->findElement(WebDriverBy::name('role'));
    $select->findElement(WebDriverBy::cssSelector("option[value='$role']"))->click();

    echo "📝 Fushat u plotësuan\n";

    // Submit form
    $form = $driver->findElement(WebDriverBy::tagName('form'));
    $form->submit();
    echo "➡️ Form u submit-ua\n";

    // Pris redirect sipas rolit
    $expectedRedirect = $role === 'admin' ? 'admin/dashboard.php' : 'index.php';
    $wait->until(WebDriverExpectedCondition::urlContains($expectedRedirect));

    $currentUrl = $driver->getCurrentURL();
    echo "🔗 URL pas register: $currentUrl\n";

    if (strpos($currentUrl, $expectedRedirect) !== false) {
        echo "✅ Testi i regjistrimit kaloi me sukses!\n";
    } else {
        echo "❌ Testi dështoi: Nuk u bë redirect si duhet\n";
    }

    $driver->quit();
    echo "🛑 Driver u mbyll\n";

} catch (Exception $e) {
    echo "⚠️ Gabim gjatë testimit: " . $e->getMessage() . "\n";
    echo "📝 Trace:\n" . $e->getTraceAsString() . "\n";
}
