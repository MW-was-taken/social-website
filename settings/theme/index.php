<?php

if(isset($_POST['submit'])) {
  $theme = $_POST['theme'];
  require_once $_SERVER['DOCUMENT_ROOT'] . '/config/functions.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';

  if($theme != 1 && $theme != 2 && $theme != 3 && $theme != 4 && $theme != 5) {
    $theme = 1;
  }

  echo $theme;
  session_start();
  UpdateTheme($theme, $_SESSION['UserID']);
  $_SESSION['note'] = "Theme updated!";
  header("Location: /settings");
  exit();
} else {
  header("Location: /settings");
  exit();
}