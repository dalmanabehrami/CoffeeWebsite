<?php
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
    $driver->get('http://localhost/UEB25_CoffeeWebsite_/');
    $wait = new WebDriverWait($driver, 10);

    $keywords = ['Americano', 'Latte', 'Espresso'];

    foreach ($keywords as $keyword) {
        echo "\n🔹 Testi për keyword: $keyword\n";

        try {
            // 1️⃣ Kliko ikonën e search-it
            $searchIcon = $wait->until(
                WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('search-icon'))
            );
            $searchIcon->click();

            // 2️⃣ Pris që input-i të bëhet i dukshëm
            $searchInput = $wait->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('search-input'))
            );
            $searchInput->clear();
            $searchInput->sendKeys($keyword);

            // 3️⃣ Pris pak që të ngarkohen rezultatet
            sleep(1);

            // 4️⃣ Merr tekstin e rezultateve
            $resultsBox = $driver->findElement(WebDriverBy::id('search-results'));
            $resultsText = $resultsBox->getText();

            if (trim($resultsText) !== "") {
                echo "✅ U gjetën rezultate për '$keyword':\n$resultsText\n";

                // (Opsionale) Kliko rezultatin e parë nëse ekziston si element <div> ose <li>
                $resultElements = $driver->findElements(WebDriverBy::cssSelector('#search-results div, #search-results li'));
                if (count($resultElements) > 0) {
                    $resultElements[0]->click();
                    echo "✅ U klikua rezultati i parë\n";
                }
            } else {
                echo "❌ Nuk u gjet asnjë rezultat për '$keyword'\n";
            }

        } catch (Exception $e) {
            echo "⚠️ Gabim gjatë testimit për '$keyword': " . $e->getMessage() . "\n";
        }

        sleep(1);
    }

    $driver->quit();
    echo "\n🛑 Testi për search u përfundua\n";

} catch (Exception $e) {
    echo "⚠️ Gabim gjatë startimit të testit: " . $e->getMessage() . "\n";
    echo "📝 Trace:\n" . $e->getTraceAsString() . "\n";
}
