<?php

// form
if(isset($_POST['submit'])) {
  $maintenance_bool = $_POST['maintenance_bool'];
  require_once '../functions.php';
  require_once '../config.php';
  
  UpdateMaintenance($maintenance_bool);
  header("Location: /admin/maintenance");
  exit();
  
} else {
  header('Location: /');
}