<?php
// start session
include("config/functions.php");
include("config/config.php");
session_start();
@$name = AssignPageName($name);
if (UserIsAuthenticated()) {
  UpdateUser($conn);
  CheckIpAddress($_SERVER['REMOTE_ADDR']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/style.css ">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Ubuntu+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
</head>
<body>
  <nav>
    <div class="navbar">
      <div class="container">
        <div class="left">
        <a style="font-weight: 600;" class="logo">Brick-Town</a>
        <?php
        if (UserIsAuthenticated()) {
          echo "<a href='/dashboard'>Dashboard</a>\n";
        } else {
          echo "<a href='/'>Home</a>\n";
        }
        ?>
        <a href="/forum">Forum</a>
        <?php
        if (UserIsAuthenticated()) {
          $messages = UnseenMessages($_SESSION['UserID']);
          if($messages != false) {
            echo "<a href='/messages'>Messages<span class='badge'>" .$messages . "</span></a>\n";
          } else {
            echo "<a href='/messages'>Messages</a>\n";
          } 
          echo "<a href='/profile?id=" . $_SESSION['UserID'] . "'>Profile</a>\n";
          echo "<a href='/settings'>Account Settings</a>\n";
        }
        ?>
        <a href="/users">Users</a>
        </div>
        <div class="right">
          <?php
          if (isset($_SESSION['UserAuthenticated']) && $_SESSION['UserAuthenticated'] == true) {
            echo("<a href='/logout'>Logout</a>\n");
          } else {
            echo("<a href='/login'>Login</a>\n
            <a href='/signup'>Sign Up</a>\n");
          }
          ?>
        </div>
      </div>
    </div>
  </nav>
  <div class="container">
    <div class="content">
      <?php
        include($child_view);
      ?>
    </div>
  </div>
  <!-- </body> -->
</body>
<head>
<title>
    <?php
      echo(HandlePageName($name));
    ?>
  </title>
</head>
</html>