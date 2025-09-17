<?php
function getFunFact($conn) {
    $result = $conn->query("SELECT fact FROM fun_facts");
    if (!$result || $result->num_rows == 0) {
        return "No fun facts available.";
    }

    $facts = [];
    while ($row = $result->fetch_assoc()) {
        $facts[] = $row['fact'];
    }

    // Zgjidh një fakt rastësor
    $randomFact = $facts[array_rand($facts)];
    return $randomFact;
}
?>
