<?php
// start session
include($_SERVER['DOCUMENT_ROOT'] . "/config/functions.php");
include($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
session_start();
ob_start();
RequireAuthentication();
UpdateUser($conn);
if (!isset($_SESSION['last_ip']) || $_SESSION['last_ip'] != $_SERVER['REMOTE_ADDR']) {
  CheckIpAddress($_SERVER['REMOTE_ADDR']);
}
Maintenance();
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
  <?php
  echo HandleTheme($_SESSION['Theme']);
  ?>
  <style>
    nav i {
      margin-right: 5px;
    }
  </style>
</head>

<body>
  <nav>
    <div class="navbar">
      <div class="container">
        <div class="left">
          <img src="/cdn/logo.png" alt="Brick-Town logo">
          <?php
          echo HomeLink();
          ?>
          <a href="/forum"><i class="fa-solid fa-comments"></i>Forum</a>
          <a href="/market"><i class="fa-solid fa-shopping-cart"></i>Market</a>
          <?php
          echo AdminLink();
          ?>
          <a href="/users">
            <i class="fa-solid fa-users"></i>Users
          </a>
        </div>
        <div class="right">
          <a href='/logout'><i class='fa-solid fa-sign-out-alt'></i>Logout</a>
        </div>
      </div>
    </div>
    <div class="secondary-navbar">
      <div class="container">
        <?php
        echo ProfileLink();
        echo MessageLink();
        ?>
        <a href='/settings'>
          <i class='fa-solid fa-cog'></i>Account Settings
        </a>
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
  <footer>
    <a href="/staff">Staff List</a> | Copyright &copy; 2022 Brick-Town. All rights reserved. | <a href="/terms">Terms of Service</a> | <a href="/privacy">Privacy Policy</a>
  </footer>
  <noscript>
      <h1 class="center">
        Hey..
      </h1>
      <p class="center">
        Brick-Town doesn't require JavaScript but it is recommended you enable it to have an improved user experience and to remove this message.
      </p>
  </noscript>
  <!-- </body> -->
  <script src="/js/warning.js"></script>
</body>

<head>
  <title>
    <?php
    echo (HandlePageName($name));
    ?>
  </title>
</head>

</html>