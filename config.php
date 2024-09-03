<?php
$servername = "rnc353.encs.concordia.ca";
$username = "rnc353_1";
$password = "CoMp353_S2024";
$dbname = "rnc353_1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
