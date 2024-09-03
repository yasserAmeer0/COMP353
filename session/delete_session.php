<?php
include '../config.php';

if (isset($_GET['DateTime'])) {
    $dateTime = $_GET['DateTime'];

    $sql = "DELETE FROM Session WHERE DateTime = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $dateTime);

    if ($stmt->execute()) {
        header("Location: session.php");
        exit();
    } else {
        echo "Error deleting session: " . $stmt->error;
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
    <title>Delete Session</title>
</head>
<body>
    <h1>Delete Session</h1>
    <button onclick="window.location.href='session.php'">Back to Sessions</button>
</body>
</html>
