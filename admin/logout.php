<?php
include '../includes/header.php';

session_unset();
session_destroy();
header("Location: ../index.php");
exit();
?>