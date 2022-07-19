<?php
/*

CREDITS: 
Grizler
  FOR: profanity filter, and a huge help all around

Glavic (https://stackoverflow.com/users/67332/glavi%c4%87)
  FOR: time_elapsed_string()

*/


if (@$_SESSION['UserID'] == null) {
  ini_set('session.gc.maxlifetime', 60 * 60 * 24 * 30); // 30 days
  session_set_cookie_params(60 * 60 * 24 * 30); // 30 days
}
// page functions
/**
 * This function sets the page's title.
 * If there is no title present, it will just be the website's name.
 * 
 */
function HandlePageName($name)
{
  if (empty($name)) {
    return "Brick-Town";
  }
  return $name . " - " . "Brick-Town";
}
// config functions

/**
 * This function is the master of it all. This function handles the database connection.
 * 
 * @param string $db_host The hostname of the database.
 * @param string $db_username The username of the database.
 * @param string $db_password The password of the database.
 * @param string $db The name of the database.
 * @return object The connection to the database.
 */

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


// site settings functions

/**
 * This function returns the alert if present.
 * @return string
 */

function Alert()
{
  global $conn;
  $sql = "SELECT * FROM site_settings WHERE id = 1";
  $result = $conn->query($sql);
  $row = $result->fetch();
  if ($row['alert'] == 1) {
    // if alert link
    if ($row['alert_link'] != "") {
      echo '<div class="alert alert ' . DetermineAlertColor($row['alert_type']) . '"><i class="fa-solid fa-circle-exclamation icon-left"></i>' . $row['alert_text'] . ' <a href="' . $row['alert_link'] . '" class="alert-link">Click here to learn more.</a><i class="fa-solid fa-circle-exclamation icon-right"></i></div>';
    } else {
      echo '<div class="alert alert ' . DetermineAlertColor($row['alert_type']) . '"><i class="fa-solid fa-circle-exclamation icon-left"></i>' . $row['alert_text'] . '<i class="fa-solid fa-circle-exclamation icon-right"></i></div>';
    }
  } else {
    return "";
  }
}

/**
 * This function updates the alert.
 * @param  string $alert_bool Determines if the alert is enabled or disabled. (required)
 * @param  string $alert_text The text of the alert. (required)
 * @param  string $alert_link The link of the alert. (optional)
 * @param  string $alert_type The type of the alert. (required)
 */

function UpdateAlert($alert_bool, $alert_text, $alert_link, $alert_type)
{
  global $conn;
  session_start();
  // StaffLog
  $color = DetermineAlertColor($alert_type);
  $staff_log_string = "Updated alert to say: " . $alert_text . "<br> Updated alert link: " . $alert_link . "<br> Updated alert color: " . $color;
  StaffLog($_SESSION['UserID'], $staff_log_string);

  $sql = "UPDATE site_settings SET alert = :alert_bool, alert_text = :alert_text, alert_link = :alert_link, alert_type = :alert_type WHERE id = 1";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':alert_bool', $alert_bool);
  $stmt->bindParam(':alert_text', $alert_text);
  $stmt->bindParam(':alert_link', $alert_link);
  $stmt->bindParam(':alert_type', $alert_type);
  $stmt->execute();
}

/**
 * This function returns the alert's text.
 * @return string
 */

function GetAlertText()
{
  global $conn;
  $sql = "SELECT * FROM site_settings WHERE id = 1";
  $result = $conn->query($sql);
  $row = $result->fetch();
  return $row['alert_text'];
}

/**
 * This function returns the alert type which is used to determine the color of the alert.
 * 
 * @return string
 */

function GetAlertType()
{
  global $conn;
  $sql = "SELECT * FROM site_settings WHERE id = 1";
  $result = $conn->query($sql);
  $row = $result->fetch();
  return $row['alert_type'];
}
/** 
 * This function returns the boolean that determines if the alert is enabled or not.
 * 
 * @return string
 */

function GetAlertBool()
{
  global $conn;
  $sql = "SELECT * FROM site_settings WHERE id = 1";
  $result = $conn->query($sql);
  $row = $result->fetch();
  return $row['alert'];
}

/**
 * This function returns the alert link if present.
 * @return string
 */

