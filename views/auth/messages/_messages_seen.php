<?php
$UserID = $_SESSION['UserID'];
$messages = ViewSeenMessages($UserID);


?>
<div class="row">
  <div class="col-8">
    <div class="card">
      <div class="card-header">
        Seen Messages
      </div>
      <div class="card-body message">
        <?php
        ListMessages($messages);
        ?>
      </div>
    </div>
  </div>
  <div class="col-4">

    <a href="/markdown" class="profile_button">Markdown Tutorial</a>
    <a href="/messages/" class="profile_button">Unseen Messages (<?php if (GetNumberOfUnseenMessages($_SESSION['UserID']) != 0) {
                                                                    echo GetNumberOfUnseenMessages($_SESSION['UserID']);
                                                                  } else {
                                                                    echo 'None';
                                                                  } ?>)</a>
    <a href="/messages/sent" class="profile_button">Sent Messages (<?php if (GetNumberOfSentMessages($_SESSION['UserID']) != 0) {
                                                                      echo GetNumberOfSentMessages($_SESSION['UserID']);
                                                                    } else {
                                                                      echo 'None';
                                                                    } ?>)</a>

  </div>
</div>