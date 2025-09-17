<?php
// Array lokal me faktet për kafe
$coffeeFacts = [
    "Coffee is the world's second most traded commodity after oil.",
    "Espresso has less caffeine than a cup of regular coffee.",
    "Coffee was discovered in Ethiopia in the 9th century.",
    "Finland is the country that consumes the most coffee per capita.",
    "Decaf coffee is not completely caffeine-free."
];

// Zgjedhim një fakt rastësor
$fact = $coffeeFacts[array_rand($coffeeFacts)];

echo '<div style="text-align: center; margin: 200px auto 20px auto; padding: 15px; border: 2px solid #ffa500; border-radius: 10px; background-color: #fff3cd; color: #333; max-width: 400px;">';
echo "<h3 style='color: #ff8c00;'>☕ Fun Fact about Coffee ☕</h3>";
echo "<p style='font-size: 1.2em;'><strong>" . htmlspecialchars($fact) . "</strong></p>";
echo "</div>";
<<<<<<< HEAD
?>
=======
?>
>>>>>>> 1a7c1ca9f0d11617aea35361ea25d40795e70aed
