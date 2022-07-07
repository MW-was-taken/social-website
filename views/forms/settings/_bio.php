<?php
$bio = GetBio($conn, $_SESSION["UserID"]);
?>
<div class="card users">
  <div class="card-header">
    Site Settings
  </div>
  <div class="card-body">
    <h4>Bio</h4>
    <form action="../../config/forms/bio.php" method="post">
      <textarea name="bio" class="form-control" rows="3" placeholder="Tell us about you..."><?php echo $bio; ?></textarea>
      <br>
      <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</div>