function GetAlertLink()
{
  global $conn;
  $sql = "SELECT * FROM site_settings WHERE id = 1";
  $result = $conn->query($sql);
  $row = $result->fetch();
  return $row['alert_link'];
}

/**
 * This function returns the alert color based on the alert type.
 * @param  string $alert_type The type of the alert. (required)
 * @return string
 */

function DetermineAlertColor($type)
{
  switch ($type) {
    case 1:
      return "green";
      break;
    case 2:
      return "purple";
      break;
    case 3:
      return "orange";
      break;
    case 4:
      return "red";
      break;
    case 5:
      return "blue";
      break;
    default:
      return "green";
      break;
  }
}

function SiteMaintenance()
{
  global $conn;
  $sql = "SELECT * FROM site_settings WHERE id = 1";
  $result = $conn->query($sql);
  $row = $result->fetch();
  if ($row['maintenance'] == 1) {
    return true;
  } else {
    return false;
  }
}

function UpdateMaintenance($maintenance_bool)
{
  global $conn;
  session_start();

  // if maintenance bool is not 1 or 0, set it to 1
  if ($maintenance_bool != 1 && $maintenance_bool != 0) {
    $maintenance_bool = 1;
  }

  if ($maintenance_bool == 1) {
    // StaffLog
    StaffLog($_SESSION['UserID'], "UPDATED MAINTENANCE: ENABLED: " . $maintenance_bool);
    // update alert to maintenance alert
    UpdateAlert(1, "Welcome admins. Site is currently under maintenance.", "", 2);
  } else {
    // StaffLog
    StaffLog($_SESSION['UserID'], "UPDATED MAINTENANCE: DISABLED");
    // update alert to normal alert
    UpdateAlert(0, "", "", 0);
  }

  $sql = "UPDATE site_settings SET maintenance = :maintenance_bool WHERE id = 1";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':maintenance_bool', $maintenance_bool);
  $stmt->execute();
}

function GetMaintenanceBool()
{
  global $conn;
  $sql = "SELECT * FROM site_settings WHERE id = 1";
  $result = $conn->query($sql);
  $row = $result->fetch();
  return $row['maintenance'];
}

