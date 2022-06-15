<?php
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
    header("location: ../dashboard/?note=Successfully signed up!");
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

function LoginUser($conn, $Username, $Password)
{
    $UsernameExists = UsernameExists($conn, $Username, $Username);

    if ($UsernameExists === false) {
        header("location: ../login/?error=Wrong username or password TEST!");
    }

    $PasswordHashed = $UsernameExists["user_password"];
    $CheckPassword = password_verify($Password, $PasswordHashed);

    if ($CheckPassword === false) {
        header("location: ../login/?error=Wrong password!");
    } else if ($CheckPassword === true) {
        session_start();
        $_SESSION["UserAuthenticated"] = "true";
        $_SESSION["UserID"] = $UsernameExists["user_id"];
        $_SESSION["Username"] = $UsernameExists["user_name"];
        $_SESSION["UserEmail"] = $UsernameExists["uesr_email"];
        header("location: ../dashboard/?note=Successfully logged in!");
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
function AuthenticationSetter() {
  @session_start();
  if (isset($_SESSION["UserAuthenticated"])) {
    if ($_SESSION["UserAuthenticated"] == "true") {
      return;
    }
  } else {
    $_SESSION["UserAuthenticated"] = "false";
  }
}
function GetAuthentication()
{
    return @$_SESSION["UserAuthenticated"];
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
?>
