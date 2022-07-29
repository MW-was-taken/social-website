<?php
HandleNote(@$_GET["note"]);
HandleError(@$_GET["error"]);
?>
<div class="card users">
  <div class="card-header">
    Welcome back to Brick-Town, <?php echo ($_SESSION["Username"]); ?>
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