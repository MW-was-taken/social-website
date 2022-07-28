  <?php

  $category = GetForumCategories();

  ?>
  <div class="row">
    <div class="col-4">
      <div class="card">
        <div class="card-header">
          Recent Topics
        </div>
        <div class="card-body">

        </div>
      </div>
      <br>
      <div class="card">
        <div class="card-header">
          Popular Topics
        </div>
        <div class="card-body">

        </div>
      </div>
    </div>
    <div class="col-8">
      <div class="card forum">
        <div class="card-header red">
          <div class="ellipsis">
            Forum
            <span style="float: right; margin: 0 5px;">
              Replies
            </span>
            <span style="float: right; margin: 0 5px;">
              Posts
            </span>
          </div>
        </div>
        <div class="card-body">
          <?php
          foreach ($category as $key => $value) {
            echo "<div class='ellipsis category'>";
            echo "<a href='/forum/category?id=". $value['cat_id'] ."'>" . $value["cat_name"] . "</a>";
            echo "<label style='float:right; padding: 0 32px;'>0</label>";
            echo "<label style='float:right; padding: 0 24px;'>0</label>";
            echo "<p class='category_desc'>" . $value["cat_desc"] . "</p>";
            echo "</div>";
            echo "<hr>";
          }
          ?>
        </div>  
      </div>
    </div>
  </div>