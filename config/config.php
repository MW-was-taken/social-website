<?php
date_default_timezone_set('America/New_York');

require('env.php');
try {
    $conn = OpenConnection($DATABASE_HOST, $DATABASE_USERNAME, $DATABASE_PASSWORD, $DATABASE);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}