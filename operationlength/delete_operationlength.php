<?php
include '../config.php';

if (isset($_GET['personnelTelephoneNum'])) {
    $personnelTelephoneNum = $_GET['personnelTelephoneNum'];

    $sql = "DELETE FROM OperationLength WHERE personnelTelephoneNum = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $personnelTelephoneNum);

    if ($stmt->execute()) {
        header("Location: operationlength.php");
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
    <title>Delete Operation Length</title>
</head>
<body>
    <h1>Delete Operation Length</h1>
    <button onclick="window.location.href='operationlength.php'">Back to Operation Lengths</button>
</body>
</html>
