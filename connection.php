<?php
$host = "localhost";        // Database host
$user = "root";             // Database username
$password = "";             // Database password
$dbname = "surplus_to_serve";  // Database name

// Create connection
$mysqli = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}
?>