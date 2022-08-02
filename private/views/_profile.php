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

// get wall posts

if ($bio != null) {
  $breaks =  array("<br />", "<br>", "<br/>", "<br />", "&lt;br /&gt;", "&lt;br/&gt;", "&lt;br&gt;");
  $bio = str_ireplace($breaks, "", $bio);
  $bio = ToLineBreaks($bio);
}

?>
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
          // only show the first 100 characters of bio, if it is longer than 100 characters then show the "Read More" button
          if (strlen($bio) > 100) {
            echo "<span id='read-less'>" . substr($bio, 0, 100) . "</span>";
            echo "<span id='dots'>...</span>";
            // remove first 100 characters from bio and show the "Read Less" button
            $bio = substr($bio, 100);
            echo "<span id='read-more' style='display: none'>" . $bio . "</span>";
            echo "<br>";
            echo "<a href='#' onclick='readMore()' id='btn' class='read_more'>Read More</button></a>";
          } else {
            echo $bio;
          }
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
  <div class="col-5">
    <div class="card">
      <div class="card-header red">
        <h2><?php echo $name; ?>'s wall</h2>
      </div>
      <div class="card-body">

      </div>
    </div>
  </div>
</div>

<script src="/js/bio.js"></script>