<?php
$UserID = $_SESSION['UserID'];
$messages = ViewUnseenMessages($UserID);
if (isset($_GET['note'])) {
  HandleNote($_GET['note']);
}
if (isset($_GET['error'])) {
  HandleError($_GET['error']);
}

?>

<div class="row">
  <div class="col-8">
    <div class="card">
      <div class="card-header">
        Unseen Messages
      </div>
      <div class="card-body message">
        <?php
        ListMessages($messages);
        ?>
        <form action="/config/forms/messages_seen.php" method="post">
          <button type="submit" name="submit">Mark All Messages As Seen</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-4">

    <a href="/markdown" class="profile_button">Markdown Tutorial</a>
    <a href="/messages/seen" class="profile_button">Seen Messages (<?php if (GetNumberOfSeenMessages($_SESSION['UserID']) != 0) {
                                                                  echo GetNumberOfSeenMessages($_SESSION['UserID']);
                                                                } else {
                                                                  echo 'None';
                                                                } ?>)</a>
    <a href="/messages/sent" class="profile_button">Sent Messages (<?php if (GetNumberOfSentMessages($_SESSION['UserID']) != 0) {
                                                                      echo GetNumberOfSentMessages($_SESSION['UserID']);
                                                                    } else {
                                                                      echo 'None';
                                                                    } ?>)</a>