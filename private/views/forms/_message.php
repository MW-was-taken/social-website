<?php
if (isset($_POST["submit"])) {
  $title = $_POST["title"];
  $body = $_POST["body"];
  $sender_id = $_POST["sender_id"];
  $receiver_id = $_POST["receiver_id"];
  include_once $_SERVER['DOCUMENT_ROOT'] . '/config/profanity.php';


  if (empty($title) || empty($body)) {
    $_SESSION["error"] = "Title or body is empty!";
    header("location: /messages/");
    exit();
  }
  if ($sender_id == $receiver_id) {
    $_SESSION["error"] = "You can't send a message to yourself!";
    header("location: /messages/");
    exit();
  }
  if ($sender_id != $_SESSION["UserID"]) {
    $_SESSION["error"] = "You are not the sender!";
    header("location: /messages/");
    exit();
  }

  if(Flood($sender_id, 60)) {
    $_SESSION['error'] = "Try again in 60 seconds!";
    header("Location: /dashboard");
    exit();
  }

  $body = PurifyInput($body);
  $body = ToMarkdown($body);
  $title = ProfanityFilter($body);
  $title = ProfanityFilter($body);
  $title = PurifyInput($title);
  $body = ToLineBreaks($body);
  $sql = "INSERT INTO messages (msg_sender, msg_receiver, msg_title, msg_body, msg_created) VALUES (:sender_id, :receiver_id, :title, :body, NOW())";
  $stmt = $conn->prepare($sql);
  $stmt->execute(array(':sender_id' => $sender_id, ':receiver_id' => $receiver_id, ':title' => $title, ':body' => $body));
  SetUserFlood($sender_id);
  $_SESSION['note'] = "Message sent!";
  header("location: /messages/");
  exit();
}
// TODO: check for exploits here
$User = GetUserByID($conn, $_GET['id']);
if(!$User) {
  $_SESSION["error"] = "User not found!";
  header("location: /messages/");
  exit();
}
?>
<div class="card form-card message">
  <div class="card-header">
    Send Message to <?php echo $User['user_name']; ?>
  </div>
  <form method="POST">
    <div class="card-body">
      <input type="text" name="title" placeholder="Title...">
      <textarea name="body" id="body" cols="30" rows="10" placeholder="Hey <?php echo $User['user_name']; ?>"></textarea>
      <input type="hidden" value="<?php echo $_SESSION['UserID'] ?>" name="sender_id">
      <input type="hidden" value="<?php echo $_GET['id'] ?>" name="receiver_id">
      <button type="submit" name="submit">Send Message</button>
    </div>
  </form>
</div>