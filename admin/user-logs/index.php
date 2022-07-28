<?php
// include layout file
$child_view = "views/admin/_index.php";
$name = "Admin";
include($_SERVER['DOCUMENT_ROOT'] . "/private/layout.php");
RequireAdmin();
