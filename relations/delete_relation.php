<?php
include '../config.php';

if (isset($_GET['membershipNum'])) {
    $membershipNum = $_GET['membershipNum'];

    $sql = "DELETE FROM Relations WHERE membershipNum = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $membershipNum);

    if ($stmt->execute()) {
        header("Location: relations.php");
        exit();
    } else {
        echo "Error deleting relation: " . $stmt->error;
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
    <title>Delete Relation</title>
</head>
<body>
    <h1>Delete Relation</h1>
    <button onclick="window.location.href='relations.php'">Back to Relations</button>
</body>
</html>
