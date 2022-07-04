<?php

if (isset($_POST["submit"])) {
    session_start();
    $status = $_POST["status"];

    require_once '../functions.php';
    require_once '../config.php';

    if(!empty($status)) {  
      UpdateStatus($conn, $status, $_SESSION["UserID"]);
    }

    header("Location: /dashboard?error=Status is empty!");
} else {
    header('location: ../../dashboard?error=Access Denied!');
}