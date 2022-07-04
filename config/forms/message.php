<?php

if (isset($_POST["submit"])) {
    session_start();
    $title = $_POST["title"];
    $body = $_POST["body"];
    $sender_id = $_POST["sender_id"];
    $receiver_id = $_POST["receiver_id"];
    
    require_once '../functions.php';
    require_once '../config.php';

    if (empty($title) || empty($body)) {
        header("location: ../../messages/?error=Please fill in all fields!");
    }
    if ($sender_id != $_SESSION['UserID']) {
        header("location: ../../messages/?error=Sender ID did not match User ID! Did you log-out before sending?");
    }

    SendMessage($sender_id, $receiver_id, $title, $body);
    header("location: ../../messages/?note=Message sent!");
} else {
    header('location: ../../signup?error=Access Denied!');
}