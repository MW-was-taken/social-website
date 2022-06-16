<?php
// getters

// prevents xss attacks and trims whitespace
function PurifyInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}

// TODO : 003 - find better use of getters or remove them
function GetAuthentication()
{
    return @$_SESSION["UserAuthenticated"];
}

// page functions
function AssignPageName($name) {
  if (isset($name) && !empty($name)) {
    return $name;
  }
  return null;
}
function HandlePageName($name)
{
  if (empty($name)) {
    return "Elfo's Forum";
  }
  return $name . " - " . "Elfo's Forum";
}

// config functions


function OpenConnection($db_host, $db_username, $db_password, $db)
{
    $conn = mysqli_connect($db_host, $db_username, $db_password);
    // select database
    try {
        // declare database if it exists
        // if it does not exist the catch block will be ran
        $db_selected = mysqli_select_db($conn, $db);
    } catch (Exception $e) {
        // variable e wont be used due to the problem being present
        unset($e);
        // create the database
        $sql = 'CREATE DATABASE ' . $db;

        if (mysqli_query($conn, $sql)) {
            return $conn;
        } else {
            echo 'Error creating database: ' . $conn->error . "\n";
        }
    } finally {
        // return $conn after the database is created
        return $conn;
    }
}

function CloseConnection($database_connection)
{
    $database_connection->close();
}

// Authentication Functions
$result;
function EmptyInputSignup($username, $email, $password, $passwordRepeat)
{
    if (empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function InvalidUsername($username)
{
    if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function InvalidEmail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function InvalidPasswordMatch($password, $passwordRepeat)
{
    if ($password !== $passwordRepeat) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function UsernameExists($conn, $username)
{
    $sql = "SELECT * FROM users WHERE user_name = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup/?error=databasefailure");
        exit();
    }


    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $Data = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($Data)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}

function CreateUser($conn, $username, $email, $password)
{
    $sql = "INSERT INTO users (user_name, user_email, user_password) VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=databasefailure");
        exit();
    }

    // hash password
    $HashedPassword = password_hash($password, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $HashedPassword);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $UsernameExists = UsernameExists($conn, $username, $username);
    session_start();
    // ANCHOR session variables
    $_SESSION["UserAuthenticated"] = "true";
    $_SESSION["UserID"] = $UsernameExists["user_id"];
    $_SESSION["Username"] = $UsernameExists["user_name"];
    $_SESSION["UserEmail"] = $UsernameExists["user_email"];
    header("location: ../../dashboard/?note=Successfully signed up!");
    exit();
}

function EmptyInputLogin($username, $password)
{
    if (empty($username) || empty($password)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}
// ANCHOR login functions
function LoginUser($conn, $Username, $Password)
{
    $UsernameExists = UsernameExists($conn, $Username, $Username);
    $PasswordHashed = $UsernameExists["user_password"];
    $CheckPassword = password_verify($Password, $PasswordHashed);

    if ($CheckPassword === false) {
        header("location: ../../login/?error=Wrong username or password!");
    } else if ($CheckPassword === true) {
        session_start();
        $_SESSION["UserAuthenticated"] = "true";
        $_SESSION["UserID"] = $UsernameExists["user_id"];
        $_SESSION["Username"] = $UsernameExists["user_name"];
        $_SESSION["UserEmail"] = $UsernameExists["uesr_email"];
        header("location: ../../dashboard/?note=Successfully logged in!");
        exit();
    }
}
// this function is run everytime the user clicks on a page.
// this will be used to tell if the user is online or not.
function UpdateUser($conn)
{
    $User = $_SESSION["UserID"];

    $sql = "UPDATE users SET user_updated = now() WHERE id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ?error=Database Failed!");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $User);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// TODO : 002 - remove this function or find a better use
function AuthenticationSetter() {
  @session_start();
  if (GetAuthentication() === true) {
    if (GetAuthentication() == "true") {
      return;
    }
  } else {
    $_SESSION["UserAuthenticated"] = "false";
  }
}
function UserIsAuthenticated()
{
    $session = GetAuthentication();
    if ($session === "true") {
        return true;
    } else {
        return false;
    }
}
function RequireAuthentication() {
  if (UserIsAuthenticated() === false) {
    header("location: ../login/?error=You must be logged in to do this!");
    exit();
  }
}
function RequireGuest() {
  if (UserIsAuthenticated() === true) {
    header("location: ../?error=You must be logged out to do this!");
    exit();
  }
}

// status functions

// TODO: 001 - check if user id is not null
function UpdateStatus($conn, $status, $user_id) {
  $sql = "UPDATE users SET user_status = ? WHERE user_id = ?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../../dashboard/?error=Database Failed!");
    exit();
  }

  mysqli_stmt_bind_param($stmt, "ss", $status, $user_id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  header("location: ../../dashboard/?note=Status updated!");
  exit();
}

function GetStatus($conn, $user_id) {
  $sql = "SELECT user_status FROM users WHERE user_id = ?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../../dashboard/?error=Database Failed!");
    exit();
  }

  mysqli_stmt_bind_param($stmt, "s", $user_id);
  mysqli_stmt_execute($stmt);
  $Data = mysqli_stmt_get_result($stmt);

  if ($row = mysqli_fetch_assoc($Data)) {
    return PurifyInput($row["user_status"]);
  } else {
    return "";
  }
}

function GetUsers() {
  global $conn;
  $sql = "SELECT * FROM users";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../../dashboard/?error=Database Failed!");
    exit();
  }

  mysqli_stmt_execute($stmt);
  $Data = mysqli_stmt_get_result($stmt);

  $result = [];
  while ($row = mysqli_fetch_assoc($Data)) {
    $result[] = $row;
  }
  return $result;
}

function ListUsers() {
  $users = GetUsers();
  foreach ($users as $user) {
    if (!empty($user['user_status'])) {
      echo "<a href='profile?id=" . $user['user_id'] . "'>" . $user['user_name'] . "</a>";
      echo "<br>";
      echo "<label>" . $user['user_status'] . "</label>";
      echo "<br>";
    }
  }
}
?>
