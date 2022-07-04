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
        exit();
    }
    if ($sender_id == $receiver_id) {
      header("location: ../../messages/?error=You can't send a message to yourself!");
      exit();
    }
    if($sender_id != $_SESSION["UserID"]) {
      header("location: ../../messages/?error=Sender ID does not match User ID!");
      exit();
    } else {
      SendMessage($sender_id, $receiver_id, $title, $body);
      header("location: ../../messages/?note=Message sent!");
    }
} else {
    header('location: ../../signup?error=Access Denied!');
}