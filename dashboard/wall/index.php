<?php

if(isset($_POST['submit'])) {
  session_start();
  include($_SERVER['DOCUMENT_ROOT'] . "/config/profanity.php");
  include($_SERVER['DOCUMENT_ROOT'] . "/config/functions.php");
  include($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

  $message = ProfanityFilter($_POST['message']);
  $creator = $_SESSION['UserID'];

  if(empty($message)) {
    $_SESSION['error'] = 'The wall message is empty!';
    header('location: /dashboard/');
    exit();
  }

  // insert 
  $statement = $conn->prepare("INSERT INTO wall (wall_message, wall_creator, wall_created) VALUES (:message, :creator, NOW())");
  $statement->execute(array(':message' => $message, ':creator' => $creator));
  $_SESSION['note'] = 'Message posted!';
  header('location: /dashboard/');
  exit();
} else {
  header("location: /dashboard");
  exit();
}