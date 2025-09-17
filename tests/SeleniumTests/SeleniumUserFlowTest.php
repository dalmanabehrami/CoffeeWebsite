<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverWait;

$host = 'http://localhost:9515'; // porti i ChromeDriver

$options = new ChromeOptions();
$capabilities = DesiredCapabilities::chrome();
$capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

try {
    echo "🚀 Filloi testi i përdoruesit...\n";

    $driver = RemoteWebDriver::create($host, $capabilities);
    $wait = new WebDriverWait($driver, 10);

    // ========================
    // 1️⃣ Testi i Regjistrimit
    // ========================
    $driver->get('http://localhost/UEB25_CoffeeWebsite_/admin/register.php');
    echo "🌐 U hap register.php\n";

    $testEmail = 'test' . time() . '@test.com';

    $driver->findElement(WebDriverBy::name('name'))->sendKeys('Test User');
    $driver->findElement(WebDriverBy::name('email'))->sendKeys($testEmail);
    $driver->findElement(WebDriverBy::name('password'))->sendKeys('test1234');
    $driver->findElement(WebDriverBy::name('confirm_password'))->sendKeys('test1234');

    // Kliko butonin register me scroll + wait
    $button = $driver->findElement(WebDriverBy::className('login-btn'));
    $driver->executeScript("arguments[0].scrollIntoView(true);", [$button]);
    $wait->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::className('login-btn')));
    $button->click();

    // Pris redirect tek login.php
    $wait->until(WebDriverExpectedCondition::urlContains('login.php'));
    $currentUrl = $driver->getCurrentURL();
    if (strpos($currentUrl, 'login.php') !== false) {
        echo "✅ Regjistrimi i suksesshëm!\n";
    } else {
        echo "❌ Regjistrimi dështoi!\n";
    }

    // ==============================
    // 2️⃣ Testi i Reset Password
    // ==============================
    $driver->get('http://localhost/UEB25_CoffeeWebsite_/admin/forgot-password.php');
    echo "🌐 U hap forgot-password.php\n";

    $driver->findElement(WebDriverBy::name('email'))->sendKeys($testEmail);

    // Kliko butonin me scroll + wait
    $button = $driver->findElement(WebDriverBy::className('login-btn'));
    $driver->executeScript("arguments[0].scrollIntoView(true);", [$button]);
    $wait->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::className('login-btn')));
    $button->click();

    // Prit pak për ridrejtim tek reset-password
    sleep(2);
    $driver->get('http://localhost/UEB25_CoffeeWebsite_/admin/reset-password.php');

    // Përdor kod test manual (ose nga db)
    $resetCode = '1234'; // ndrysho sipas kodit që krijon sistemi
    $newPassword = 'newpassword123';

    $driver->findElement(WebDriverBy::name('reset_code'))->sendKeys($resetCode);
    $driver->findElement(WebDriverBy::name('password'))->sendKeys($newPassword);
    $driver->findElement(WebDriverBy::name('confirm_password'))->sendKeys($newPassword);

    // Kliko butonin reset password
    $button = $driver->findElement(WebDriverBy::className('login-btn'));
    $driver->executeScript("arguments[0].scrollIntoView(true);", [$button]);
    $wait->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::className('login-btn')));
    $button->click();

    sleep(2);
    $currentUrl = $driver->getCurrentURL();
    if (strpos($currentUrl, 'login.php') !== false) {
        echo "✅ Reset Password u krye me sukses!\n";
    } else {
        echo "❌ Reset Password dështoi!\n";
    }

    $driver->quit();
    echo "🛑 Testi përfundoi dhe driver u mbyll.\n";

} catch (Exception $e) {
    echo "⚠️ Gabim gjatë testimit: " . $e->getMessage() . "\n";
    echo "📝 Trace:\n" . $e->getTraceAsString() . "\n";
}
