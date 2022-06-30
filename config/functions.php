<?php
// getters

// prevents xss attacks and trims whitespace
function PurifyInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}

function ToLineBreaks($text) {
    return nl2br($text);
}

function ToMarkdown($text) {
  $text = preg_replace("#\*([^*]+)\*#", '<b>$1</b>', $text);
  $text = preg_replace("#\_([^_]+)\_#", '<i>$1</i>', $text);
  $text = preg_replace("#\%([^%]+)\%#", '<strike>$1</strike>', $text);
  $text = preg_replace("#\`([^`]+)\`#", '<code>$1</code>', $text);
  return $text;
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
    return "Brick-Town";
  }
  return $name . " - " . "Brick-Town";
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
    if (!preg_match("/^[a-zA-Z0-9_]*$/", $username)) {
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
// Credit: https://stackoverflow.com/users/67332/glavi%c4%87
function time_elapsed_string($datetime, $full = false) {
  $now = new DateTime;
  $ago = new DateTime($datetime);
  $diff = $now->diff($ago);

  $diff->w = floor($diff->d / 7);
  $diff->d -= $diff->w * 7;

  $string = array(
      'y' => 'year',
      'm' => 'month',
      'w' => 'week',
      'd' => 'day',
      'h' => 'hour',
      'i' => 'minute',
      's' => 'second',
  );
  foreach ($string as $k => &$v) {
      if ($diff->$k) {
          $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
      } else {
          unset($string[$k]);
      }
  }

  if (!$full) $string = array_slice($string, 0, 1);
  return $string ? implode(', ', $string) . ' ago' : 'Now';
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

    $sql = "UPDATE users SET user_updated = now() WHERE user_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ?error=Database Failed!");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $User);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
function IfIsOnline($updated_at_timestamp)
{
    if ($updated_at_timestamp == null) {
        return false;
    }
    $now = date_create(date('Y-m-d H:i:s'));
    $updated = date_create($updated_at_timestamp);

    $now_format = date_format($now, 'Y-m-d H:i:s');
    $updated_format =  date_format($updated, 'Y-m-d H:i:s');

    $test1 = strtotime($now_format);
    $test2 = strtotime($updated_format);
    $hour = abs($test1 - $test2) / (1 * 1);

    if ($hour < 90) {
        return true;
    } else {
        return false;
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
    header("location: ../../dashboard");
    exit();
  }
}

function GetNumberOfUsers() {
  global $conn;
  $sql = "SELECT * FROM users";
  $result = mysqli_query($conn, $sql);
  $NumberOfUsers = mysqli_num_rows($result);
  mysqli_close($conn);
  return $NumberOfUsers;
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
  if ($result == null) {
    echo "<label>No users found! Click <a href='/signup' class='link'>here</a> to signup today!</label>";
  } else {
    return $result;
  }
  return $result;
}

function ListUsers() {
  $users = GetUsers();
  foreach ($users as $user) {
    echo '<div class="ellipsis">';
    echo "<a href='/profile?id=" . $user['user_id'] . "'>" . $user['user_name'] . "</a>";
    if(!IfIsOnline($user['user_updated'])) {
      echo '<span class="status-dot users"></span>';
    } else {
      echo '<span class="status-dot users online"></span>';
    }
    echo '</div>';
    if (!empty($user['user_status'])) {
      echo "<label>" . PurifyInput($user['user_status']) . "</label>";
    }
    echo "<hr>";
  }
}
function HandleProfile($id) {
  if ($id !== null && !empty($id)) {
    $user = GetUserByID($id);
    return $user;
  }
}

