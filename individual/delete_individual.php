<?php
include '../config.php';

if (isset($_GET['SSN'])) {
    $SSN = $_GET['SSN'];

    $sql = "DELETE FROM Individual WHERE SSN = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $SSN);

    if ($stmt->execute()) {
        header("Location: individual.php");
        exit();
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Individual</title>
</head>
<body>
    <h1>Delete Individual</h1>
    <button onclick="window.location.href='individual.php'">Back to Individuals</button>
</body>
</html>
