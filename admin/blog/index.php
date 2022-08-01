<?php
// include layout file
$child_view = "views/admin/_blog.php";
$name = "Admin";
include($_SERVER['DOCUMENT_ROOT'] . "/private/layout.php");
RequireAdmin();