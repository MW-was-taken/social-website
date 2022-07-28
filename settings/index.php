<?php
// include layout file
$child_view = "views/forms/settings/_settings.php";
$name = "Account Settings";
include($_SERVER['DOCUMENT_ROOT'] . "/private/layout.php");
RequireAuthentication();