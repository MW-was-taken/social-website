<?php
// include layout file
$child_view = "views/auth/messages/_message.php";
include($_SERVER['DOCUMENT_ROOT'] . "/private/layout.php");
RequireAuthentication();