<form action="/config/forms/signup.php" method="POST">
  <div class="input-group">
    <div class="labels">
      <label for="username">Username</label>
      <label for="email">Email</label>
      <label for="password">Password</label>
      <label for="passwordRepeat">Repeat Password</label>
    </div>
    <div class="inputs">
      <input type="text" name="username" id="username" required>
      <input type="email" name="email" id="email" required>
      <input type="password" name="password" id="password" required>
      <input type="password" name="passwordRepeat" id="passwordRepeat" required>
    </div>
  </div>
  <button type="submit" name="submit" value="submit">Sign Up</button>
</form>