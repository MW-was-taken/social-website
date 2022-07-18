<div class="row">
  <div class="col-6" style="margin: auto;">
    <div class="card no-header">
      <div class="card-body">
        <div class="center">
          <h1>
            Create a New Item
          </h1>
        </div>
        <hr>
          <h1>Legend</h1>
          <h2>Item Image</h2>
          <p>This is the image that will be displayed on the market. It must be a .png file and it must be 200x206 as
            per the avatar size.
          </p>
          <h2>Item Type</h2>
          <p>This is the type of item that you are creating.</p>
          <h2>Big Item</h2>
          <p>
            If the item is bigger than our cropped avatar, you can set this to true. The player's avatar will be rendered
            at the non-cropped size. If you are unsure, set it to false. You can change it later.
          </p>
        <hr>
        <form action="/config/forms/market.php" method="POST" enctype="multipart/form-data">
          <label>Item Name</label>
          <input type="text" name="name" placeholder="Item Name">
          <label>Item Description</label>
          <textarea name="description" placeholder="Item Description"></textarea>
          <label>Item Price (0 = free)</label>
          <input type="number" name="price" placeholder="Item Price">
          <label>Item Image (200x206)</label>
          <!-- image upload -->
          <input type="file" name="image" placeholder="Item Image">
          <!-- drop down for type of item -->
          <label>Item Type</label>
          <br>
          <select name="type">
            <option value="1">Shirt</option>
            <option value="2">Pants</option>
            <option value="3">T-Shirt</option>
            <option value="4">Hat</option>
            <option value="5">Glasses</option>
            <option value="6">Face</option>
          </select>
          <!-- dropdown for big item -->
          <button type="submit" name="submit" class="profile_button" style="width: 100%;">Create</button>
        </form>
      </div>
    </div>
  </div>
</div>