function Maintenance()
{
  // if site is in maintenance mode, redirect to maintenance page
  if (!IfAdmin($_SESSION['UserID']) && SiteMaintenance()) {
    header("Location: /maintenance");
    exit();
  }
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

function InvalidUsernameLength($username)
{
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
  session_start();
  // ANCHOR session variables
  $_SESSION["UserAuthenticated"] = "true";
  $_SESSION['UserID'] = $pdo->lastInsertId();
  $_SESSION['Username'] = $username;
  $_SESSION['UserEmail'] = $email;
  $_SESSION['UserIP'] = $_SERVER['REMOTE_ADDR'];
  $_SESSION['Theme'] = 0;
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
function time_elapsed_string($datetime, $full = false)
{
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
      $_SESSION['last_ip'] = $result['user_ip'];
      $_SESSION['UserIP'] = $_SERVER['REMOTE_ADDR'];
      $_SESSION['Theme'] = $result['user_theme'];
      header("location: ../../dashboard/?note=Successfully logged in!");
      exit();
    } else {
      header("location: ../../login/?error=Invalid password!");
      exit();
    }
  } else {
    header("location: ../../login/?error=Invalid username!");
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
function RequireAuthentication()
{
  if (UserIsAuthenticated() === false) {
    header("location: ../login/?error=You must be logged in to do this!");
    exit();
  }
}
function RequireGuest()
{
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

function UpdateStatus($conn, $status_raw, $user_id)
{
  // sanitize input
  $status = PurifyInput($status_raw);

  // insert user_status into users table
  $statement = $conn->prepare("UPDATE users SET user_status = :status WHERE user_id = :user_id");
  $statement->execute(array(':status' => $status, ':user_id' => $user_id));
  header("location: ../../dashboard/?note=Status updated!");
}

function GetStatus($conn, $user_id)
{
  // get user_status from users table
  $statement = $conn->prepare("SELECT user_status FROM users WHERE user_id = :user_id");
  $statement->execute(array(':user_id' => $user_id));
  $result = $statement->fetch();
  return $result['user_status'];
}
// end status functions

// bio functions

function UpdateBio($conn, $bio_raw, $user_id)
{
  // sanitize input
  $bio_raw_1 = PurifyInput($bio_raw);
  $bio = ToMarkdown($bio_raw_1);

  // insert user_bio into users table
  $statement = $conn->prepare("UPDATE users SET user_bio = :bio WHERE user_id = :user_id");
  $statement->execute(array(':bio' => $bio, ':user_id' => $user_id));
  header("location: ../../settings/?note=Bio updated!");
}

function GetBio($conn, $user_id)
{
  // get user_bio from users table
  $statement = $conn->prepare("SELECT user_bio FROM users WHERE user_id = :user_id");
  $statement->execute(array(':user_id' => $user_id));
  $result = $statement->fetch();
  $breaks =  array("<br />", "<br>", "<br/>", "<br />", "&lt;br /&gt;", "&lt;br/&gt;", "&lt;br&gt;");
  $bio = str_ireplace($breaks, "", $result['user_bio']);
  return $bio;
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
function GetUsers($page)
{
  global $conn;
  $limit = 12;
  $offset = ($page - 1) * $limit;
  $statement = $conn->prepare("SELECT * FROM users ORDER BY user_id ASC LIMIT :limit OFFSET :offset");
  $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
  $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
  $statement->execute();
  $result = $statement->fetchAll();
  return $result;
}

function GetStaff($page)
{
  global $conn;
  $limit = 12;
  $offset = ($page - 1) * $limit;
  $statement = $conn->prepare("SELECT * FROM users WHERE user_admin = 2 OR user_admin = 3 OR user_admin = 4 OR user_admin = 5 ORDER BY user_id ASC LIMIT :limit OFFSET :offset");
  $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
  $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
  $statement->execute();
  $result = $statement->fetchAll();
  return $result;
}

function ListUsers($page)
{
  $users = GetUsers($page);
  $usercount = count($users);
  if ($usercount > 0) {
    echo "<div class='row'>";
    foreach ($users as $user) {
?>
      <div class="col-3 no-col-padding users-col">
        <div class="center">
        <a href="/profile/?id=<?php echo $user['user_id']; ?>">
        <img src="/avatar?id=<?php echo $user['user_id']; ?>" class="avatar" width="150">
        </a>
        <br>
        <a class="profile-link" href="/profile?id=<?php echo $user['user_id']; ?>"><?php echo $user['user_name']; ?></a>
        </div>
      </div>
    <?php
    }
    echo "</div>";

  } else {
    echo "No users found!";
  }
}
function ListStaff($page)
{
  $users = GetStaff($page);
  $usercount = count($users);
  if ($usercount > 0) {
    echo "<div class='row'>";
    foreach ($users as $user) {
?>
      <div class="col-3 no-col-padding users-col">
        <div class="center">
        <a href="/profile/?id=<?php echo $user['user_id']; ?>">
        <img src="/avatar?id=<?php echo $user['user_id']; ?>" class="avatar" width="150">
        </a>
        <br>
        <a class="profile-link" href="/profile?id=<?php echo $user['user_id']; ?>"><?php echo $user['user_name']; ?></a>
        </div>
      </div>
    <?php
    }
    echo "</div>";
  } else {
    echo "No users found!";
  }
}
// ANCHOR profile sectuion
function HandleProfile($id)
{
  global $conn;
  $user = GetUserByID($conn, $id);
  return $user;
}
function GetProfileLink($user_id, $user_name)
{
  return "<a href='/profile?id=" . $user_id . "'>" . $user_name . "</a>";
}
// end profile section

// ANCHOR handlers and misc functions
// prevents xss attacks and trims whitespace
function PurifyInput($input)
{
  $input = trim($input);
  $input = stripslashes($input);
  $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
  return $input;
}

function ToLineBreaks($text)
{
  // remove <br /> tags
  $text = str_ireplace("<br />", "", $text);
  return nl2br($text);
}

function ToMarkdown($text)
{
  $text = preg_replace("#\*([^*]+)\*#", '<b>$1</b>', $text);
  $text = preg_replace("#\_([^_]+)\_#", '<i>$1</i>', $text);
  $text = preg_replace("#\%([^%]+)\%#", '<strike>$1</strike>', $text);
  $text = preg_replace("#\`([^`]+)\`#", '<code>$1</code>', $text);
  return $text;
}
function CheckIpAddress($ip)
{
  if (filter_var($ip, FILTER_VALIDATE_IP)) {
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
function UpdateIP($ip)
{
  global $conn;
  // hash ip address
  $ip_hash = hash('sha256', $ip);
  // update ip address in users table
  $statement = $conn->prepare("UPDATE users SET user_ip = :ip_hash WHERE user_id = :user_id");
  $statement->execute(array(':ip_hash' => $ip_hash, ':user_id' => $_SESSION['UserID']));
}
function CheckIfIpIsBanned($ip)
{
  global $conn;

  $statement = $conn->prepare("SELECT * FROM ip_bans WHERE ip = :ip");
  $statement->execute(array(':ip' => $ip));
  $result = $statement->fetch();
  if (!empty($result)) {
    IpBanRedirect();
  }
}
function IpBanRedirect()
{
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
  $now = date_create(date('Y-m-d H:i:s'));
  $updated = date_create($updated_at_timestamp);

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
function GetNumberOfUsers($pdo)
{
  // use pdo to get number of users
  $sql = "SELECT * FROM users";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetchAll();
  return count($result);
}

function GetUserByID($pdo, $id)
{
  // use pdo to get user by id
  $sql = "SELECT * FROM users WHERE user_id = :user_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':user_id' => $id));
  $result = $stmt->fetch();
  return $result;
}
function HandleDate($date)
{
  $date_formatted = date("F j, Y", strtotime($date));
  return $date_formatted;
}
function HandleError($type)
{
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
function HandleNote($type)
{
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
function UnseenMessages($user_id)
{
  $message_number = GetNumberOfUnseenMessages($user_id);
  if ($message_number > 0) {
    return $message_number;
  } else {
    return false;
  }
}
function GetNumberOfUnseenMessages($user_id)
{
  // get number of unseen messages with PDO
  global $conn;
  // order by message_id desc to get the latest message first
  $sql = "SELECT * FROM messages WHERE msg_receiver = :user_id AND msg_seen = 0 ORDER BY msg_id DESC";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id));
  $result = $stmt->fetchAll();
  $number_of_unseen_messages = count($result);
  return $number_of_unseen_messages;
}
function GetNumberOfSeenMessages($user_id)
{
  // get number of unseen messages with PDO
  global $conn;
  // order by message_id desc to get the latest message first
  $sql = "SELECT * FROM messages WHERE msg_receiver = :user_id AND msg_seen = 1 ORDER BY msg_id DESC";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id));
  $result = $stmt->fetchAll();
  $number_of_seen_messages = count($result);
  return $number_of_seen_messages;
}
function GetNumberOfSentMessages($user_id)
{
  // get number of unseen messages with PDO
  global $conn;
  // order by message_id desc to get the latest message first
  $sql = "SELECT * FROM messages WHERE msg_sender = :user_id ORDER BY msg_id DESC";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id));
  $result = $stmt->fetchAll();
  $number_of_sent_messages = count($result);
  return $number_of_sent_messages;
}


function ViewUnseenMessages($user_id)
{
  global $conn;
  // use pdo to get messages
  $sql = "SELECT * FROM messages WHERE msg_receiver = :user_id AND msg_seen = 0";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id));
  $result = $stmt->fetchAll();
  return $result;
}
function ViewSeenMessages($user_id)
{
  global $conn;
  // use pdo to get messages
  $sql = "SELECT * FROM messages WHERE msg_receiver = :user_id AND msg_seen = 1";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id));
  $result = $stmt->fetchAll();
  return $result;
}
function ViewSentMessages($user_id)
{
  global $conn;
  // use pdo to get messages
  $sql = "SELECT * FROM messages WHERE msg_sender = :user_id";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id));
  $result = $stmt->fetchAll();
  return $result;
}
function ListMessages($result)
{
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
function SendMessage($sender_id, $receiver_id, $title_unpurified, $body_unpurified)
{
  global $conn;
  $body_sanitized = PurifyInput($body_unpurified);
  $body_markdown = ToMarkdown($body_sanitized);
  // profanity filter
  include_once '../profanity.php';

  $body_profanity = ProfanityFilter($body_markdown);
  $title_profanity = ProfanityFilter($title_unpurified);
  $title = PurifyInput($title_profanity);
  $body = ToLineBreaks($body_profanity);
  $sql = "INSERT INTO messages (msg_sender, msg_receiver, msg_title, msg_body, msg_created) VALUES (:sender_id, :receiver_id, :title, :body, NOW())";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':sender_id' => $sender_id, ':receiver_id' => $receiver_id, ':title' => $title, ':body' => $body));
}
function SetAllMessagesAsSeen($user_id)
{
  global $conn;
  $sql = "UPDATE messages SET msg_seen = 1 WHERE msg_receiver = :user_id";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id));
  header("Location: /messages?note=All messages set as seen.");
}
function ViewMessage($msg_id, $user_id)
{
  // i do not know how this works

  // check if message exists
  global $conn; 
  // limit 
  $sql = "SELECT * FROM messages WHERE msg_id = :msg_id";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':msg_id' => $msg_id));
  $result = $stmt->fetchAll();
  if (!empty($result)) {
    // check if message is for user
    if ($result[0]['msg_receiver'] == $user_id || $result[0]['msg_sender'] == $user_id) {
      // set message as seen
      // if message receiver is user, set message as seen
      if ($result[0]['msg_receiver'] == $user_id) {
        $sql = "UPDATE messages SET msg_seen = 1 WHERE msg_id = :msg_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(':msg_id' => $msg_id));
      }
      // get message
      $sql = "SELECT * FROM messages WHERE msg_id = :msg_id";
      $stmt = $conn->prepare($sql);
      $stmt->execute(array(':msg_id' => $msg_id));
      $msg_result = $stmt->fetchAll();
      // return message
      return $msg_result[0];
    } else {
      header("Location: /messages?error=You do not have permission to view this message.");
    }
  } else {
    header("Location: /messages?error=Message does not exist.");
  }
}

