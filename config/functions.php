<?php
/*

CREDITS: 
Grizler
  FOR: profanity filter, and a huge help all around

Glavic (https://stackoverflow.com/users/67332/glavi%c4%87)
  FOR: time_elapsed_string()

*/

// TODO: Make headers use $_SERVER['DOCUMENT_ROOT'] instead of doing ../ infinitely


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
* @param string $db_password The password of the database
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
  if(!$row) {
    return;
  }
  if ($row['alert'] == 1) {
    // if alert link
    if ($row['alert_link'] != "") {
      echo '<div class="alert alert ' . DetermineAlertColor($row['alert_type']) . '"><i class="fa fa-exclamation-circle icon-left"></i>' . $row['alert_text'] . ' <a href="' . $row['alert_link'] . '" class="alert-link">Click here to learn more.</a><i class="fa fa-exclamation-circle icon-right"></i></div>';
    } else {
      echo '<div class="alert alert ' . DetermineAlertColor($row['alert_type']) . '"><i class="fa fa-exclamation-circle icon-left"></i>' . $row['alert_text'] . '<i class="fa fa-exclamation-circle icon-right"></i></div>';
    }
  }
  return "";
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
    case 2:
      return "purple";
    case 3:
      return "orange";
    case 4:
      return "red";
    case 5:
      return "blue";
    default:
      return "green";
  }
}
/**
 * This function checks whether the site is in maintenance or not.
 * @return bool
 */
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
/**
 * This returns the site's maintenance status. (BOOL)
 * @return bool
 */
function GetMaintenanceBool()
{
  global $conn;
  $sql = "SELECT * FROM site_settings WHERE id = 1";
  $result = $conn->query($sql);
  $row = $result->fetch();
  return $row['maintenance'];
}
/**
 * This function returns the user to the maintenance page if the user is not an admin.
 * @return void
 */
function Maintenance()
{
  // if site is in maintenance mode, redirect to maintenance page
  if (!IfAdmin($_SESSION['UserID']) && SiteMaintenance()) {
    header("Location: /maintenance");
    exit();
  }
}
/**
 * This function takes in a date and time and gets how long ago it was.
 * @author Glavic
 * @param mixed $datetime
 * @param mixed $full
 * @return string
 */
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
// ANCHOR auth functions
/**
 * Checks if the user is authenticated.
 * @return bool
 */
function UserIsAuthenticated()
{
  $session = @$_SESSION['UserAuthenticated'];
  if ($session === "true") {
    return true;
  } else {
    return false;
  }
}
/**
 * Checks if the user is authenticated and if not redirects them to the login page with an error.
 * @return void
 */
function RequireAuthentication()
{
  if (UserIsAuthenticated() === false) {
    session_start();
    $_SESSION['Error'] = "You must be logged in to access this page.";
    header("location: /login/");
    exit();
  }
}
/**
 * Checks if the user isn't logged in and if they are it redirects back to dashboard.
 * @return void
 */
function RequireGuest()
{
  if (UserIsAuthenticated() === true) {
    header("location: /dashboard");
    exit();
  }
}
// end auth functions
// status functions

/**
 * Checks if status has invalid characters.
 * @param mixed $status
 * @return bool
 */
function InvalidStatus($status)
{
  if (!preg_match("/^[ a-zA-Z0-9_',.|*&^%$#@!()?]*$/", $status)) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}
/**
 * Checks if status is too long or not.
 * @param mixed $status
 * @return bool
 */
function StatusTooLong($status)
{
  if (strlen($status) > 50) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}
/**
 * Updates the status.
 * @param mixed $conn
 * @param mixed $status_raw
 * @param mixed $user_id
 * @return void
 */

function UpdateStatus($conn, $status_raw, $user_id)
{
  // sanitize input
  $status = PurifyInput($status_raw);

  // insert user_status into users table
  $statement = $conn->prepare("UPDATE users SET user_status = :status WHERE user_id = :user_id");
  $statement->execute(array(':status' => $status, ':user_id' => $user_id));
  session_start();
  $_SESSION['note'] = "Status updated successfully.";
  header("location: /dashboard/");
}
/**
 * Gets and returns the status.
 * @param mixed $conn
 * @param mixed $user_id
 * @return mixed
 */

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
/**
 * Gets and returns the user's bio.
 * @param mixed $conn
 * @param mixed $user_id
 * @return array|string
 */
