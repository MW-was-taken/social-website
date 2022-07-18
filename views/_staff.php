<?php 
// TODO: pagination
// TODO: implement errors and notes
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
    ListStaff($page);
  ?>
  <div class="ellipsis">
  <?php
  if($page > 1) {
     echo '<a id="prev" style="float: left" href="?page=' . ($page - 1) . '">
        <i class="fas fa-angle-double-left"></i>
        Previous
        </a>';
  }
  echo '<a id="next" style="float: right" href="?page=' . ($page + 1) . '">Next
  <i class="fa fa-angle-double-right"></i></a>';
  ?>
  </div>
</div>
</div>