function GetMessageTitle($message)
{
  return $message['msg_title'];
}

function GetMessageBody($message)
{
  return $message['msg_body'];
}

function GetMessageSender($message)
{
  return $message['msg_sender'];
}

function GetMessageDate($message)
{
  return time_elapsed_string($message['msg_created']);
}

function IfMessageIsSeen($message)
{
  if ($message['msg_seen'] == 1) {
    return "Seen";
  } else {
    return "Unseen";
  }
}
// end of messages functions
// badge functions
function GetBadge($user_id)
{
  global $conn;
  $sql = "SELECT * FROM badges WHERE user_id = :user_id";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':badge_owner' => $user_id));
  $result = $stmt->fetchAll();
  return $result;
}

function HandleBadgeColor($badge_color)
{
  // gh copilot is overpowered
  if ($badge_color == "red") {
    return "danger";
  } else if ($badge_color == "green") {
    return "success";
  } else if ($badge_color == "blue") {
    return "primary";
  } else if ($badge_color == "yellow") {
    return "warning";
  } else if ($badge_color == "orange") {
    return "info";
  } else {
    return "secondary";
  }
}
// end profile badge functions
// friend functions
function GetFriends($user_id)
{
  // get * from friends where receiver or sender is user_id but is not request
  global $conn;
  $sql = "SELECT * FROM friends WHERE (receiver = :user_id AND request = 0) OR (sender = :user_id AND request = 0)";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id));
  $result = $stmt->fetchAll();
  return $result;
}

