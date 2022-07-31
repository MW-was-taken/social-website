<?php
include($_SERVER['DOCUMENT_ROOT'] . "/config/functions.php");
include($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
session_start();
CheckIfIpIsBanned($_SERVER['REMOTE_ADDR']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/index.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<?php
include($child_view);
?>
</body>
<head>
<title>
    <?php
      echo(HandlePageName($name));
    ?>
  </title>
</head>
</html>