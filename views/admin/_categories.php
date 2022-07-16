<?php
HandleNote(@$_GET["note"]);
HandleError(@$_GET["error"]);
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
      <h1>Create Forum Category</h1>
      <p>A category that forum posts relating to the category topic go in.</p>
      <hr>
      <form action="/config/forms/categories.php" method="post">
        <div class="form-group">
          <label for="category_name">Category Name</label>
          <input type="text" class="form-control" id="category_name" name="category_name">
          <label for="category_description">Category Description</label>
          <textarea class="form-control" id="category_description" name="category_description" rows="3"></textarea>
          <input hidden type="text" class="form-control" id="category_creator" name="category_creator" value="<?php echo $_SESSION["UserID"]; ?>">
          <label for="category_admin">Locked Category? (for announcements, etc)</label>
          <br>
          <!-- no php -->
          <select class="form-control" name="category_admin">
            <option value="0" selected>No</option>
            <option value="1">Yes</option>
          </select>

          
          <button type="submit" name="submit" class="btn btn-primary">Create Category</button>
        </div>
      </form>
    </div>
  </div>
</div>