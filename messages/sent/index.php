<?php
// include layout file
$child_view = "views/auth/messages/_messages_sent.php";
$name = "Seen Messages";
include($_SERVER['DOCUMENT_ROOT'] . "/private/layout.php");
RequireAuthentication();