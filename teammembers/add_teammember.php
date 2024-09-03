<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $DateTime = $_POST['DateTime'];
    $coachFirstName = $_POST['coachFirstName'];
    $coachLastName = $_POST['coachLastName'];
    $coachTelephoneNum = $_POST['coachTelephoneNum'];
    $membershipNum = $_POST['membershipNum'];
    $role = $_POST['role'];

    $sql = "INSERT INTO TeamMembers (DateTime, coachFirstName, coachLastName, coachTelephoneNum, membershipNum, role) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssis", $DateTime, $coachFirstName, $coachLastName, $coachTelephoneNum, $membershipNum, $role);

    if ($stmt->execute()) {
        header("Location: teammembers.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
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
    <title>Add Team Member</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="form-container">
        <h1>Add New Team Member</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="DateTime">Date-Time:</label>
                <input type="datetime-local" name="DateTime" required>
            </div>
            <div class="form-group">
                <label for="coachFirstName">Coach First Name:</label>
                <input type="text" name="coachFirstName" required>
            </div>
            <div class="form-group">
                <label for="coachLastName">Coach Last Name:</label>
                <input type="text" name="coachLastName" required>
            </div>
            <div class="form-group">
                <label for="coachTelephoneNum">Coach Telephone Number:</label>
                <input type="text" name="coachTelephoneNum" required>
            </div>
            <div class="form-group">
                <label for="membershipNum">Membership Number:</label>
                <input type="number" name="membershipNum" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <input type="text" name="role" required>
            </div>
            <div class="form-row buttons">
                <input type="submit" class="submit-btn" value="Submit">
                <button class="back-btn" onclick="window.location.href='teammembers.php'">Back to Team Members</button>
            </div>
        </form>
    </div>
</body>
</html>
