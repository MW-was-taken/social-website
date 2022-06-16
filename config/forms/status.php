<?php

if (isset($_POST["submit"])) {
    session_start();
    $status = $_POST["status"];

    require_once '../functions.php';
    require_once '../config.php';


    UpdateStatus($conn, $status, $_SESSION["UserID"]);
} else {
    header('location: ../../dashboard?error=Access Denied!');
}