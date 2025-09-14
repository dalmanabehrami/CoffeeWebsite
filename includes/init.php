<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    echo "Error [$errno]: $errstr in $errfile on line $errline.";
});
?>