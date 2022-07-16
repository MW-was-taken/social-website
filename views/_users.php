<?php 
// TODO: pagination
// TODO: implement errors and notes
HandleError(@$_GET["error"]);
if(isset($_GET['page'])) {
  $page = $_GET['page'];
} else {
  $page = 1;
}
?>
<div class="card users">
<div class="card-header">
Users
</div>
<div class="card-body">
  <?php 
    ListUsers($page);
  ?>
  <center>
  <?php
  if($page > 1) {
    echo '<a href="?page=' . ($page - 1) . '">Previous</a>';
    echo ' | ';
  }
  echo '<a href="?page=' . ($page + 1) . '">Next</a>';
  ?>
  </center>
</div>
</div>
