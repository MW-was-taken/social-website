<?php
// import the StackImage class
require_once "../utils/StackImage.php";
require_once "../config/functions.php";
require_once "../config/config.php";
if (!isset($_GET['id'])) {
  $Image = new StackImage($_SERVER["DOCUMENT_ROOT"] . "/cdn/no-id.png");
  $Image->Error($_SERVER['DOCUMENT_ROOT'] . "/cdn/no-id.png");
} else {
  // check if id contains "DROP"
  if(strpos($_GET['id'], "DROP") !== false) {
    // dark mode error message
    $Image = new StackImage($_SERVER["DOCUMENT_ROOT"] . "/cdn/fuck-you.png");
    $Image->Error($_SERVER['DOCUMENT_ROOT'] . "/cdn/fuck-you.png");
  }

  if (!CheckIfUserExists($_GET['id'])) {
    // get error image from CDN
    $Image = new StackImage($_SERVER["DOCUMENT_ROOT"] . "/cdn/no-account.png");
    $Image->Error($_SERVER['DOCUMENT_ROOT'] . "/cdn/no-account.png");
  } else {
    $UserID = $_GET['id'];
    $User = GetUserByID($conn, $UserID);
    $shirt = "/cdn/Store/" . $User['wearing_shirt'].  ".png";
    $pants = "/cdn/Store/" . $User['wearing_pants']. ".png";
    $glasses = "/cdn/Store/" . $User['wearing_glasses'] .".png";
    $face = "/cdn/Store/" . $User['wearing_face'] . ".png";
    $hat = "/cdn/Store/" . $User['wearing_hat'] . ".png";

    if($User["wearing_face"] == 0) {
      $face = "/cdn/Store/default_face.png";
    }

    $Image = new StackImage($_SERVER["DOCUMENT_ROOT"] . "/cdn/TransparentBG.png");
    $Image->AddLayer($_SERVER["DOCUMENT_ROOT"] . "/cdn/Avatar.png");
    if($User["wearing_hat"] != 0) {
      $Image->AddLayer($_SERVER["DOCUMENT_ROOT"] . $hat);
    }
    $Image->AddLayer($_SERVER["DOCUMENT_ROOT"] . $face);
    $Image->Output();  
}
}