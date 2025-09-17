<?php
$password_plain = 'test123';  // fjalëkalimi që do hash-in
$hash = password_hash($password_plain, PASSWORD_DEFAULT);
echo $hash;
?>
