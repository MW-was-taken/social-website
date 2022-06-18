<?php
// get error from url
@$error = $_GET["error"];
// if error is set
HandleError($error);
?>
<div class="card form-card signup">
  <div class="card-header">
    Login
  </div>
  <div class="card-body">
    <form action="/config/forms/login.php" method="POST">
          <label for="username">Username</label>
          <input type="text" name="username" id="username" required placeholder="Username...">
          <label for="password">Password</label>
          <input type="password" name="password" id="password" required placeholder="Password...">
      <button type="submit" name="submit" value="submit">Login</button>
    </form>
  </div>
</div>
