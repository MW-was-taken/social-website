<?php
date_default_timezone_set('America/New_York');

require('env.php');
try {
    $conn = OpenConnection($DATABASE_HOST, $DATABASE_USERNAME, $DATABASE_PASSWORD, $DATABASE);
} catch (mysqli_sql_exception $e) {
    unset($e);
    exit();
}