<?php
// import the StackImage class
require_once $_SERVER['DOCUMENT_ROOT'] . "/utils/StackImage.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/config/functions.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/config/config.php";
if (!isset($_GET['id'])) {
  echo '<style>body {background-color: #1E1D1D; font-family: monospace; color: white;}</style>';
  echo 'No ID provided.';
} else {

  // sanitize the ID
  $item_id = $_GET['id'];
  // check if id is numeric
  if(!is_numeric($item_id)) {
    echo '<style>body {background-color: #1E1D1D; font-family: monospace; color: white;}</style>';
    echo 'ID is not numeric.';
    exit();
  }

  // check if item is in the database
  //CheckifItemExists($conn, $item_id);


  $Image = new StackImage($_SERVER["DOCUMENT_ROOT"] . "/cdn/TransparentBG.png");
  $Image->AddLayer($_SERVER["DOCUMENT_ROOT"] . "/cdn/Avatar.png");
  $Image->AddLayer($_SERVER["DOCUMENT_ROOT"] . "/cdn/Store/default_face.png");
  $Image->AddLayer($_SERVER["DOCUMENT_ROOT"] . "/cdn/Store/" . $item_id . ".png");

  $Image->Output();
}
