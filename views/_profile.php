<?php
if (isset($_GET['id'])) {
 $id = $_GET['id'];
 $User = HandleProfile($id);
} else {
  header("location: ../../users/?error=Invalid User!");
}
$name = $User['user_name'];

?>
<div class="card users">
<div class="card-header">
  <?php echo $User['user_name']; 
  ?>
  <?php
  ProfileBadge($User['user_admin']); 
  ?>

    
  <?php
      if(!IfIsOnline($User['user_updated'])) {
        echo '<span class="status-dot users"></span>';
      } else {
        echo '<span class="status-dot users online"></span>';
      }
      ?>
  
</div>
<div class="card-body">
  <?php 
    echo "<label>Status: " . $User['user_status'] . "</label>";
    echo "<br>";
    echo "<label>Join Date: " . HandleDate($User['user_created']) . "</label>";
    echo "<br>";
    echo "<label>Last Online: " . time_elapsed_string($User['user_updated']) . "</label>";
  ?>
  <br>
  <?php 
  if ($_GET['id'] != @$_SESSION['UserID'] && UserIsAuthenticated()) {
    echo "<a href='../../messages/send?id=" . $User['user_id'] . "'>Message</a>";
  }
  ?>
</div>
</div>
