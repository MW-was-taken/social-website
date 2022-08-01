<div class="admin-card">
  <div class="admin-header">
    <h1>Admin</h1>
  </div>
  <div class="admin-wrapper">
    <?php
      include('_sidebar.php');
    ?>
    <div class="admin-content">
      <h1>Admin Dashboard</h1>
      <h2 class="small">
        Time
      </h2>
      <!-- javascript time -->
      <h3 id="time"></h3>
      <script>
        // update time and get in local timezone
        function updateTime() {
          var d = new Date();
          var time = d.toLocaleTimeString();
          document.getElementById("time").innerHTML = time;
        }
        setInterval(updateTime, 1000);
      </script>
      <!-- end javascript time -->
      <h2 class="small">
        Users
      </h2>
      <h3>
        <?php
          echo GetNumberOfUsers($conn);
        ?>
        users
      </h3>
      <h2 class="small">
        Crime Rate <span class="small">(percentage of banned users)</span>
      </h2>
      <h3>
        0%
      </h3>
    </div>
  </div>
</div>