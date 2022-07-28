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
      <form action="/config/forms/maintenance.php" method="post">
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