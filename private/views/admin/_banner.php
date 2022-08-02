<?php

if(isset($_POST['submit'])) {
  $alert_bool = $_POST['alert_bool'];
  $alert_text = $_POST['alert_text'];
  $alert_type = $_POST['alert_type'];
  $alert_link = $_POST['alert_link'];
  require_once $_SERVER['DOCUMENT_ROOT'] . '/config/profanity.php';

  // sanitize input
  $alert_text = PurifyInput($alert_text);
  $alert_link = PurifyInput($alert_link);

  $alert_text = ProfanityFilter($alert_text);

  RequireAuthentication();

  // flood check
  if(Flood($_SESSION['UserID'], 60)) {
    $_SESSION['error'] = "Try again in 60 seconds!";
    header("Location: /admin/banner");
    exit();
  }
  
  // check if alert bool doesn't equal 0 or 1
  if($alert_bool != 0 && $alert_bool != 1) {
    $alert_bool = 0;
  }

  // check if alert type doesn't equal 1 through 5
  if($alert_type != 1 && $alert_type != 2 && $alert_type != 3 && $alert_type != 4 && $alert_type != 5) {
    $alert_type = 1;
  }
  session_start();
  // check if alert text is empty
  if(empty($alert_text)) {
    $_SESSION['error'] = "Banner text is empty!";
    header("Location: /admin/banner");
    exit();
  }

  // StaffLog
  $color = DetermineAlertColor($alert_type);
  $staff_log_string = "Updated alert to say: " . $alert_text . "<br> Updated alert link: " . $alert_link . "<br> Updated alert color: " . $color;
  StaffLog($_SESSION['UserID'], $staff_log_string);
  SetUserFlood($_SESSION['UserID']);

  $sql = "UPDATE site_settings SET alert = :alert_bool, alert_text = :alert_text, alert_link = :alert_link, alert_type = :alert_type WHERE id = 1";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':alert_bool', $alert_bool);
  $stmt->bindParam(':alert_text', $alert_text);
  $stmt->bindParam(':alert_link', $alert_link);
  $stmt->bindParam(':alert_type', $alert_type);
  $stmt->execute();
  header("Location: /admin/banner");
  exit();
}
// get error from url
@$error = $_SESSION["error"];
// if error is set
HandleError($error);
// unset error
unset($_SESSION["error"]);


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
      <?php
      if (!SiteMaintenance()) {
      ?>
        <h1>Site Banner</h1>
        <p>A banner that shows for all users on-site.</p>
        <hr>
        <form action="/admin/banner/" method="post">
          <div class="form-group">
            <label for="banner">Banner Text (100 characters or less)</label>
            <textarea class="form-control" id="banner" name="alert_text" rows="3"><?php echo GetAlertText(); ?></textarea>
            <label for="banner-link">Banner Link</label>
            <input type="text" class="form-control" id="banner-link" name="alert_link" value="<?php echo GetAlertLink(); ?>">
            <!-- dropdown for alert enabled/disabled -->
            <label for="alert_bool">Banner Enabled/Disabled</label>
            <br>
            <select class="form-control" name="alert_bool">
              <option value="1" <?php if (GetAlertBool() == 1) {
                                  echo "selected";
                                } ?>>Enabled</option>
              <option value="0" <?php if (GetAlertBool() == 0) {
                                  echo "selected";
                                } ?>>Disabled</option>
            </select>
            <br>
            <!-- dropdown for alert type -->
            <label for="alert_type">Banner Type</label>
            <br>
            <select class="form-control" name="alert_type">
              <option value="1" <?php if (GetAlertType() == 1) {
                                  echo "selected";
                                } ?>>Green</option>
              <option value="2" <?php if (GetAlertType() == 2) {
                                  echo "selected";
                                } ?>>Purple</option>
              <option value="3" <?php if (GetAlertType() == 3) {
                                  echo "selected";
                                } ?>>Orange</option>
              <option value="4" <?php if (GetAlertType() == 4) {
                                  echo "selected";
                                } ?>>Red</option>
              <option value="5" <?php if (GetAlertType() == 5) {
                                  echo "selected";
                                } ?>>Blue</option>
            </select>
            <button type="submit" name="submit" class="btn btn-primary">Update Banner</button>
        </form>
      <?php

      }
      if (SiteMaintenance()) {
      ?>
        <h1>Site Maintenance</h1>
        <p>You cannot update the banner if the website is in maintenance.</p>
      <?php
      }
      ?>
    </div>
  </div>
</div>
</div>
<?php
