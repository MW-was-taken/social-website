<?php
// include layout file
$child_view = "views/auth/messages/_messages.php";
$name = "Messages";
include($_SERVER['DOCUMENT_ROOT'] . "/private/layout.php");
RequireAuthentication();