<?php
$UserID = $_SESSION['UserID'];
$messages = ViewMessages($UserID);


?>

<div class="card users">
  <div class="card-header">
    Messages
  </div>
  <div class="card-body">
    <?php
      ListMessages($messages);
    ?>
  </div>
</div>