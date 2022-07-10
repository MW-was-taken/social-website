<?php
// set session timeout to 24 hours
ini_set('session.gc_maxlifetime', 86400);
// set session cookie to 24 hours
ini_set('session.cookie_lifetime', 86400);
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

// site settings functions
function GetSiteSettings() {
  global $db;
  $stmt = $db->prepare("SELECT * FROM site_settings");
  $stmt->execute();
  $result = $stmt->fetchAll();
  return $result;
}

function WebsiteAlert($site_settings) {
  if ($site_settings['alert'] == 1) {
    return true;
  }
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
    if (!preg_match("/^[a-zA-Z0-9_ ]*$/", $username)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function InvalidUsernameLength($username) {
  // user name must be atleast 3 characters long but not exceed 20 characters long
  if (strlen($username) < 3 || strlen($username) > 20) {
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

function InvalidPasswordLength($password)
{
    if (strlen($password) < 8 || strlen($password) > 50) {
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
  $statement = $pdo->prepare("INSERT INTO users (user_name, user_email, user_password, user_signup_ip, user_ip) VALUES (:username, :email, :password, :ip, :ip)");
  // hash password
  $password = password_hash($password, PASSWORD_DEFAULT);
  $statement->execute(array(':username' => $username, ':email' => $email, ':password' => $password, ':ip' => $_SERVER['REMOTE_ADDR']));
  $result = $statement->fetch();
  session_start();
  // ANCHOR session variables
  $_SESSION["UserAuthenticated"] = "true";
  $_SESSION['UserID'] = $pdo->lastInsertId();
  $_SESSION['Username'] = $username;
  $_SESSION['UserEmail'] = $email;
  $_SESSION['UserIP'] = $_SERVER['REMOTE_ADDR'];
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
function ConvertDateToTimezone($date)
{
    $date = new DateTime($date);
    $date->setTimezone(new DateTimeZone('America/New_York'));
    return $date;
}
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
      $_SESSION['UserIP'] = $_SERVER['REMOTE_ADDR'];
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

// check if status has invalid characters
function InvalidStatus($status)
{
    if (!preg_match("/^[ a-zA-Z0-9_',.|*&^%$#@!()?]*$/", $status)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}
// check if status is over 100 characters
function StatusTooLong($status)
{
    if (strlen($status) > 50) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function UpdateStatus($conn, $status_raw, $user_id) {
  // sanitize input
  $status = PurifyInput($status_raw);

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

// bio functions

function UpdateBio($conn, $bio_raw, $user_id) {
  // sanitize input
  $bio_raw_1 = PurifyInput($bio_raw);
  $bio_raw_2 = ToLineBreaks($bio_raw_1);
  $bio = ToMarkdown($bio_raw_2);

  // insert user_bio into users table
  $statement = $conn->prepare("UPDATE users SET user_bio = :bio WHERE user_id = :user_id");
  $statement->execute(array(':bio' => $bio, ':user_id' => $user_id));
  header("location: ../../dashboard/?note=Bio updated!");
}

function GetBio($conn, $user_id) {
  // get user_bio from users table
  $statement = $conn->prepare("SELECT user_bio FROM users WHERE user_id = :user_id");
  $statement->execute(array(':user_id' => $user_id));
  $result = $statement->fetch();
  return $result['user_bio'];
}

function InvalidBio($bio)
{
    if (!preg_match("/^[ a-zA-Z0-9_',.|*&^%$#@!()?`]*$/", $bio)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function BioTooLong($bio)
{
    if (strlen($bio) > 3000) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

// end bio functions
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
  $usercount = count($users);
  if ($usercount > 0) {
    foreach ($users as $user) {
      $status = $user['user_status'];
      $bio = $user['user_bio'];
      $username = $user['user_name'];
      $id = $user['user_id'];
      echo '<div class="ellipsis">';
      echo "<a href='/profile?id=" . $id . "'>" . $username . "</a>";
      if(!IfIsOnline($user['user_updated'])) {
        echo '<span class="status-dot users"></span>';
      } else {
        echo '<span class="status-dot users online"></span>';
      }
      echo '</div>';
      if (!empty($status)) {
        echo "<label>" . $status . "</label>";
      }
      echo "<hr>";
    }
  } else {
    echo "No users found!";
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
function CheckIpAddress($ip) {
  if(filter_var($ip, FILTER_VALIDATE_IP)) {
    if ($_SESSION['UserIP'] === $ip) {
      CheckIfIpIsBanned($ip);
      UpdateIP($ip);
    } else {
      return false;
    }
  } else {
    CheckIfIpIsBanned($ip);
    UpdateIP($ip);
  }
}
function UpdateIP($ip) {
  global $conn;
  // hash ip address
  $ip_hash = hash('sha256', $ip);
  // update ip address in users table
  $statement = $conn->prepare("UPDATE users SET user_ip = :ip_hash WHERE user_id = :user_id");
  $statement->execute(array(':ip_hash' => $ip_hash, ':user_id' => $_SESSION['UserID']));
}
function CheckIfIpIsBanned($ip) {
  global $conn;
  
  $statement = $conn->prepare("SELECT * FROM ip_bans WHERE ip = :ip");
  $statement->execute(array(':ip' => $ip));
  $result = $statement->fetch();
  if(!empty($result)) {
    IpBanRedirect();
  }
}
function IpBanRedirect() {
  header("location: ../../bans/ip");
  exit();
}
// this function is run everytime the user clicks on a page.
// this will be used to tell if the user is online or not.
function UpdateUser($pdo)
{
  // update user_upated field in users table to current timestamp WITHOUT NOW()
  $statement = $pdo->prepare("UPDATE users SET user_updated = CURRENT_TIMESTAMP WHERE user_id = :user_id");
  $statement->execute(array(':user_id' => $_SESSION['UserID']));
}
function IfIsOnline($updated_at_timestamp)
{
    if ($updated_at_timestamp == null) {
        return false;
    }

    // use convertdatetotimezone to convert the timestamp to the user's timezone
    $updated = ConvertDateToTimezone($updated_at_timestamp);
    $now = date_create(date('Y-m-d H:i:s'));


    $now_format = date_format($now, 'Y-m-d H:i:s');
    $updated_format =  date_format($updated, 'Y-m-d H:i:s');

    $test1 = strtotime($now_format);
    $test2 = strtotime($updated_format);
    $hour = abs($test1 - $test2) / (1 * 1);

    if ($hour < 180) {
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
  $result = $stmt->fetchAll();
  return count($result);
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
}
function SetAllMessagesAsSeen($user_id) {
  global $conn;
  $sql = "UPDATE messages SET msg_seen = 1 WHERE msg_receiver = :user_id";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id));
  header("Location: /messages?note=All messages set as seen.");
}
function ViewMessage($msg_id, $user_id) {
    global $conn;
    // set message as seen
    $sql = "UPDATE messages SET msg_seen = 1 WHERE msg_id = :msg_id";
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