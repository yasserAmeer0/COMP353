<?php
include '../config.php';

if (isset($_GET['postalCode'])) {
    $postalCode = $_GET['postalCode'];

    // Delete the area from the database
    $sql = "DELETE FROM Area WHERE postalCode='$postalCode'";

    if ($conn->query($sql) === TRUE) {
        header("Location: area.php");
        exit();
    } else {
        // Corrected the way errors are handled
        echo "Error deleting area: " . $conn->error;
    }
} else {
    echo "No area specified";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Area</title>
</head>
<body>
    <h1>Delete Area</h1>
    <button onclick="window.location.href='area.php'">Back to Areas</button>
</body>
</html>
