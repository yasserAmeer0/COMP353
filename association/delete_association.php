<?php
include '../config.php';

if (isset($_GET['famTelephoneNum'])) {
    $famTelephoneNum = $_GET['famTelephoneNum'];

    $sql = "DELETE FROM Association WHERE famTelephoneNum = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $famTelephoneNum);

    if ($stmt->execute()) {
        header("Location: association.php");
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
    <title>Delete Association</title>
</head>
<body>
    <h1>Delete Association</h1>
    <button onclick="window.location.href='association.php'">Back to Associations</button>
</body>
</html>
