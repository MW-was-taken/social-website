<?php
// Get number of users
$statement = $conn->prepare("SELECT COUNT(*) AS count FROM users");
$statement->execute();
$count = $statement->fetch(PDO::FETCH_ASSOC);
$count = $count['count'];
// get number of users created in the last 24 hours
$statement = $conn->prepare("SELECT COUNT(*) AS count FROM users WHERE user_created >= date_sub(now(), interval 1 day) AND user_created <= now()");
$statement->execute();
$count24 = $statement->fetch(PDO::FETCH_ASSOC);
$count24 = $count24['count']; 
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
          echo $count;
        ?>
        users (<?php echo $count24; ?> in the last 24 hours)
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