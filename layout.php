<?php
// start session
include("config/functions.php");
include("config/config.php");
session_start();
ob_start();
if (UserIsAuthenticated()) {
  UpdateUser($conn);
  CheckIpAddress($_SERVER['REMOTE_ADDR']);
  Maintenance();
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
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
  <nav>
    <div class="navbar">
      <div class="container">
        <div class="left">
        <a style="font-weight: 600;" class="logo">Brick-Town</a>
        <?php
        echo HomeLink();
        ?>
        <a href="/forum">Forum</a>
        <?php
        echo ProfileLink();
        echo MessageLink();
        echo AdminLink();
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
      echo Alert();
      include($child_view);
      ?>
    </div>
  </div>
  <!-- </body> -->
  <script src="/js/warning.js"></script>
</body>
<head>
<title>
    <?php
      echo(HandlePageName($name));
    ?>
  </title>
</head>
</html>