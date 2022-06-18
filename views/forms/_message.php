<?php
$User = GetUserByID($_GET['id']);
?>
<div class="card form-card message">
  <div class="card-header">
    Send Message to <?php echo $User['user_name']; ?>
  </div>
  <form action="/config/forms/message.php" method="POST">
    <div class="card-body">
      <input type="text" name="title" placeholder="Title...">
      <textarea name="body" id="body" cols="30" rows="10" placeholder="Hey <?php echo $User['user_name']; ?>"></textarea>
      <input type="hidden" value="<?php echo $_SESSION['UserID'] ?>" name="sender_id">
      <input type="hidden" value="<?php echo $_GET['id'] ?>" name="receiver_id">
      <button type="submit" name="submit">Send Message</button>
    </div>
  </form>
</div>