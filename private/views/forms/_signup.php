<?php
if (isset($_POST["submit"])) {
  global $conn;
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $passwordRepeat = $_POST["passwordRepeat"];

  // TODO make this all one function
  if (empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) {
    $_SESSION["error"] = "Please fill in all fields";
    header("location: /signup");
    exit();
  }
  if (!preg_match("/^[a-zA-Z0-9_ ]*$/", $username)) {
    $_SESSION["error"] = "Username can only contain letters, numbers, and underscores.";
    header("location: /signup");
    exit();
  }
  if (strlen($username) > 20 || strlen($username) < 3) {
    $_SESSION["error"] = "Username must be between 3 and 20 characters.";
    header("location: /signup");
    exit();
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["error"] = "Invalid email.";
    header("location: /signup");
    exit();
  }
  if (strlen($password) < 8 || strlen($password) > 50) {
    $_SESSION["error"] = "Password must be between 8 and 50 characters.";
    header("location: /signup");
    exit();
  }
  if ($password != $passwordRepeat) {
    $_SESSION["error"] = "Passwords do not match.";
    header("location: /signup?error=Passwords do not match.");
    exit();
  }
  $statement = $conn->prepare("SELECT * FROM users WHERE user_name = :username");
  $statement->execute(array(':username' => $username));
  $result = $statement->fetch();
  // if result is not empty, username exists
  if (!empty($result)) {
    $_SESSION["error"] = "Username already exists.";
    header("location: /signup");
    exit();
  }

  $statement = $conn->prepare("INSERT INTO users (user_name, user_email, user_password, user_signup_ip, user_ip) VALUES (:username, :email, :password, :ip, :ip)");
  // hash password
  $password = password_hash($password, PASSWORD_DEFAULT);
  $statement->execute(array(':username' => $username, ':email' => $email, ':password' => $password, ':ip' => md5($_SERVER['REMOTE_ADDR'])));
  // ANCHOR session variables
  session_start();
  $_SESSION["UserAuthenticated"] = "true";
  $_SESSION['UserID'] = $conn->lastInsertId();
  $_SESSION['Username'] = $username;
  $_SESSION['UserEmail'] = $email;
  $_SESSION['UserIP'] = $_SERVER['REMOTE_ADDR'];
  $_SESSION['Theme'] = 0;
  UserLog($_SESSION['UserID'], "User created account.");
  $_SESSION['note'] = "Welcome to Brick-Town, " . $_SESSION['Username'] . "!";
  header("location: /dashboard/");
  exit();
}
?>
<div class="content">
  <div class="center">
    <div class="card">
      <h1>Signup</h1>
      <form action="/signup/" method="post">
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
        <label for="error" class="error">
          <?php echo @$_SESSION['error']; ?><br>
        </label>
        <button type="submit" name="submit">
          Signup
        </button>
        <hr>
        <a href="/login">Login</a>
      </form>
    </div>
  </div>
</div>

<?php
unset($_SESSION['error']);
?>