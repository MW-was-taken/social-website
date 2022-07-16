<?php

if(isset($_POST["submit"])) {
  $category_name = $_POST["category_name"];
  $category_description = $_POST["category_description"];
  $category_creator = $_POST['category_creator'];
  $category_admin = $_POST['category_admin'];

  session_start();
  if($category_creator != $_SESSION["UserID"]) {
    header("Location: /admin/forum/categories?error=You are not the creator of this category.");
    exit();
  }

  include("../../config/functions.php");
  include("../../config/config.php");

  if(empty($category_name) && empty($category_description)) {
    header("Location: ../../admin/forum/categories?error=Please fill out all fields");
  }

  CreateCategory($category_name, $category_description, $category_creator, $category_admin);  
  header("Location: ../../admin/forum/categories?note=Category created successfully!");
  exit();
} else {
  header("Location: ../../admin/forum/categories");
}