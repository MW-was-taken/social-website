<?php
// import the StackImage class
require_once "../utils/StackImage.php";
require_once "../config/functions.php";
require_once "../config/config.php";
if (!isset($_GET['id'])) {
  echo '<style>body {background-color: #1E1D1D; font-family: monospace; color: white;}</style>';
  echo 'No ID provided.';
} else {
  if (!CheckIfUserExists($_GET['id'])) {
    echo '<style>body {background-color: #1E1D1D; font-family: monospace; color: white;}</style>';
    echo 'The provided ID does not match a user account.';
  } else {
    $Image = new StackImage($_SERVER["DOCUMENT_ROOT"] . "/cdn/Avatar.png");
    $Image->AddLayer($_SERVER["DOCUMENT_ROOT"] . "/cdn/Avatar.png");
    $Image->AddLayer($_SERVER["DOCUMENT_ROOT"] . "/cdn/Pants.png");
    $Image->AddLayer($_SERVER["DOCUMENT_ROOT"] . "/cdn/Shirt.png");
    $Image->CropImage();
    $Image->Output();
}
}