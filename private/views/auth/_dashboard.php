<?php

if (isset($_POST["submit"])) {
  $status = $_POST["status"];

  include $_SERVER['DOCUMENT_ROOT'] . "/config/profanity.php";

  $status = ProfanityFilter($status);
  $status = PurifyInput($status);
  if (!preg_match("/^[ a-zA-Z0-9_',.|*&^%$#@!()?]*$/", $status)) {
    $_SESSION["error"] = "Status can only contain letters, numbers, and punctuation.";
    header("location: /dashboard");
    exit();
  }
  if (strlen($status) > 50) {
    $_SESSION["error"] = "Status must be less than 50 characters.";
    header("location: /dashboard");
    exit();
  }

  if (empty($status)) {
    $_SESSION["error"] = "Please fill in all fields";
    header("location: /dashboard");
    exit();
  }

  UserLog($_SESSION['UserID'], "Updated status to: " . $status);

  // insert user_status into users table
  $statement = $conn->prepare("UPDATE users SET user_status = :status WHERE user_id = :user_id");
  $statement->execute(array(':status' => $status, ':user_id' => $_SESSION['UserID']));
  $_SESSION["note"] = "Status updated!";
  header("Location: /dashboard");
  exit();
}


HandleNote(@$_SESSION['note']);
HandleError(@$_SESSION['error']);

unset($_SESSION['note']);
unset($_SESSION['error']);

// get number of wall posts
$statement = $conn->prepare("SELECT COUNT(*) AS count FROM wall");
$statement->execute();
$count = $statement->fetch(PDO::FETCH_ASSOC);
$count = $count['count'];
if($count > 6) {
  $offset = $count - 6;
} else {
  $offset = 0;
}
// Get all wall posts, limit to 6, and order by newest first and offset by the number of posts already shown.
$statement = $conn->prepare("SELECT * FROM wall ORDER BY wall_id DESC LIMIT 6 OFFSET :offset");
// Set offset to the number of posts already shown.
$statement->bindParam(':offset', $offset, PDO::PARAM_INT);
$statement->execute();
$wall = $statement->fetchAll(PDO::FETCH_ASSOC);
// get blog posts
// get number of blog posts
$statement = $conn->prepare("SELECT COUNT(*) AS count FROM blog");
$statement->execute();
$count = $statement->fetch(PDO::FETCH_ASSOC);
$count = $count['count'];
if($count > 6) {
  $offset = $count - 6;
} else {
  $offset = 0;
}

$statement = $conn->prepare("SELECT * FROM blog ORDER BY blog_id DESC LIMIT 6 OFFSET :offset");
$statement->bindParam(':offset', $offset, PDO::PARAM_INT);
$statement->execute();
$blog = $statement->fetchAll(PDO::FETCH_ASSOC);


$user = GetUserByID($conn, $_SESSION['UserID']);
?>
<div class="row">
  <div class="col-2">
    <div class="speech-bubble">
      <?php
      echo GetStatus($conn, $_SESSION['UserID']);
      ?>
    </div>
    <br>
    <div class="card no-header">
      <div class="card-body">
        <div class="center">
          <img src="/Avatar?id=<?php echo $_SESSION['UserID']; ?>" alt="Avatar">
          <h2>
            <?php echo $_SESSION['Username']; ?>
          </h2>
        </div>
      </div>
    </div>
    <br>
    <div class="card no-header">
      <div class="card-body">
        <h2 class="center">
          User Info
        </h2>
        <hr>  
        <label>
          Created On: <span class="small" style="float: right;"><?php echo HandleDate($user['user_created']) ?></span>
        </label>
        <br>
        <label>
          Friends: <span class="small" style="float: right;">Placeholder</span>
        </label>
        <br>
        <label>
          Wall Posts: <span class="small" style="float: right;"><?php echo GetWallPostAmount($_SESSION['UserID']); ?></span>
        </label>
      </div>
    </div>
  </div>
  <div class="col-4">
    <div class="card no-header">
      <div class="card-body">
        <h2 class="center">
          Dashboard
        </h2>
        <hr>
        <form action="/dashboard/" method="post">
          <label for="status">Status</label>
          <input type="text" name="status" placeholder="How are you?">
          <button type="submit" name="submit">Update Status</button>
        </form>
      </div>
    </div>
    <br>
    <div class="card no-header">
      <div class="card-body">
        <h2 class="center">
          Blog
        </h2>
        <hr>
        <?php
        foreach ($blog as $post) {
          ?>
            <a href="/blog?id=<?php echo $post['blog_id']; ?>">
              <h2><?php echo $post['blog_title']; ?></h2>
            </a>
            <p class="small" style="margin: 5px 0;">
              <?php echo HandleDate($post['blog_created']); ?>
              <?php
                // if blog post was created in last 24 hours, show "new" badge
                if (strtotime($post['blog_created']) > strtotime("-1 day")) {
                  echo "<span class='badge admin-text'>New</span>";
                }
              ?>
            </p>
            <hr>
          <?php
        }
        ?>

      </div>
    </div>
  </div>
  <div class="col-6">
    <div class="card no-header">
      <div class="card-body">
        <h2 class="center">
          Website Wall
        </h2>
        <hr>
        <?php
        // get count of wall posts
        $count = count($wall);
        if ($count == 0) {
          echo "<p class='center'>No posts yet!</p>";
        } else {
          foreach ($wall as $post) {
            $user = GetUserByID($conn, $post['wall_creator']);
        ?>
            <a href="/profile?id=<?php echo $user['user_id']; ?>">
              <?php
              echo $user['user_name'];
              ?>
            </a>
            <p>
              <?php
              echo $post['wall_message'];
              ?>
            </p>
            <p class="small">
              <?php
              echo time_elapsed_string($post['wall_created']);
              ?>
            </p>
            <hr>
        <?php
          }
        }
        ?>
        <form action="/dashboard/wall/" method="post">
          <input type="text" name="message" placeholder="Your wall message here...">
          <button type="submit" name="submit">Post</button>
        </form>
      </div>
    </div>
  </div>
</div>