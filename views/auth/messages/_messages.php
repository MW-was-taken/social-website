<?php
$UserID = $_SESSION['UserID'];
$messages = ViewUnseenMessages($UserID);
if(isset($_GET['note'])) {
  HandleNote($_GET['note']);
}
if(isset($_GET['error'])) {
  HandleError($_GET['error']);
}

?>

<div class="card users">
  <div class="card-header">
    Messages
  </div>
  <div class="card-body message">
    <?php
      ListMessages($messages);
    ?>
    <form action="/config/forms/messages_seen.php" method="post">
      <button type="submit" name="submit">Mark All Messages As Seen</button>
    </form>
    <hr>
    <a href="/markdown">Markdown Tutorial</a>
    |
    <a href="/messages/seen">Seen Messages (<?php if(GetNumberOfSeenMessages($_SESSION['UserID']) != 0) {
     echo GetNumberOfSeenMessages($_SESSION['UserID']); } else {
     echo 'None';}?>)</a>
         <a href="/messages/sent">Look at Sent Messages (<?php if(GetNumberOfSentMessages($_SESSION['UserID']) != 0) {
     echo GetNumberOfSentMessages($_SESSION['UserID']); } else {
     echo 'None';}?>)</a>
  </div>
</div>