function GetFriendRequestsReceived($user_id)
{
  // get * from friends where receiver or sender is user_id but is request
  global $conn;
  $sql = "SELECT * FROM friends WHERE (receiver = :user_id AND request = 1)";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id));
  $result = $stmt->fetchAll();
  return $result;
}

function GetFriendRequestsSent($user_id)
{
  // get * from friends where receiver or sender is user_id but is request
  global $conn;
  $sql = "SELECT * FROM friends WHERE sender = :user_id AND request = 1";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id));
  $result = $stmt->fetchAll();
  return $result;
}

function SendFriendRequest($sender_id, $receiver_id)
{
  global $conn;
  $sql = "INSERT INTO friends (sender, receiver, time_sent) VALUES (:sender_id, :receiver_id, NOW())";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':sender_id' => $sender_id, ':receiver_id' => $receiver_id));
}

function AcceptFriendRequest($sender_id, $receiver_id)
{
  global $conn;
  $sql = "UPDATE friends SET request = 0 WHERE sender = :sender_id AND receiver = :receiver_id";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':sender_id' => $sender_id, ':receiver_id' => $receiver_id));
}

function DeclineFriendRequest($sender_id, $receiver_id)
{
  global $conn;
  $sql = "DELETE FROM friends WHERE sender = :sender_id AND receiver = :receiver_id";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':sender_id' => $sender_id, ':receiver_id' => $receiver_id));
}

