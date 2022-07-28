<?php
// get item id from url
if (isset($_GET['id'])) {
  $id = $_GET['id'];
} else {
  // header back to market if no id is set
  header("Location: /market");
  exit();
}
// get item info from db
$item = GetMarketItemByID($id);
$id = $item[0]['item_id'];
$name = $item[0]['item_name'];
$description = $item[0]['item_desc'];
$price = $item[0]['item_price'];
$creator = $item[0]['item_creator'];
// get creator name from db
global $conn;
$creator_name = GetUserByID($conn, $creator)['user_name'];
?>
<div class="row">
  <div class="col-6" style="margin: auto;">
    <div class="card no-header">
      <div class="card-body">
        <div class="row">
          <div class="col-2">
            <img src="/Avatar/thumbnail?id=<?php echo $id; ?>" alt="<?php echo $name; ?>" class="item_image">
            <br>
          </div>
          <div class="col-8">
            <h1>
              <?php echo $name; ?> 
            </h1>
            <?php echo GetProfileLink($creator, $creator_name); ?>
            
            <?php
            if ($price == 0) {
              echo '<button type="button" class="profile_button" onclick="BuyItem(' . $id . ')">FREE ITEM</button>';
            } else {
              echo '<button type="button" class="profile_button" onclick="BuyItem(' . $id . ')">' . $price . ' CUBES</button>';
            }
            ?>
            <p>
              <?php echo $description; ?>
            </p>
            <!-- buy item button -->
          </div>
        </div>

      </div>
    </div>
  </div>
</div>