function GetUserByID($id) {
  global $conn;
  $sql = "SELECT * FROM users WHERE user_id = ?";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../../dashboard/?error=Database Failed!");
    exit();
  }

  mysqli_stmt_bind_param($stmt, "s", $id);
  mysqli_stmt_execute($stmt);
  $Data = mysqli_stmt_get_result($stmt);

  if ($row = mysqli_fetch_assoc($Data)) {
    return $row;
  } else {
    header("location: ../../users/?error=Invalid User! This usually means that the ID entered is not a valid user." . $id);
  }
}
function GetProfileLink($user_id, $user_name) {
  return "<a href='/profile?id=" . $user_id . "'>" . $user_name . "</a>";
}
function HandleDate($date) {
  $date_formatted = date("F j, Y", strtotime($date));
  return $date_formatted;
}
function HandleError($type) {
  if (isset($type)) {
    echo '<div class="toast-wrapper">
    <div class="toast error" id="toast">
      <div class="container-1 error">
        <i class="fa-solid fa-square-xmark"></i>
      </div>
      <div class="container-2">
        <p>Error</p>
        <p>' . $type . '</p>
      </div>
      <button class="close" onclick="closeToast()">
        &times;
      </button>
    </div>
  </div>';
  echo '<script src="/js/toast.js"></script>
  <script>
  showToast();
  </script>';
  }
}
function HandleNote($type) {
  if (isset($type)) {
    echo '<div class="toast-wrapper">
    <div class="toast" id="toast">
      <div class="container-1">
        <i class="fa-solid fa-square-xmark"></i>
      </div>
      <div class="container-2">
        <p>Success</p>
        <p>' . $type . '</p>
      </div>
      <button class="close" onclick="closeToast()">
        &times;
      </button>
    </div>
  </div>';
  echo '<script src="/js/toast.js"></script>
  <script>
  showToast();
  </script>';
  }
}

function ViewMessages($user_id) {
  global $conn;
  $sql = "SELECT * FROM messages WHERE msg_receiver = ? ORDER BY msg_id DESC";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../../messages/?error=Database Failed!");
    exit();
  }

  mysqli_stmt_bind_param($stmt, "s", $user_id);
  mysqli_stmt_execute($stmt);

  $Data = mysqli_stmt_get_result($stmt);

  $result = [];
  while ($row = mysqli_fetch_assoc($Data)) {
    $result[] = $row;
  }
  if ($result != null) {
    return $result;
  }
}
function ListMessages($result) {
  if ($result !== null) {
    foreach ($result as $message) {
      echo "<a href='/messages/view?id=" . $message['msg_id'] . "'>" . $message['msg_title'] . "</a>";
      echo "<br>";
      echo "<a href='/profile?id=" . $message['msg_sender'] . "'>" . GetUserByID($message['msg_sender'])['user_name'] . "</a><br>";
      echo "<label>" . HandleDate($message['msg_created']) . "</label>";
      echo "<hr>";
    }
  } else {
    echo "<p>No Messages</p>";
  }
}
function SendMessage($sender_id, $receiver_id, $title_unpurified, $body_unpurified) {
  global $conn;
  $body_sanitized = PurifyInput($body_unpurified);
  $body_markdown = ToMarkdown($body_sanitized);
  $title = PurifyInput($title_unpurified);
  $body= ToLineBreaks($body_markdown);
  $sql = "INSERT INTO messages (msg_sender, msg_receiver, msg_title, msg_body) VALUES (?, ?, ?, ?)";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../../messages/?error=Database Failed!");
    exit();
  }

  mysqli_stmt_bind_param($stmt, "ssss", $sender_id, $receiver_id, $title, $body);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if ($result) {
    HandleNote("Message Sent!");
    header("location: ../../messages/");
  } else {
    HandleError("Message Failed to Send!");
    header("location: ../../messages/");
  }
}
function ViewMessage($msg_id, $user_id) {
    global $conn;
    $sql = "SELECT * FROM messages WHERE msg_id = ? AND msg_receiver = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("location: ../../messages/?error=Database Failed!");
      exit();
    }
  
    mysqli_stmt_bind_param($stmt, "ss", $msg_id, $user_id);
    mysqli_stmt_execute($stmt);
  
    $Data = mysqli_stmt_get_result($stmt);
  
    if ($row = mysqli_fetch_assoc($Data)) {
      return $row;
    } else {
      header("location: ../../messages/?error=Invalid Message!");
    }
}

function GetMessageTitle($message) {
  return $message['msg_title'];
}

function GetMessageBody($message) {
  return $message['msg_body'];
}

function GetMessageSender($message) {
  return $message['msg_sender'];
}

function GetMessageDate($message) {
  return time_elapsed_string($message['msg_created']);
}