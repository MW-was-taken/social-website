<?php
// include layout file
$child_view = "views/forms/_message.php";
$name = "Send Message";
include($_SERVER['DOCUMENT_ROOT'] . "/private/layout.php");
RequireAuthentication();