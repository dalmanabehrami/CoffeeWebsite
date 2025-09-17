<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["name"])) {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);
    $age = trim($_POST["age"]);
    if (isset($_POST["phone"])) {
        $phone = $_POST["phone"];
    } else {
        $phone = "";
    }
    
    $message = preg_replace('/\s+/', ' ', $message); 
    $message = trim($message); 

    $nameRegex = "/^[a-zA-Z\s]+$/";
    $emailRegex = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    $ageRegex = "/^\d{1,2}$/"; 
    $phoneRegex = "/^(\+383|0)[\s\-\/\(\)]*\d{2}[\s\-\/\(\)]*\d{3}[\s\-\/\(\)]*\d{3}$/"; 
    if (!preg_match($nameRegex, $name)) {
        echo "Name is not valid! Only letters are allowed.<br />";
    } elseif (!preg_match($emailRegex, $email)) {
        echo "Email is not valid!<br />";
    } elseif (!empty($age) && !preg_match($ageRegex, $age)) {
        echo "Age is not valid. Must be a number between 1-99.<br />";
    } elseif (!empty($phone) && !preg_match($phoneRegex, $phone)) {
        echo "Phone number is not in the correct format!<br />";
    } else {
        echo "Data is correct! Message sent successfully.<br />";
    }
}
?>