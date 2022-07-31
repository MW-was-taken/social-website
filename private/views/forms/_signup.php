<?php
// get error from url
@$error = $_GET["error"];
// if error is set
HandleError($error);
?>
<div class="content">
  <div class="center">
    <div class="card">
      <h1>Signup</h1>
      <form action="/config/forms/signup.php" method="post">
        <label for="email">
          Email
        </label>
        <input type="email" id="email" name="email" placeholder="Email">
        <label for="username">
          Username
        </label>
        <input type="text" id="username" name="username" placeholder="Username">
        <label for="password">
          Password
        </label>
        <input type="password" id="password" name="password" placeholder="Password">
        <label for="passwordRepeat">
          Repeat Password
        </label>
        <input type="password" id="passwordRepeat" name="passwordRepeat" placeholder="Password">
        <button type="submit" name="submit">
          Signup
        </button>
        <hr>
        <a href="/login">Login</a>
      </form>
    </div>
  </div>
</div>