function GetBio($conn, $user_id)
{
  // get user_bio from users table
  $statement = $conn->prepare("SELECT user_bio FROM users WHERE user_id = :user_id");
  $statement->execute(array(':user_id' => $user_id));
  $result = $statement->fetch();
  $breaks =  array("<br />", "<br>", "<br/>", "<br />", "&lt;br /&gt;", "&lt;br/&gt;", "&lt;br&gt;");
  if(!empty($result['user_bio'])) {
    $bio = str_replace($breaks, "\n", $result['user_bio']);
  } else {
    $bio = "";
  }
  return $bio;
}
/**
 * Checks if the bio is too long. 
 * @param mixed $bio
 * @return bool
 */

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
/**
 * Gets all users within page limit.
 * @param mixed $page
 * @return mixed
 */
function GetUsers($page)
{
  global $conn;
  $limit = 8;
  $offset = ($page - 1) * $limit;
  $statement = $conn->prepare("SELECT * FROM users ORDER BY user_id ASC LIMIT :limit OFFSET :offset");
  $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
  $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
  $statement->execute();
  $result = $statement->fetchAll();
  return $result;
}
/**
 * Same as GetUsers but returns the staff members instead of all users.
 * @param mixed $page
 * @return mixed
 */
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
/**
 * This page lists all users, and if staff is true it lists all staff members instead.
 * @param mixed $page
 * @param mixed $staff
 * @return void
 */
