<?php 
// import the StackImage class
require_once "../utils/StackImage.php";
require_once "../config/functions.php";
require_once "../config/config.php";
$user_id = $_GET['id'];
if(!CheckIfUserExists($user_id)) {
  $Image = new StackImage($_SERVER["DOCUMENT_ROOT"]."/cdn/error.png");
  $Image->Output();
}

$Image = new StackImage($_SERVER["DOCUMENT_ROOT"]."/cdn/Avatar.png");
$Image->AddLayer($_SERVER["DOCUMENT_ROOT"]."/cdn/Avatar.png");
$Image->AddLayer($_SERVER["DOCUMENT_ROOT"]."/cdn/Pants.png");
$Image->AddLayer($_SERVER["DOCUMENT_ROOT"]."/cdn/Shirt.png");
$Image->CropImage();
$Image->Output();

