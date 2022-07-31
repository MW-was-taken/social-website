<?php
// get error from url
@$error = $_GET["error"];
// if error is set
HandleError($error);
?>
<div class="content">
  <div class="center">
    <div class="card">
      <h1>Login</h1>
      <form action="/config/forms/login.php" method="post">
        <label for="username">
          Username
        </label>
        <input type="text" id="username" name="username" placeholder="Username">
        <label for="password">
          Password
        </label>
        <input type="password" id="password" name="password" placeholder="Password">
        <button type="submit" name="submit">
          Login
        </button>
        <hr>
        <a href="/signup">Signup</a>
      </form>
    </div>
  </div>
</div>