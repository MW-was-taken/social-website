<?php

if(isset($_POST['submit'])) {
  $theme = $_POST['theme'];
  require_once '../functions.php';
  require_once '../config.php';

  echo $theme;
  session_start();
  UpdateTheme($theme, $_SESSION['UserID']);
  header("Location: /settings?note=Theme updated!");
  
} else {
  header('Location: /settings?error=Theme not updated!');
}