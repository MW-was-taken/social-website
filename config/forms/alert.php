<?php

if(isset($_POST['submit'])) {
  $alert_bool = $_POST['alert_bool'];
  $alert_text = $_POST['alert_text'];
  $alert_type = $_POST['alert_type'];
  $alert_link = $_POST['alert_link'];
  require_once '../functions.php';
  require_once '../config.php';
  require_once '../profanity.php';

  // sanitize input
  $alert_text = PurifyInput($alert_text);
  $alert_link = PurifyInput($alert_link);

  $alert_text = ProfanityFilter($alert_text);
  
  // check if alert bool doesn't equal 0 or 1
  if($alert_bool != 0 && $alert_bool != 1) {
    $alert_bool = 0;
  }

  // check if alert type doesn't equal 1 through 4
  if($alert_type != 1 && $alert_type != 2 && $alert_type != 3 && $alert_type != 4 && $alert_type != 5) {
    $alert_type = 1;
  }

  // check if alert text is empty
  if(empty($alert_text)) {
    header("Location: /admin/banner?error=Banner text is empty!");
  }

  UpdateAlert($alert_bool, $alert_text, $alert_link, $alert_type);
  header("Location: /admin/banner");
  exit();

} else {
  header('Location: /');
}