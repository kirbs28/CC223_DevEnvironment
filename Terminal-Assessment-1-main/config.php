<!--
    This file is used for database connection.
-->

<?php
// Set secure session settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);

$servername = "localhost";
$username = "root";
$password = "";
$database = "restaurant_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>