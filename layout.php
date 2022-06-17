<?php
session_start();
include("config/functions.php");
include("config/config.php");
@$name = AssignPageName($name);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php
      echo(HandlePageName($name));
    ?>
  </title>
  <link rel="stylesheet" href="/css/style.css ">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
  <nav>
    <div class="header">
      <h1>
        Elfo's Forum
      </h1>
    </div>
    <div class="navbar">
      <div class="container">
        <div class="left">
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
          echo "<a href='/messages'>Messages</a>\n";
          echo "<a href='/profile?id=" . $_SESSION['UserID'] . "'>Profile</a>\n";
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
</body>
</html>