<?php
// get avatar image
function GetHeadshot($user_id) {
  $image_url = "http://localhost/Avatar?id=" . $user_id;
  // get image from url
  $image = file_get_contents($image_url);
  // create image from string
  $image = imagecreatefromstring($image);
  // only get middle part of image
  $image = imagecrop($image, ['x' => 50, 'y' => 65, 'width' => 100, 'height' => 150]);

  // save image
   header('Content-Type: image/png');
  imagepng($image);
  return $image;

}

GetHeadshot(6);