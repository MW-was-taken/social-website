<?php

$id = $_GET['id'];

$statement = $conn->prepare("SELECT * FROM blog WHERE blog_id = :id");
$statement->execute(array(':id' => $id));
$blog = $statement->fetch(PDO::FETCH_ASSOC);

if (!$blog) {
  header("location: /dashboard");
  exit();
}

if(isset($_POST['submit'])) {
  // delete blog post
  if(!IfAdmin($_SESSION['UserID'])) {
    $_SESSION['error'] = "Permission denied.";
    header("location: /dashboard");
    exit();
  }

  $statement = $conn->prepare("DELETE FROM blog WHERE blog_id = :id");
  $statement->execute(array(':id' => $id));
  $_SESSION['note'] = "Blog post deleted!";
  header("location: /dashboard");
  exit();
}

$name = $blog['blog_title'];
$user = GetUserByID($conn, $blog['blog_creator']);


?>
<div class="row">
  <div class="col-3">
  </div>
  <div class="col-6">
    <div class="card no-header">
      <div class="card-body" style="padding: 24px">
        <div class="center">
          <h1 id="title">
            <?php
            echo $blog['blog_title'];
            ?>
          </h1>
          <label for="title" class="small">
            <?php
            echo HandleDate($blog['blog_created']);
            ?>, <?php
                echo time_elapsed_string($blog['blog_created']);
                ?>, by <?php
                    echo GetProfileLink($user['user_id'], $user['user_name']);
                    ?>
          </label>
        </div>
        <hr>
        <?php
        echo $blog['blog_body'];
        ?>
        <?php
        if (IfAdmin($_SESSION['UserID'])) {
        ?>
          <hr>
          <div class="inline-block">
            <button href="/blog/edit?id=<?php echo $blog['blog_id']; ?>" class="btn-primary sidebyside">
              <i class="fas fa-edit"></i>
              Edit
            </button>
            <form method="post">
              <button class="btn-danger sidebyside" name="submit" type="submit">
                <i class="fas fa-trash-alt"></i>
                Delete
              </button>
            </form>
          </div>
        <?php
        }
        ?>
      </div>
    </div>
  </div>
  <div class="col-3"></div>
</div>