<?php

// if form submitted
if(isset($_POST['submit'])) {
  require_once '../functions.php';
  require_once '../config.php';

  session_start();
  SetAllMessagesAsSeen($_SESSION['UserID']);
  exit();
}