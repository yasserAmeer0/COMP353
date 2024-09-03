<?php
include '../config.php';

if (isset($_GET['membershipNum'])) {
    $membershipNum = $_GET['membershipNum'];

    $sql = "DELETE FROM TeamMembers WHERE membershipNum = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $membershipNum);

    if ($stmt->execute()) {
        header("Location: teammembers.php");
        exit();
    } else {
        echo "Error deleting team member: " . $stmt->error;
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
    <title>Delete Team Member</title>
</head>
<body>
    <h1>Delete Team Member</h1>
    <button onclick="window.location.href='teammembers.php'">Back to Team Members</button>
</body>
</html>
