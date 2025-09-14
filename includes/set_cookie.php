<?php

function setUserCookie($accepted, $preferences = ['analytics' => false, 'ads' => false]) {
    $cookieData = [
        'accepted' => $accepted,
        'preferences' => $preferences
    ];
   
    setcookie('user_cookies', json_encode($cookieData), time() + 86400, '/', '', isset($_SERVER['HTTPS']), true);
}

if (!isset($_COOKIE['user_cookies'])) {
    setUserCookie(false);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    if (isset($_POST['accept_cookies'])) {
        setUserCookie(true, ['analytics' => true, 'ads' => true]);
        echo json_encode(["success" => true, "message" => "Cookies accepted"]);
        exit();
    }

    
    if (isset($_POST['reject_cookies'])) {
        setUserCookie(false, ['analytics' => false, 'ads' => false]);
        echo json_encode(["success" => true, "message" => "Cookies rejected"]);
        exit();
    }

    
    if (isset($_POST['delete_cookie'])) {
        setcookie('user_cookies', '', time() - 3600, '/');
        echo json_encode(["success" => true, "message" => "Cookie deleted"]);
        exit();
    }
}
?>
