<?php
// get error from url
@$error = $_GET["error"];
// if error is set
HandleError($error);
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
        <form action="/config/forms/alert.php" method="post">
          <div class="form-group">
            <label for="banner">Banner Text (100 characters or less)</label>
            <textarea class="form-control" id="banner" name="alert_text" rows="3"><?php echo GetAlertText(); ?></textarea>
            <!-- dropdown for alert enabled/disabled -->
            <label for="alert_bool">Alert Enabled/Disabled</label>
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
            <label for="alert_type">Alert Type</label>
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
            </select>
            <button type="submit" name="submit" class="btn btn-primary">Update Banner</button>
        </form>
      <?php

      }
      if(SiteMaintenance()) {
      ?>
        <h1>Site Maintenance</h1>
        <p>You cannot update the banner if the website is in maintenance. If you need to update it, update it through the database tab.</p>
      <?php
      }
      ?>
    </div>
  </div>
</div>
</div>
<?php
