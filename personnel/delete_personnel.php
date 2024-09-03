<?php
include '../config.php';

if (isset($_GET['firstName']) && isset($_GET['lastName'])) {
    $firstName = $conn->real_escape_string($_GET['firstName']);
    $lastName = $conn->real_escape_string($_GET['lastName']);

    // Start transaction
    $conn->begin_transaction();

    try {
        // Ensure all related entries are updated or deleted first if necessary
        // For example, deleting from OperationLength or any other dependent table
        $sql = "DELETE FROM OperationLength WHERE personnelFirstName=? AND personnelLastName=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $firstName, $lastName);
        $stmt->execute();
        $stmt->close();

        // Now, delete the personnel from the Personnels table
        $sql = "DELETE FROM Personnels WHERE firstName=? AND lastName=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $firstName, $lastName);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();
        header("Location: personnel.php"); // Redirect back to the personnel page
        exit();
    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaction in case of error
        echo "Error deleting personnel: " . $e->getMessage();
    }
} else {
    echo "No personnel specified";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Personnel</title>
</head>
<body>
    <h1>Delete Personnel</h1>
    <button onclick="window.location.href='personnel.php'">Back to Personnel</button>
</body>
</html>
