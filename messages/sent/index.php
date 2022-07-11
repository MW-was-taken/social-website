<?php
// include layout file
$child_view = "views/auth/messages/_messages_sent.php";
$name = "Seen Messages";
include("../../layout.php");
RequireAuthentication();