// end of friend functions
// admin functions
function IfAdmin($user_id)
{
  global $conn;
  // get * from users where user_id is user_id and admin is 2 or higher
  $sql = "SELECT * FROM users WHERE user_id = :user_id AND user_admin >= 3";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id));
  $result = $stmt->fetchAll();
  // if user is admin return true
  if (count($result) > 0) {
    return true;
  } else {
    return false;
  }
}
function RequireAdmin()
{
  if (!UserIsAuthenticated()) {
    header("Location: /login");
  }
  if (!IfAdmin($_SESSION['UserID'])) {
    header("Location: /");
  }
}
function StaffLog($user_id, $action)
{
  global $conn;
  $sql = "INSERT INTO staff_log (log_user_id, log_action, time_logged) VALUES (:user_id, :action, NOW())";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id, ':action' => $action));
}

function GetLogs($page)
{
  global $conn;
  $limit = 5;
  $offset = ($page - 1) * $limit;
  $statement = $conn->prepare("SELECT * FROM staff_log ORDER BY log_id DESC LIMIT :limit OFFSET :offset");
  $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
  $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
  $statement->execute();
  $result = $statement->fetchAll();
  return $result;
}

function CheckIfUserExists($user_id)
{
  global $conn;
  $sql = "SELECT * FROM users WHERE user_id = :user_id";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id));
  $result = $stmt->fetchAll();
  if (count($result) > 0) {
    return true;
  } else {
    return false;
  }
}

// end of admin functions
// links
function ProfileLink()
{
  global $conn;
  if (UserIsAuthenticated()) {
    $user_id = $_SESSION['UserID'];
    $user = GetUserByID($conn, $user_id);
    return '<a href="/profile"><i class="fas fa-user"></i>' . $user['user_name'] . '</a>';
  }
}

function HomeLink()
{
  if (UserIsAuthenticated()) {
    return '<a href="/dashboard">
    <i class="fas fa-home"></i>Dashboard
    </a>';
  } else {
    return '<a href="/">Home</a>';
  }
}

function MessageLink()
{
  if (UserIsAuthenticated()) {
    // get user_id from session
    $user_id = $_SESSION['UserID'];
    // get number of unread messages
    $messages = UnseenMessages($user_id);
    if ($messages > 0) {
      return '<a href="/messages">
      <i class="fas fa-envelope"></i>Messages <span class="badge">' . $messages . '</span></a>';
    } else {
      return '<a href="/messages"><i class="fas fa-envelope"></i>Messages</a>';
    }
  }
}

function AdminLink()
{
  if (UserIsAuthenticated()) {
    $user_id = $_SESSION['UserID'];
    if (IfAdmin($user_id)) {
      return '<a href="/admin">
      <i class="fa fa-cogs" aria-hidden="true"></i>Admin
      </a>';
    }
  }
}

function GetTheme()
{
  global $conn;
  if (UserIsAuthenticated()) {
    session_start();
    if (isset($_SESSION['Theme'])) {
      return $_SESSION['Theme'];
    } else {
      $user_id = $_SESSION['UserID'];
      $user = GetUserByID($conn, $user_id);
      $_SESSION['Theme'] = $user['user_theme'];
    }
  }
}

