<?php
include '../config.php';

if (isset($_GET['dateTime']) && isset($_GET['teamName'])) {
    $dateTime = $_GET['dateTime'];
    $teamName = $_GET['teamName'];

    $sql = "DELETE FROM TeamFormation WHERE dateTime = ? AND teamName = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $dateTime, $teamName);

    if ($stmt->execute()) {
        header("Location: teamformation.php");
        exit();
    } else {
        echo "Error deleting team formation: " . $stmt->error;
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
    <title>Delete Team Formation</title>
</head>
<body>
    <h1>Delete Team Formation</h1>
    <button onclick="window.location.href='teamformation.php'">Back to Team Formations</button>
</body>
</html>
