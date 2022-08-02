<?php
// start session
include($_SERVER['DOCUMENT_ROOT'] . "/config/functions.php");
include($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
session_start();
ob_start();
RequireAuthentication();
UpdateUser($conn);
if (!isset($_SESSION['UserIP']) || $_SESSION['UserIP'] != $_SERVER['REMOTE_ADDR']) {
  CheckIpAddress($_SERVER['REMOTE_ADDR']);
}
Maintenance();

// get amount of cubes

$statement = $conn->prepare("SELECT cubes FROM users WHERE user_id = :user_id");
$statement->execute(array(':user_id' => $_SESSION['UserID']));
$cubes = $statement->fetch(PDO::FETCH_ASSOC);
$cubes = $cubes['cubes']; 

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/style.css ">
  <link href="https://cdn.jsdelivr.net/gh/hung1001/font-awesome-pro@4cac1a6/css/all.css" rel="stylesheet" type="text/css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- jquery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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
          <a href="/forum"><i class="fa fa-comments"></i>Forum</a>
          <a href="/market"><i class="fa fa-shopping-cart"></i>Market</a>
          <?php
          echo AdminLink();
          ?>
          <a href="/users">
            <i class="fa fa-users"></i>Users
          </a>
        </div>
        <div class="right">
          <a href='/logout'><i class='fa fa-sign-out-alt'></i>Logout</a>
          <!-- cubes amount -->
          <span class="cubes">
            <i class="far fa-cube"></i>
            <?php echo $cubes; ?>
          </span>
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
          <i class="fas fa-cog"></i>Account Settings
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