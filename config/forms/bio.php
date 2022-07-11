<?php

if (isset($_POST["submit"])) {
    session_start();
    $Bio = $_POST["bio"];

    require_once '../functions.php';
    require_once '../config.php';
    
    if(InvalidBio($Bio)) {
      header("location: ../../dashboard/?error=Invalid Bio!");
      exit();
    }
    if(BioTooLong($Bio)) {
      header("location: ../../dashboard/?error=Bio too long!");
      exit();
    }

    if(!empty($Bio)) {  
      UpdateBio($conn, $Bio, $_SESSION["UserID"]);
      exit();
    }

    header("Location: /settings?error=Bio is empty!");
} else {
    header('location: /404/');
}