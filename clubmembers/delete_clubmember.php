<?php
include '../config.php';

if (isset($_GET['membershipNum'])) {
    $membershipNum = $_GET['membershipNum'];

    $sql = "DELETE FROM ClubMembers WHERE membershipNum = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $membershipNum);

    if ($stmt->execute()) {
        header("Location: clubmembers.php");
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
    <title>Delete Club Member</title>
</head>
<body>
    <h1>Delete Club Member</h1>
    <button onclick="window.location.href='clubmembers.php'">Back to Club Members</button>
</body>
</html>
