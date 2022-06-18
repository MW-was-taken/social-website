<?php
// get error from url
HandleNote(@$_GET["note"]);
?>
<div class="card users">
  <div class="card-header">
    Welcome back to Elfo's Forum, <?php echo ($_SESSION["Username"]); ?>
  </div>
  <div class="card-body">
    <label for="status" class="status">Your Status</label>
    <p><?php echo GetStatus($conn, $_SESSION['UserID']); ?></p>
    <form action="/config/forms/status.php" method="POST">
      <label for="status" class="status">New Status</label>
      <input type="text" name="status" placeholder="Status...">
      <button type="submit" name="submit" value="submit">Set Status</button>
    </form>
  </div>
</div>