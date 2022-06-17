<?php
// get error from url
@$error = $_GET["error"];
// if error is set
HandleError($error);
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
          <input type="text" name="username" id="username" required placeholder="Username...">
          <input type="password" name="password" id="password" required placeholder="Password...">
        </div>
      </div>
      <button type="submit" name="submit" value="submit">Login</button>
    </form>
  </div>
</div>
