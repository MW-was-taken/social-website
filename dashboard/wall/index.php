<?php

if(isset($_POST['submit'])) {
  session_start();
  include($_SERVER['DOCUMENT_ROOT'] . "/config/profanity.php");
  include($_SERVER['DOCUMENT_ROOT'] . "/config/functions.php");
  include($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");

  if(!isset($_SESSION['UserID'])) {
    $_SESSION['error'] = "You must be logged in to post on the wall!";
    header("Location: /login");
    exit();
  }

  if(Flood($_SESSION['UserID'], 15)) {
    $_SESSION['error'] = "Try again in 15 seconds!";
    header("Location: /dashboard");
    exit();
  }

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

  SetUserFlood($_SESSION['UserID']);

  $_SESSION['note'] = 'Message posted!';
  header('location: /dashboard/');
  exit();
} else {
  header("location: /dashboard");
  exit();
}