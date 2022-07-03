<?php
$UserID = $_SESSION['UserID'];
$messages = ViewSeenMessages($UserID);


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
    <a href="/messages/">Look at Unseen Messages (<?php if(GetNumberOfUnseenMessages($_SESSION['UserID']) != 0) {
     echo GetNumberOfUnseenMessages($_SESSION['UserID']); } else {
     echo 'None';}?>)</a>
  </div>
</div>