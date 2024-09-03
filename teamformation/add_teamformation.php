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

    $sql = "INSERT INTO TeamFormation (dateTime, teamName, coachFirstName, coachLastName, coachTelephoneNum, locPostalCode, locPhoneNum, score, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssis", $dateTime, $teamName, $coachFirstName, $coachLastName, $coachTelephoneNum, $locPostalCode, $locPhoneNum, $score, $gender);

    if ($stmt->execute()) {
        header("Location: teamformation.php");
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
    <title>Add Team Formation</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="form-container">
        <h1>Add New Team Formation</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="dateTime">Date-Time:</label>
                <input type="datetime-local" name="dateTime" required>
            </div>
            <div class="form-group">
                <label for="teamName">Team Name:</label>
                <input type="text" name="teamName" required>
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
                <label for="locPostalCode">Location Postal Code:</label>
                <input type="text" name="locPostalCode" required>
            </div>
            <div class="form-group">
                <label for="locPhoneNum">Location Phone Number:</label>
                <input type="text" name="locPhoneNum" required>
            </div>
            <div class="form-group">
                <label for="score">Score:</label>
                <input type="number" name="score" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="form-row buttons">
                <input type="submit" class="submit-btn" value="Submit">
                <button class="back-btn" onclick="window.location.href='teamformation.php'">Back to Team Formations</button>
            </div>
        </form>
    </div>
</body>
</html>
