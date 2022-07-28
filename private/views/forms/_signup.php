<?php
// get error from url
@$error = $_GET["error"];
// if error is set
HandleError($error);
?>
<div class="content">
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
        <hr>
        <div style="display: block; color: #fff; text-align: center; font-size: 1.3em"><a href="/login/" style="text-decoration: none; color: #067aef;">Login</a>Already have an account?</div>
      </form>
    </div>
  </div>
</div>
