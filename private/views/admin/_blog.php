
<?php
if(isset($_POST['submit'])) {
  $title = $_POST['title'];
  $body = $_POST['body'];

  // flood check
  if(Flood($_SESSION['UserID'], 60)) {
    $_SESSION['error'] = "Try again in 60 seconds!";
    header("Location: /dashboard");
    exit();
  }


  if(empty($title) || empty($body)) {
    $_SESSION['error'] = 'Title or body is empty!';
    header('location: /admin/blog');
    exit();
  }

  $staffLog = "Created a new blog post titled: " . $title . " on " . date("Y-m-d H:i:s");
  StaffLog($conn, $staffLog);

  SetUserFlood($_SESSION['UserID']);

  $statement = $conn->prepare("INSERT INTO blog (blog_title, blog_body, blog_created, blog_creator) VALUES (:title, :body, NOW(), :creator)");
  $statement->execute(array(':title' => $title, ':body' => $body, ':creator' => $_SESSION['UserID']));
  $_SESSION['note'] = 'Blog post created!';
  header('location: /admin/blog');
  exit();
}

HandleNote(@$_SESSION['note']);
unset($_SESSION['note']);

?>

<div class="admin-card">
  <div class="admin-header">
    <h1>Admin</h1>
  </div>
  <div class="admin-wrapper">
    <?php
      include('_sidebar.php');
    ?>
    <div class="admin-content">
      <h1>Blog</h1>
      <p>
        Notify users of events and updates.
      </p>
      <hr>
      <?php
        if (isset($_SESSION['error'])) {
          echo '<div class="error">' . $_SESSION['error'] . '</div>';
          unset($_SESSION['error']);
        }
      ?>
      <form action="/admin/blog/" method="post">
        <div class="form-group">
          <label for="title">Title</label>
          <input type="text" class="form-control" id="title" name="title" placeholder="Title...">
        </div>
        <div class="form-group">
          <label for="content">Body</label>
          <textarea class="form-control" id="content" name="body" rows="3" placeholder="Body..."></textarea>
        </div>
        <div id="preview">
          <p>Start typing and it will show up here...</p>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</div>
<script>
  // after every keypress, preview the content
  $('#content').keyup(function() {
    $('#preview').html($('#content').val());
  });
</script>