<?php

if(isset($_POST['submit'])) {
  $theme = $_POST['theme'];
  require_once '../functions.php';
  require_once '../config.php';

  if($theme != 1 && $theme != 2 && $theme != 3 && $theme != 4) {
    $theme = 1;
  }

  echo $theme;
  session_start();
  UpdateTheme($theme, $_SESSION['UserID']);
  header("Location: /settings?note=Theme updated!");
  
} else {
  header('Location: /settings?error=Theme not updated!');
}