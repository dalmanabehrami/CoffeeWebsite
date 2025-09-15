<?php
function &getHelpRedirectURL() {
    $target = "../contact.php#contact";
    return $target;
}

$redirectUrl = &getHelpRedirectURL();
header("Location: $redirectUrl");
exit();
?>