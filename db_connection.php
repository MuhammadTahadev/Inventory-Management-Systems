<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory_management_System";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>