function UpdateTheme($theme, $user_id)
{
  global $conn;
  if (UserIsAuthenticated()) {
    $sql = "UPDATE users SET user_theme = :theme WHERE user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array(':theme' => $theme, ':user_id' => $user_id));
    session_start();
    $_SESSION['Theme'] = $theme;
  }
}

function HandleTheme($theme_id)
{
  if ($theme_id == 2) {
    return '<link rel="stylesheet" href="/css/grizlers_theme.css">';
  }
  if ($theme_id == 3) {
    return '<link rel="stylesheet" href="/css/elfos_theme.css">';
  }
  if ($theme_id == 4) {
    return '<link rel="stylesheet" href="/css/cool_theme.css">';
  }
}

//  forum functions
function GetForumCategories()
{
  global $conn;
  $sql = "SELECT * FROM categories";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetchAll();
  return $result;
}

function CreateCategory($cat_name, $cat_description, $cat_creator, $locked)
{
  global $conn;
  $sql = "INSERT INTO categories (cat_name, cat_desc, cat_creator, cat_created, cat_admin) VALUES (:cat_name, :cat_description, :cat_creator, NOW(), :cat_admin)";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':cat_name' => $cat_name, ':cat_description' => $cat_description, ':cat_creator' => $cat_creator, ':cat_admin' => $locked));
}

function GetAvatar($user_id)
{
  return "/Avatar?id=" . $user_id;
}

function OnlineDot($last_online, $float = false)
{
  if (!$float) {
    if (!IfIsOnline($last_online)) {
      echo '<span class="status-dot users no-float"></span>';
    } else {
      echo '<span class="status-dot users online no-float"></span>';
    }
  } else {
    if (!IfIsOnline($last_online)) {
      echo '<span class="status-dot users"></span>';
    } else {
      echo '<span class="status-dot users online"></span>';
    }
  }
}

// Catalog functions

function UploadMarketItem($item_name, $item_desc, $item_price, $item_type)
{
  global $conn;
  $sql = "INSERT INTO market (item_name, item_desc, item_price, item_type) VALUES (:item_name, :item_desc, :item_price, :item_type)";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':item_name' => $item_name, ':item_desc' => $item_desc, ':item_price' => $item_price, ':item_type' => $item_type));
}

function GetMarketItemByID($item_id)
{
  global $conn;
  $sql = "SELECT * FROM market WHERE item_id = :item_id";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':item_id' => $item_id));
  $result = $stmt->fetchAll();
  return $result;
}

function GetMarketItemByName($item_name)
{
  global $conn;
  $sql = "SELECT * FROM market WHERE item_name = :item_name";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':item_name' => $item_name));
  $result = $stmt->fetchAll();
  return $result;
}
function GetLastMarketItem()
{
  // get last id
  global $conn;
  $sql = "SELECT item_id FROM market ORDER BY item_id DESC LIMIT 1";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetchAll();
  return $result[0]['item_id'];
}

function GetItems($item_type = "all")
{
  if ($item_type == "all") {
    global $conn;
    $sql = "SELECT * FROM market";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
  } else {
    global $conn;
    $sql = "SELECT * FROM market WHERE item_type = :item_type";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array(':item_type' => $item_type));
    $result = $stmt->fetchAll();
    return $result;
  }
}

function ListItems()
{
  global $conn;
  // for every 4 items, create a new row
  $items = GetItems();
  $itemcount = count($items);
  echo '<div class="row">';
  foreach ($items as $item) {
    ?>
    <div class="col-3">
      <div class="card no-header">
        <div class="card-body">
          <div class="center">
            <a href="/market/item?id=<?php echo $item['item_id']; ?>">
              <img src="/Avatar/Thumbnail/?id=<?php echo $item['item_id']; ?>" width="150">
            </a>
          </div>
          <hr>
            <a href="/market/item?id=<?php echo $item['item_id']; ?>">
              <h3 style="color: white;"><?php echo $item['item_name']; ?></h3>
            </a>
            <a href="/profile/<?php echo $item['item_creator']; ?>">
              <h5><?php echo GetUserByID($conn, $item['item_creator'])['user_name']; ?></h5>
            </a>
        </div>
      </div>
    </div>
<?php
  }
  echo "</div>";
}
