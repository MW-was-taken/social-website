<?php
if (isset($_GET['id'])) {
 $id = $_GET['id'];
 $User = HandleProfile($id);
} else {
  header("location: ../../users/?error=Invalid User!");
}

?>

<div class="card users">
<div class="card-header">
  <?php echo $User['user_name']; ?>
</div>
<div class="card-body">
  <?php 
    echo "<label>Status: " . PurifyInput($User['user_status']) . "</label>";
    echo "<br>";
    echo "<label>Join Date: " . HandleDate($User['user_created']) . "</label>";
  ?>
  <br>
  <?php 
  if ($_GET['id'] != $_SESSION['UserID']) {
    echo "<a href='../../messages/send?id=" . $User['user_id'] . "'>Message</a>";
  }
  ?>
</div>
</div>
