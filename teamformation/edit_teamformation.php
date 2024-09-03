<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dateTime = $_POST['dateTime'];
    $teamName = $_POST['teamName'];
    $coachFirstName = $_POST['coachFirstName'];
    $coachLastName = $_POST['coachLastName'];
    $coachTelephoneNum = $_POST['coachTelephoneNum'];
    $locPostalCode = $_POST['locPostalCode'];
    $locPhoneNum = $_POST['locPhoneNum'];
    $score = $_POST['score'];
    $gender = $_POST['gender'];

    $sql = "UPDATE TeamFormation SET coachFirstName=?, coachLastName=?, coachTelephoneNum=?, locPostalCode=?, locPhoneNum=?, score=?, gender=? WHERE dateTime=? AND teamName=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssisss", $coachFirstName, $coachLastName, $coachTelephoneNum, $locPostalCode, $locPhoneNum, $score, $gender, $dateTime, $teamName);

    if ($stmt->execute()) {
        header("Location: teamformation.php");
        exit();
    } else {
        echo "Error updating team formation: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}

if (isset($_GET['dateTime']) && isset($_GET['teamName'])) {
    $dateTime = $_GET['dateTime'];
    $teamName = $_GET['teamName'];
    $sql = "SELECT * FROM TeamFormation WHERE dateTime = ? AND teamName = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $dateTime, $teamName);
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
    <title>Edit Team Formation</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="form-container">
        <h1>Edit Team Formation</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="dateTime" value="<?php echo htmlspecialchars($dateTime); ?>">
            <input type="hidden" name="teamName" value="<?php echo htmlspecialchars($teamName); ?>">
            <div class="form-group">
                <label for="coachFirstName">Coach First Name:</label>
                <input type="text" name="coachFirstName" value="<?php echo htmlspecialchars($row['coachFirstName']); ?>" required>
            </div>
            <div class="form-group">
                <label for="coachLastName">Coach Last Name:</label>
                <input type="text" name="coachLastName" value="<?php echo htmlspecialchars($row['coachLastName']); ?>" required>
            </div>
            <div class="form-group">
                <label for="coachTelephoneNum">Coach Telephone Number:</label>
                <input type="text" name="coachTelephoneNum" value="<?php echo htmlspecialchars($row['coachTelephoneNum']); ?>" required>
            </div>
            <div class="form-group">
                <label for="locPostalCode">Location Postal Code:</label>
                <input type="text" name="locPostalCode" value="<?php echo htmlspecialchars($row['locPostalCode']); ?>" required>
            </div>
            <div class="form-group">
                <label for="locPhoneNum">Location Phone Number:</label>
                <input type="text" name="locPhoneNum" value="<?php echo htmlspecialchars($row['locPhoneNum']); ?>" required>
            </div>
            <div class="form-group">
                <label for="score">Score:</label>
                <input type="number" name="score" value="<?php echo htmlspecialchars($row['score']); ?>" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select name="gender" required>
                    <option value="Male" <?php if ($row['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ($row['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                </select>
            </div>
            <div class="form-row buttons">
                <input type="submit" class="submit-btn" value="Save Changes">
                <button class="back-btn" onclick="window.location.href='teamformation.php'">Back to Team Formations</button>
            </div>
        </form>
    </div>
</body>
</html>
