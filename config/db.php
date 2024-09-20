<?php
require_once('config.php');

// Create a database connection
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check if the connection is successful
if (!$connection) {
    die('Database connection failed: ' . mysqli_connect_error());
}
