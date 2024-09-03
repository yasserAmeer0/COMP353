<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collecting data from the form
    $DateTime = $_POST['DateTime'];
    $coachFirstName = $_POST['coachFirstName'];
    $coachLastName = $_POST['coachLastName'];
    $coachTelephoneNum = $_POST['coachTelephoneNum'];
    $membershipNum = $_POST['membershipNum'];  // Primary key, cannot change
    $role = $_POST['role'];

    // SQL Update Statement
    $sql = "UPDATE TeamMembers SET DateTime=?, coachFirstName=?, coachLastName=?, coachTelephoneNum=?, role=? WHERE membershipNum=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $DateTime, $coachFirstName, $coachLastName, $coachTelephoneNum, $role, $membershipNum);

    if ($stmt->execute()) {
        header("Location: teammembers.php");
        exit();
    } else {
        echo "Error updating team member: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}

// Fetching existing data for display in the form
if (isset($_GET['membershipNum'])) {
    $membershipNum = $_GET['membershipNum'];
    $sql = "SELECT * FROM TeamMembers WHERE membershipNum = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $membershipNum);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Team Member</title>
</head>
<body>
    <h1>Edit Team Member</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="membershipNum" value="<?php echo $membershipNum; ?>">
        Date-Time: <input type="datetime-local" name="DateTime" value="<?php echo date('Y-m-d\TH:i', strtotime($row['DateTime'])); ?>" required><br>
        Coach First Name: <input type="text" name="coachFirstName" value="<?php echo $row['coachFirstName']; ?>" required><br>
        Coach Last Name: <input type="text" name="coachLastName" value="<?php echo $row['coachLastName']; ?>" required><br>
        Coach Telephone Number: <input type="text" name="coachTelephoneNum" value="<?php echo $row['coachTelephoneNum']; ?>" required><br>
        Role: <input type="text" name="role" value="<?php echo $row['role']; ?>" required><br>
        <input type="submit" value="Update">
    </form>
    <button onclick="window.location.href='teammembers.php'">Back to Team Members</button>
</body>

</html>
