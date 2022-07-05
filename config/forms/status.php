<?php

if (isset($_POST["submit"])) {
    session_start();
    $status = $_POST["status"];

    require_once '../functions.php';
    require_once '../config.php';
    
    if(InvalidStatus($status)) {
      header("location: ../../dashboard/?error=Invalid status!");
      exit();
    }
    if(StatusTooLong($status)) {
      header("location: ../../dashboard/?error=Status too long!");
      exit();
    }

    if(!empty($status)) {  
      UpdateStatus($conn, $status, $_SESSION["UserID"]);
      exit();
    }

    header("Location: /dashboard?error=Status is empty!");
} else {
    header('location: ../../dashboard?error=Access Denied!');
}