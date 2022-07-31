<?php
if (isset($_POST["submit"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (empty($username) || empty($password)) {
    $_SESSION["error"] = "Please fill in all fields";
    header("location: /login");
    exit();
  }
  $statement = $conn->prepare("SELECT * FROM users WHERE user_name = :username");
  $statement->execute(array(':username' => $username));
  $result = $statement->fetch();
  if (!empty($result)) {
    // check password
    if (password_verify($password, $result['user_password'])) {
      $_SESSION["UserAuthenticated"] = "true";
      $_SESSION['UserID'] = $result['user_id'];
      $_SESSION['Username'] = $result['user_name'];
      $_SESSION['UserEmail'] = $result['user_email'];
      $_SESSION['UserIP'] = $_SERVER['REMOTE_ADDR'];
      $_SESSION['Theme'] = $result['user_theme'];
      $_SESSION['note'] = "Welcome back, " . $_SESSION['Username'] . "!";
      header("location: /dashboard/");
      exit();
    } else {
      $_SESSION['error'] = "Invalid username or password.";
      header("location: /login/");
      exit();
    }
  } else {
    $_SESSION['error'] = "Invalid username or password.";
    header("location: /login/");
    exit();
  }
}
// get error from url
@$error = $_SESSION['error'];
?>
<div class="content">
  <div class="center">
    <div class="card">
      <h1>Login</h1>
      <form action="/login/" method="POST">
        <label for="username">
          Username
        </label>
        <input type="text" id="username" name="username" placeholder="Username">
        <label for="password">
          Password
        </label>
        <input type="password" id="password" name="password" placeholder="Password">
        <?php
        // if error is set
        if(isset($error)) {
          echo '<label class="error">'.$error.'</label><br>';
        }
        ?>
        <button type="submit" name="submit">
          Login
        </button>
        <hr>
        <a href="/signup">Signup</a>
      </form>
    </div>
  </div>
</div>

<?php
unset($_SESSION['error']);
?>