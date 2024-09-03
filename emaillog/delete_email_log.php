<?php
include '../config.php';

if (isset($_GET['EmailDate']) && isset($_GET['Sender']) && isset($_GET['Receiver'])) {
    $emailDate = $_GET['EmailDate'];
    $sender = $_GET['Sender'];
    $receiver = $_GET['Receiver'];

    // Delete the email log from the database
    $sql = "DELETE FROM EmailLog WHERE EmailDate='$emailDate' AND Sender='$sender' AND Receiver='$receiver'";

    if ($conn->query($sql) === TRUE) {
        header("Location: email_log.php");
        exit();
    } else {
        echo "Error deleting email log: " . $conn->error;
    }
} else {
    echo "No email log specified";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Email Log</title>
</head>
<body>
    <h1>Delete Email Log</h1>
    <button onclick="window.location.href='email_log.php'">Back to Email Logs</button>
</body>
</html>
