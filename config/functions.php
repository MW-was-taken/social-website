<?php
// Convert all mysql functions to PDO functions:




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
  // PDO connection
  $dsn = "mysql:host=$db_host;dbname=$db";
  $pdo = new PDO($dsn, $db_username, $db_password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  return $pdo;
}

function CloseConnection($database_connection)
{
    // close pdo connection
    $database_connection = null;
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

function UsernameExists($pdo, $username)
{
  $statement = $pdo->prepare("SELECT * FROM users WHERE user_name = :username");
  $statement->execute(array(':username' => $username));
  $result = $statement->fetch();
  // if result is not empty, username exists
  if (!empty($result)) {
    return true;
  } else {
    return $result;
  }
}

function CreateUser($pdo, $username, $email, $password)
{
  $statement = $pdo->prepare("INSERT INTO users (user_name, user_email, user_password) VALUES (:username, :email, :password)");
  // hash password
  $password = password_hash($password, PASSWORD_DEFAULT);
  $statement->execute(array(':username' => $username, ':email' => $email, ':password' => $password));
  $result = $statement->fetch();
  session_start();
  // ANCHOR session variables
  $_SESSION["UserAuthenticated"] = "true";
  $_SESSION['UserID'] = $pdo->lastInsertId();
  $_SESSION['Username'] = $username;
  $_SESSION['UserEmail'] = $email;
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
  $statement = $conn->prepare("SELECT * FROM users WHERE user_name = :username");
  $statement->execute(array(':username' => $Username));
  $result = $statement->fetch();
  if (!empty($result)) {
    // check password
    if (password_verify($Password, $result['user_password'])) {
      session_start();
      // ANCHOR session variables
      $_SESSION["UserAuthenticated"] = "true";
      $_SESSION['UserID'] = $result['user_id'];
      $_SESSION['Username'] = $result['user_name'];
      $_SESSION['UserEmail'] = $result['user_email'];
      header("location: ../../dashboard/?note=Successfully logged in!");
      exit();
    } else {
      header("location: ../../login/?note=Invalid password!");
      exit();
    }
  } else {
    header("location: ../../login/?note=Invalid username!");
    exit();
  }
}
// ANCHOR auth functions
function UserIsAuthenticated()
{
    $session = @$_SESSION['UserAuthenticated'];
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
// end auth functions
// status functions

// TODO: make empty input checker for status



function UpdateStatus($conn, $status, $user_id) {
  // insert user_status into users table
  $statement = $conn->prepare("UPDATE users SET user_status = :status WHERE user_id = :user_id");
  $statement->execute(array(':status' => $status, ':user_id' => $user_id));
  header("location: ../../dashboard/?note=Status updated!");
}

function GetStatus($conn, $user_id) {
  // get user_status from users table
  $statement = $conn->prepare("SELECT user_status FROM users WHERE user_id = :user_id");
  $statement->execute(array(':user_id' => $user_id));
  $result = $statement->fetch();
  return $result['user_status'];
}
// end status functions
function GetUsers() {
  global $conn;
  $statement = $conn->prepare("SELECT * FROM users");
  $statement->execute();
  $result = $statement->fetchAll();
  if(!empty($result)) {
    return $result;
  } else {
    echo "No users found!";
  }
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
// ANCHOR profile sectuion
function HandleProfile($id) {
  global $conn;
  if ($id !== null && !empty($id)) {
    $user = GetUserByID($conn, $id);
    return $user;
  }
}
function GetProfileLink($user_id, $user_name) {
  return "<a href='/profile?id=" . $user_id . "'>" . $user_name . "</a>";
}
// end profile section

// ANCHOR handlers and misc functions
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
// this function is run everytime the user clicks on a page.
// this will be used to tell if the user is online or not.
function UpdateUser($pdo)
{
  // use pdo to update user
  $sql = "UPDATE users SET user_updated = NOW() WHERE user_id = :user_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':user_id' => $_SESSION['UserID']));
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
function GetNumberOfUsers($pdo) {
  // use pdo to get number of users
  $sql = "SELECT * FROM users";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
}

function GetUserByID($pdo, $id) {
  // use pdo to get user by id
  $sql = "SELECT * FROM users WHERE user_id = :user_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':user_id' => $id));
  $result = $stmt->fetch();
  return $result;
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
// ANCHOR message section
function UnseenMessages($user_id) {
  $message_number = GetNumberOfUnseenMessages($user_id);
  if ($message_number > 0) {
    return $message_number;
  } else {
    return false;
  }
}
function GetNumberOfUnseenMessages($user_id) {
  // get number of unseen messages with PDO
  global $conn;
  $sql = "SELECT * FROM messages WHERE msg_receiver = :user_id AND msg_seen = 0";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id));
  $result = $stmt->fetchAll();
  $number_of_unseen_messages = count($result);
  return $number_of_unseen_messages;
}
function GetNumberOfSeenMessages($user_id) {
  // get number of unseen messages with PDO
  global $conn;
  $sql = "SELECT * FROM messages WHERE msg_receiver = :user_id AND msg_seen = 1";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id));
  $result = $stmt->fetchAll();
  $number_of_seen_messages = count($result);
  return $number_of_seen_messages;
}


function ViewUnseenMessages($user_id) {
  global $conn;
  // use pdo to get messages
  $sql = "SELECT * FROM messages WHERE msg_receiver = :user_id AND msg_seen = 0";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id));
  $result = $stmt->fetchAll();
  return $result;
}
function ViewSeenMessages($user_id) {
  global $conn;
  // use pdo to get messages
  $sql = "SELECT * FROM messages WHERE msg_receiver = :user_id AND msg_seen = 1";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id));
  $result = $stmt->fetchAll();
  return $result;
}
function ViewSentMessages($user_id) {
  global $conn;
  // use pdo to get messages
  $sql = "SELECT * FROM messages WHERE msg_sender = :user_id";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id));
  $result = $stmt->fetchAll();
  return $result;
}
function ListMessages($result) {
  global $conn;
  if (!empty($result)) {
    foreach ($result as $message) {
      echo "<a href='/messages/view?id=" . $message['msg_id'] . "'>" . $message['msg_title'] . "</a>";
      echo "<br>";
      echo "<a href='/profile?id=" . $message['msg_sender'] . "'>" . GetUserByID($conn, $message['msg_sender'])['user_name'] . "</a><br>";
      echo "<label>" . HandleDate($message['msg_created']) . "</label>";
      echo " | ";
      echo "<label>" . IfMessageIsSeen($message) . "</label>";
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
  $sql = "INSERT INTO messages (msg_sender, msg_receiver, msg_title, msg_body, msg_created) VALUES (:sender_id, :receiver_id, :title, :body, NOW())";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':sender_id' => $sender_id, ':receiver_id' => $receiver_id, ':title' => $title, ':body' => $body));
  // redirect to messages
  header("Location: ../../messages");
}
function ViewMessage($msg_id, $user_id) {
    global $conn;
    // set message as seen
    $sql = "UPDATE messages SET message_seen = 1 WHERE msg_id = :msg_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array(':msg_id' => $msg_id));
    // get message with PDO
    $sql = "SELECT * FROM messages WHERE msg_id = :msg_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array(':msg_id' => $msg_id));
    $result = $stmt->fetch();
    return $result;
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

function IfMessageIsSeen($message) {
  if ($message['msg_seen'] == 1) {
    return "Seen";
  } else {
    return "Unseen";
  }
}