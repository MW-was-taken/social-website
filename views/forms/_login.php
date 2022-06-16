<?php
// get error from url
@$error = $_GET["error"];
// if error is set
if (isset($error)) {
?>
  <div id="snackbar" style="background-color: red;">
    <?php echo $error; ?>
  </div>
<?php
}
?>
<div class="card form-card">
  <div class="card-header">
    Login
  </div>
  <div class="card-body">
  <form action="/config/forms/login.php" method="POST">
  <div class="input-group">
    <div class="labels">
      <label for="username">Username</label>
      <label for="password">Password</label>
    </div>
    <div class="inputs">
      <input type="text" name="username" id="username" required>
      <input type="password" name="password" id="password" required>
    </div>
  </div>
  <button type="submit" name="submit" value="submit">Login</button>
</form>
  </div>
</div>
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