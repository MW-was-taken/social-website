<?php 
// TODO: pagination
// TODO: implement errors and notes
HandleError(@$_GET["error"]);
?>
<div class="card users">
<div class="card-header">
Users
</div>
<div class="card-body">
  <?php 
    ListUsers();
  ?>
</div>
</div>
