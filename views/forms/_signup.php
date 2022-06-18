<?php
// get error from url
@$error = $_GET["error"];
// if error is set
HandleError($error);
?>
<div class="card form-card signup">
  <div class="card-header">
    Sign Up
  </div>
  <div class="card-body">
    <form action="/config/forms/signup.php" method="POST">
      <label for="username">Username</label>
      <input type="text" name="username" id="username" placeholder="Username..." required>
      <label for="email">Email</label>
      <input type="email" name="email" id="email" placeholder="Email..." required>
      <label for="password">Password</label>
      <input type="password" name="password" id="password" placeholder="Password..." required>
      <label for="passwordRPT">Password Repeat</label>
      <input type="password" name="passwordRepeat" id="passwordRPT" placeholder="Password Repeat..." required>
      <button type="submit" name="submit" value="submit">Sign Up</button>
    </form>
  </div>
</div>
