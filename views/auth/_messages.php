<?php

$messages = ViewMessages($_SESSION['UserID']);

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