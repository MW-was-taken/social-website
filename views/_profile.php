<?php
// if $_GET['id'] is set, then this is not the logged in user's profile



if (isset($_GET['id'])) {
  if ($_GET['id'] == $_SESSION['UserID']) {
    header('location: /profile/');
    exit();
  }
  $user_id = $_GET['id'];
  $user = GetUserByID($conn, $user_id);
  $user_name = $user['user_name'];
  $status = $user['user_status'];
  $bio = $user['user_bio'];
  $name = $user['user_name'];
  $user_admin = $user['user_admin'];
  $online = $user['user_updated'];
} else {
  $user_id = $_SESSION['UserID'];
  $user = GetUserByID($conn, $user_id);
  $user_name = $user['user_name'];
  $status = $user['user_status'];
  $bio = $user['user_bio'];
  $name = "Your Profile";
  $user_admin = $user['user_admin'];
  $online = $user['user_updated'];
}
if ($bio != null) {
  $breaks =  array("<br />", "<br>", "<br/>", "<br />", "&lt;br /&gt;", "&lt;br/&gt;", "&lt;br&gt;");
  $bio = str_ireplace($breaks, "", $bio);
  $bio = ToLineBreaks($bio);
}


?>
<!--
<div class="card users">
  <div class="card-header">
    <?php echo $user_name;
    ?>

    <?php
    if (!IfIsOnline($user['user_updated'])) {
      echo '<span class="status-dot users"></span>';
    } else {
      echo '<span class="status-dot users online"></span>';
    }
    ?>

  </div>
  <div class="card-body">
    <?php
    echo "<label>Status: " . $status . "</label>";
    echo "<br>";
    if (!empty($bio)) {
      echo "<label>Bio</label>";
      echo "<p>" . $bio . "</p>";
    }
    echo "<label>Join Date: " . HandleDate($user['user_created']) . "</label>";
    echo "<br>";
    echo "<label>Last Online: " . time_elapsed_string($user['user_updated']) . "</label>";
    ?>
    <br>
    <?php
    if (isset($_GET['id'])) {
      echo "<a href='../../messages/send?id=" . $user['user_id'] . "'>Message</a>";
    }
    ?>
  </div>
</div>
  -->
<div class="row">
  <div class="col-2">
    <div class="speech-bubble">
      <?php
      echo $status;
      ?>
    </div>
    <br>
    <div style="align-items: center;"> <?php echo OnlineDot($online) . "<span class='username'>" .$user_name."</span>";   ?> </div>
    <br>
    <div class="card no-header">
      <div class="card-body">
        <img src="<?php echo GetAvatar($user['user_id']); ?>" class="center-img" alt="<?php echo $user_name; ?>">
        <hr>
        <?php
        if(!empty($bio)) {
          echo "<center><b>BIO</b></center>";
          echo "<center>";
          echo "<p>" . $bio . "</p>";
          echo "</center>";
          echo "<hr>";
        }
        ?>
        <?php
        if (isset($_GET['id'])) {
          echo "<a class='profile_button' href='../../messages/send?id=" . $user['user_id'] . "'>Message</a>";
          echo "<hr>";
        }
        ?>
        <label>Last Online:
          <?php
          echo time_elapsed_string($online);
          ?>
        </label>
        <br>
        <label>Join Date:
          <?php
          echo HandleDate($user['user_created']);
          ?>
      </div>
    </div>
  </div>
  <div class="col-4">
    <div class="card no-header">
      <div class="card-body">
        <h1></h1>
      </div>
    </div>
  </div>
</div>