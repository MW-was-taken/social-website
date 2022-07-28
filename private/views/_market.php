<?php

if(isset($_GET['sort'])) {
  $sort = $_GET['sort'];
} else {
  $sort = "";
}
?>

<div class="row">
  <div class="col-2">
    <?php
      if(IfAdmin($_SESSION['UserID'])) {
        echo '<a href="/market/new" class="profile_button">
          New Item
        </a>';
      }
    ?>
    <div class="card no-header">
      <div class="card-body market">
        <h2>Market</h2>
        <hr>
        <a href="?sort=price_desc">Sort by Price (high to low)</a>
        <a href="?sort=price_asc">Sort by Price (low to high)</a>
        <a href="?sort=name">Sort by Name</a>
        <a href="?sort=name_desc">Collectible Items</a>
      </div>
    </div>
  </div>
  <div class="col-8">
    <div class="center">
      <h1>
        Items
      </h1>
    </div>
    <?php 
    ListItems();
    ?>
  </div>

</div>