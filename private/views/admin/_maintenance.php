<?php
// form
if (isset($_POST['submit'])) {
  $maintenance_bool = $_POST['maintenance_bool'];

  // if maintenance bool is not 1 or 0, set it to 1
  if ($maintenance_bool != 1 && $maintenance_bool != 0) {
    $maintenance_bool = 1;
  }

  if ($maintenance_bool == 1) {
    // StaffLog
    StaffLog($_SESSION['UserID'], "UPDATED MAINTENANCE: ENABLED: " . $maintenance_bool);
    // update alert to maintenance alert
    UpdateAlert(1, "Welcome admins. Site is currently under maintenance.", "", 2);
  } else {
    // StaffLog
    StaffLog($_SESSION['UserID'], "UPDATED MAINTENANCE: DISABLED");
    // update alert to normal alert
    UpdateAlert(0, "", "", 0);
  }

  $sql = "UPDATE site_settings SET maintenance = :maintenance_bool WHERE id = 1";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':maintenance_bool', $maintenance_bool);
  $stmt->execute();
  header("Location: /admin/maintenance");
  exit();
}
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
      <h1>Maintenance</h1>
      <p>Puts the site into a locked mode so that only admins, site developers, and the owner can log in.</p>
      <hr>
      <!-- maintenance form -->
      <form action="/admin/maintenance/" method="post">
        <div class="form-group">
          <label for="maintenance_bool">Maintenance Mode</label>
          <br>
          <select class="form-control" name="maintenance_bool">
            <option value="1" <?php if (GetMaintenanceBool() == 1) {
                                echo "selected";
                              } ?>>Enabled</option>
            <option value="0" <?php if (GetMaintenanceBool() == 0) {
                                echo "selected";
                              } ?>>Disabled</option>
          </select>
          <button type="submit" name="submit" class="btn btn-primary">Update Maintenance</button>
        </div>    
      </div>
  </div>
</div>