function ListUsers($page, $staff = false)
{
  if($staff === false) {
    $users = GetUsers($page);
  } else {
    $users = GetStaff($page);
  }
  $usercount = count($users);
  if ($usercount > 0) {
    echo "<div class='row'>";
    foreach ($users as $user) {
?>
      <div class="col-3 no-col-padding users-col">
        <div class="center">
          <a href="/profile/?id=<?php echo $user['user_id']; ?>">
            <img src="/Avatar?id=<?php echo $user['user_id']; ?>" class="avatar" width="150">
          </a>
          <br>
          <a class="profile-link" href="/profile?id=<?php echo $user['user_id']; ?>"><?php echo $user['user_name']; OnlineDot($user['user_updated']) ?></a>
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
/**
 * Gets the user's profile.
 * @param mixed $id
 * @return mixed
 */
function HandleProfile($id)
{
  global $conn;
  $user = GetUserByID($conn, $id);
  return $user;
}
/**
 * Gets the user's profile link from their ID and username.
 * @param mixed $user_id
 * @param mixed $user_name
 * @return string
 */
function GetProfileLink($user_id, $user_name)
{
  return "<a href='/profile?id=" . $user_id . "'>" . $user_name . "</a>";
}
// end profile section

// ANCHOR handlers and misc functions
/**
 * This function purifies input by removing HTML tags and other unwanted characters.
 * @param mixed $input
 * @return string
 */
function PurifyInput($input)
{
  $input = trim($input);
  $input = stripslashes($input);
  $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
  return $input;
}
/**
 * This function converts all line breaks to <br> tags.
 * @param mixed $text
 * @return string
 */
function ToLineBreaks($text)
{
  // remove <br /> tags
  $text = str_ireplace("<br />", "", $text);
  return nl2br($text);
}
/**
 * This function converts given text to markdown if any of the relevant tags are found.
 * @param mixed $text
 * @return array|null|string
 */
function ToMarkdown($text)
{
  $text = preg_replace("#\*([^*]+)\*#", '<b>$1</b>', $text);
  $text = preg_replace("#\_([^_]+)\_#", '<i>$1</i>', $text);
  $text = preg_replace("#\%([^%]+)\%#", '<strike>$1</strike>', $text);
  $text = preg_replace("#\`([^`]+)\`#", '<code>$1</code>', $text);
  return $text;
}
/**
 * This function checks if the ip address is equal to the session ip.
 * @param mixed $ip
 * @return bool
 */
function CheckIpAddress($ip)
{
  CheckIfIpIsBanned($ip);
  UpdateIP($ip);
}
/**
 * This function updates the ip address in the database.
 * @param mixed $ip
 * @return void
 */
function UpdateIP($ip)
{
  global $conn;
  // hash ip address
  $ip_hash = hash('sha256', $ip);
  // update ip address in users table
  $statement = $conn->prepare("UPDATE users SET user_ip = :ip_hash WHERE user_id = :user_id");
  $statement->execute(array(':ip_hash' => $ip_hash, ':user_id' => $_SESSION['UserID']));
}
/**
 * This function checks if the ip address is banned.
 * @param mixed $ip
 * @return void
 */
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
/**
 * This function redirects the user to the banned ip page.
 * @return void
 */
function IpBanRedirect()
{
  header("location: /bans/ip");
  exit();
}
/**
 * This function updates the user's last activity.
 * @return void
 */
function UpdateUser($pdo)
{
  // update user_upated field in users table to current timestamp WITHOUT NOW()
  $statement = $pdo->prepare("UPDATE users SET user_updated = CURRENT_TIMESTAMP WHERE user_id = :user_id");
  $statement->execute(array(':user_id' => $_SESSION['UserID']));
}
/**
 * This function checks if the user is logged in and active.
 * @param mixed $updated_at_timestamp
 * @return bool
 */
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
/**
 * This function gets the number of users.
 * @param mixed $pdo
 * @return int
 */
function GetNumberOfUsers($pdo)
{
  // use pdo to get number of users
  $sql = "SELECT * FROM users";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetchAll();
  return count($result);
}
/**
 * This function gets all user info by their id.
 * @param mixed $pdo
 * @param mixed $id
 * @return mixed
 */
function GetUserByID($pdo, $id)
{
  // use pdo to get user by id
  $sql = "SELECT * FROM users WHERE user_id = :user_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':user_id' => $id));
  $result = $stmt->fetch();
  return $result;
}
/**
 * This function gets date in MM DD, YYYY format.
 * @param mixed $date
 * @return string
 */
function HandleDate($date)
{
  $date_formatted = date("F j, Y", strtotime($date));
  return $date_formatted;
}
/**
 * This function checks if an erorr is set and displays it through a toast.
 * @param mixed $type
 * @return void
 */
function HandleError($type)
{
  if (isset($type)) {
    echo '<div class="toast-wrapper">
    <div class="toast error" id="toast">
      <div class="container-1 error">
        <i class="fas fa-times-square"></i>
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
/**
 * This function checks if a note is set and displays it through a toast.
 * @param mixed $type
 * @return void
 */
function HandleNote($type)
{
  if (isset($type)) {
    echo '<div class="toast-wrapper">
    <div class="toast" id="toast">
      <div class="container-1">
        <i class="fas fa-check-square"></i>
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
function GetUserLogs($page)
{
  global $conn;
  $limit = 5;
  $offset = ($page - 1) * $limit;
  $statement = $conn->prepare("SELECT * FROM logs ORDER BY id DESC LIMIT :limit OFFSET :offset");
  $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
  $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
  $statement->execute();
  $result = $statement->fetchAll();
  return $result;
}

function UserLog($user_id, $action)
{
  global $conn;
  $sql = "INSERT INTO logs (user, message, created) VALUES (:user_id, :action, NOW())";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id, ':action' => $action));
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
  $user_id = $_SESSION['UserID'];
  $user = GetUserByID($conn, $user_id);
  return '<a href="/profile"><i class="fas fa-user"></i>' . $user['user_name'] . '</a>';
}

function HomeLink()
{
  return '<a href="/dashboard">
  <i class="fas fa-home"></i>Dashboard
  </a>';
}

function MessageLink()
{
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

function AdminLink()
{
  $user_id = $_SESSION['UserID'];
  if (IfAdmin($user_id)) {
    return '<a href="/admin">
      <i class="fa fa-cogs" aria-hidden="true"></i>Admin
      </a>';
  }
}

function GetTheme()
{
  global $conn;
  session_start();
  if (isset($_SESSION['Theme'])) {
    return $_SESSION['Theme'];
  } else {
    $user_id = $_SESSION['UserID'];
    $user = GetUserByID($conn, $user_id);
    $_SESSION['Theme'] = $user['user_theme'];
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
  if($theme_id == 5) {
    return '<link rel="stylesheet" href="/css/grey_theme.css">';
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

function GetWallPostAmount($user_id)
{
  global $conn;
  $sql = "SELECT * FROM wall WHERE wall_creator = :user_id";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id));
  $result = $stmt->fetchAll();
  return count($result);
}

function SetUserFlood($user_id)
{
  global $conn;
  $sql = "UPDATE users SET user_flood = NOW() WHERE user_id = :user_id";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id));
}

function Flood($user_id, $seconds)
{
  global $conn;
  $sql = "SELECT user_flood FROM users WHERE user_id = :user_id";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':user_id' => $user_id));
  $result = $stmt->fetchAll();
  $last_flood = $result[0]['user_flood'];
  $diff = strtotime(date("Y-m-d H:i:s")) - strtotime($last_flood);
  if ($diff < $seconds) {
    return true;
  } else {
    return false;
  }
}