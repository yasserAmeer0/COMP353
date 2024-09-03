<?php
include '../config.php';

if (isset($_GET['phoneNum'])) {
    $phone = $_GET['phoneNum'];

    // Updated SQL statement to match the new column name for phone number
    $sql = "DELETE FROM Location WHERE phoneNum='$phone'";

    if ($conn->query($sql) === TRUE) {
        echo "Location deleted successfully";
    } else {
        echo "Error deleting location: " . $conn->error;
    }
} else {
    echo "No location found";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Location</title>
</head>
<body>
    <h1>Delete Location</h1>
    <button onclick="window.location.href='location.php'">Back to Locations</button>
</body>
</html>
