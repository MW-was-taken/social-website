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
    Sign Up
  </div>
  <div class="card-body">
    <form action="/config/forms/signup.php" method="POST">
      <div class="input-group">
        <div class="labels">
          <label for="username">Username</label>
          <label for="email">Email</label>
          <label for="password">Password</label>
          <label for="confirm_password">Confirm Pwd.</label>
        </div>
        <div class="inputs">
          <input type="text" name="username" id="username" required placeholder="Username...">
          <input type="email" name="email" id="email" required placeholder="Email...">
          <input type="password" name="password" id="password" required placeholder="Password...">
          <input type="password" name="passwordRepeat" id="passwordRepeat" required placeholder="Password Repeat...">
        </div>
      </div>
      <button type="submit" name="submit" value="submit">Sign Up</button>
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