<?php

// form
if (isset($_POST['submit'])) {
  session_start();
  $item_name = $_POST['name'];
  $item_description = $_POST['description'];
  $item_price = $_POST['price'];
  $item_type = $_POST['type'];
  $item_image = $_FILES['image'];
  $creator = $_SESSION['UserID'];

  require_once '../functions.php';
  require_once '../config.php';

  session_start();
  RequireAdmin();

  if (empty($item_name) || empty($item_description) || empty($item_price) || empty($item_type)) {
    header("Location: /market/new?error=One or more fields is empty");
    exit();
  }

  // check if price is a number
  if (!is_numeric($item_price)) {
    header("Location: /market/new?error=Price must be a number");
    exit();
  }

  if($item_image['type'] != 'image/png') {
    header("Location: /market/new?error=Image must be a .png file");
    exit();
  }

  if($item_image['size'] > 200000) {
    header("Location: /market/new?error=Image must be less than 200kb");
    exit();
  }
  
  // check if image is 200x206
  $image_size = getimagesize($item_image['tmp_name']);
  if($image_size[0] != 200 || $image_size[1] != 206) {
    header("Location: /market/new?error=Image must be 200x206");
    exit();
  }
  



  UploadMarketItem($item_name, $item_description, $item_price, $item_type);
  // get id of image that was just uploaded
  $id = GetLastMarketItem();
  // upload image to server
  $image_name = $id . '.png';
  if(!move_uploaded_file($item_image['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/cdn/Store/' . $image_name)) {
    header("Location: /market/new?error=Image could not be uploaded");
    exit();
  }
  // redirect to market
  header("Location: /market?success=Item Created!");
} else {
  header('Location: /');
}
