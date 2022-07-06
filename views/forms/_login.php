<?php
// get error from url
@$error = $_GET["error"];
// if error is set
HandleError($error);
?>
<div class="content">
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
      <hr>
      <a href="/signup/" style="display: block; color: #fff; text-align: center; font-size: 135%; text-decoration: none;">Signup</a>
    </div>
  </div>
</div>
