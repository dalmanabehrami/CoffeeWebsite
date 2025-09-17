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

    // Hap faqen e kontaktit
    $driver->get('http://localhost/UEB25_CoffeeWebsite_/contact.php');
    echo "🌐 U hap faqja: contact.php\n";

    $wait = new WebDriverWait($driver, 10);

    // Plotëso fushat
    $driver->findElement(WebDriverBy::name('name'))->sendKeys('Test User');
    $driver->findElement(WebDriverBy::name('email'))->sendKeys('test'.time().'@test.com');
    $driver->findElement(WebDriverBy::name('age'))->sendKeys('30');
    $driver->findElement(WebDriverBy::name('phone'))->sendKeys('1234567890');
    $driver->findElement(WebDriverBy::name('subject'))->sendKeys('Testing Contact Form');
    $driver->findElement(WebDriverBy::name('message'))->sendKeys('Ky është një mesazh testi nga Selenium.');

    echo "📝 Fushat u plotësuan\n";

    // Kliko butonin me JavaScript, scroll dhe click për siguri
    $driver->executeScript("
        const btn = document.querySelector('button[type=\"submit\"]');
        if(btn) btn.scrollIntoView({behavior: 'smooth', block: 'center'});
        if(btn) btn.click();
    ");
    echo "➡️ Butoni Send Message u klikua me JavaScript\n";

    // Pris që div.success të shfaqet (AJAX-safe)
    // Pris deri 10 sekonda, por lejo që të presë visibility të elementit që mund të shtohet dinamically
    $successDiv = $wait->until(
    WebDriverExpectedCondition::presenceOfElementLocated(
        WebDriverBy::xpath("//*[contains(text(),'Message sent successfully')]")
    )
    );


    $text = $successDiv->getText();
    $currentUrl = $driver->getCurrentURL();
    echo "🔗 URL aktuale pas submit: $currentUrl\n";

    // Verifiko që mbetëm në contact.php
    if (strpos($currentUrl, '/contact.php') !== false) {
        echo "✅ Jemi ende në faqen e contact.php pas submit\n";
    } else {
        echo "❌ URL ndryshoi pas submit!\n";
    }

    // Kontrollo mesazhin e suksesit
    if (strpos($text, 'Message sent successfully') !== false) {
        echo "✅ Testi kaloi: Mesazhi u dërgua me sukses!\n";
    } else {
        echo "❌ Testi dështoi: Mesazhi nuk u dërgua. Teksti: $text\n";
    }

    $driver->quit();
    echo "🛑 Testimi përfundoi.\n";

} catch (Exception $e) {
    echo "⚠️ Gabim gjatë testimit: " . $e->getMessage() . "\n";
}
?>
