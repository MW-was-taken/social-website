<?php

if (isset($_POST["submit"])) {
    session_start();
    $Bio_raw = $_POST["bio"];

    require_once '../functions.php';
    require_once '../config.php';

    $Bio = ToLineBreaks($Bio_raw);
    
    if(BioTooLong($Bio)) {
      header("location: ../../settings/?error=Bio too long!");
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