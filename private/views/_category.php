<?php

if (!isset($_GET['id'])) {
  header('location: /forum');
  exit();
} else
  $id = $_GET['id'];

if (!isset($_GET['page']))
  $page = 1;
else
  $page = $_GET['page'];

$limit = 10;
$offset = ($page - 1) * $limit;

// order by newest first but if post is pinned, show it first
$statement = $conn->prepare("SELECT * FROM posts WHERE post_cat = :id ORDER BY post_pinned DESC, post_created DESC LIMIT :limit OFFSET :offset");
// cast as int
$statement->bindParam(':id', $id, PDO::PARAM_INT);
$statement->bindParam(':limit', $limit, PDO::PARAM_INT);
$statement->bindParam(':offset', $offset, PDO::PARAM_INT);
$posts = $statement->fetchAll(PDO::FETCH_ASSOC);
// get category info
$statement = $conn->prepare("SELECT * FROM categories WHERE cat_id = :id");
$statement->bindParam(':id', $id, PDO::PARAM_INT);
$statement->execute();
$category = $statement->fetch(PDO::FETCH_ASSOC);


?>

<div class="row">
  <div class="col-3">

  </div>
  <div class="col-6">
    <div class="card">
      <div class="card-header red">
        <?php echo $category['cat_name']; ?>
      </div>
      <div class="card-body">
        <?php
        if (!$posts) {
          echo 'No posts here!';
        }

        foreach($posts as $post)

        ?>
      </div>
    </div>
  </div>
  <div class="col-3">

  </div>
</div>