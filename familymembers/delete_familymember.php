<?php
include '../config.php';

// Check if `firstName` and `lastName` are provided via GET request
if (isset($_GET['firstName']) && isset($_GET['lastName'])) {
    $firstName = $conn->real_escape_string($_GET['firstName']);
    $lastName = $conn->real_escape_string($_GET['lastName']);

    // Delete the family member record
    $sql = "DELETE FROM FamilyMembers WHERE firstName = ? AND lastName = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $firstName, $lastName);

    if ($stmt->execute()) {
        // Successfully deleted, redirect to the main family members page
        header("Location: familymembers.php");
        exit();
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No family member specified for deletion.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Family Member</title>
</head>
<body>
    <h1>Delete Family Member</h1>
    <button onclick="window.location.href='familymembers.php'">Back to Family Members</button>
</body>
</html>
