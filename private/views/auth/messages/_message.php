<?php 
$message_id = $_GET['id'];
$UserID = $_SESSION['UserID'];
$message = ViewMessage($message_id, $UserID);
$Sender = GetUserByID($conn, $message['msg_sender']);
$name = GetMessageTitle($message);
?>
<div class="card users">
  <div class="card-header">
    <?php echo GetMessageTitle($message); ?>
  </div>
  <div class="card-body">
    <p><?php echo GetMessageBody($message); ?></p>
    <hr>
    <p><?php echo GetProfileLink($Sender['user_id'], $Sender['user_name']); ?>, <?php echo GetMessageDate($message);?></p>
  </div>
</div>