<?php
date_default_timezone_set('America/New_York');

$DATABASE_HOST = 'localhost';
$DATABASE_USERNAME = 'root';
$DATABASE_PASSWORD = 'DatabasePass';
$DATABASE = 'forum2';

try {
    $conn = OpenConnection($DATABASE_HOST, $DATABASE_USERNAME, $DATABASE_PASSWORD, $DATABASE);
} catch (mysqli_sql_exception $e) {
    unset($e);
    exit();
}