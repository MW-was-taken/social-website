<?php
// get error from url
@$error = $_GET["note"];
// if error is set
if (isset($error)) {
?>
  <div id="snackbar" style="  background: rgb(4, 4, 82);">
    <?php echo $error; ?>
  </div>
<?php
}
?>
<h1>
  Welcome to Elfo's Forum, <?php echo($_SESSION['Username']); ?>
</h1>

<h4>
  Your Status
</h4>
<label>
  <?php echo(GetStatus($conn, $_SESSION['UserID'])); ?>
</label>
<h4>
  Update Status
</h4>
<form action="/config/forms/status.php" method="POST">
  <input type="text" name="status" placeholder="Status...">
  <br>
  <button type="submit" name="submit" value="submit">Set Status</button>
</form>
<?php
if (isset($error)) {
?>
  <script>
    // Get the snackbar DIV
    var x = document.getElementById("snackbar");

    // Add the "show" class to DIV
    x.className = "show";

    // After 3 seconds, remove the show class from DIV
    setTimeout(function() {
      x.className = x.className.replace("show", "");
    }, 3000);
  </script>
<?php
}
?>