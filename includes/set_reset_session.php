<?php
session_start();
if (isset($_GET['email'])) {
    $_SESSION['reset_email'] = $_GET['email'];
    echo "Session vendosur për: " . $_GET['email'];
}
