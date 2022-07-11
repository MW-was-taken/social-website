<?php
$UserID = $_SESSION['UserID'];
$messages = ViewSentMessages($UserID);


?>

<div class="card users">
  <div class="card-header">
    Messages
  </div>
  <div class="card-body message">
    <?php
      ListMessages($messages);
    ?>
    <a href="/markdown">Markdown Tutorial</a>
    |
    <a href="/messages/">Unseen Messages (<?php if(GetNumberOfUnseenMessages($_SESSION['UserID']) != 0) {
     echo GetNumberOfUnseenMessages($_SESSION['UserID']); } else {
     echo 'None';}?>)</a>
     |
              <a href="/messages/seen/">Seen Messages (<?php if(GetNumberOfSeenMessages($_SESSION['UserID']) != 0) {
     echo GetNumberOfSentMessages($_SESSION['UserID']); } else {
     echo 'None';}?>)</a>
  </div>
</div>