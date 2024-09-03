<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dateTime = $_POST['dateTime'];
    $coachFirstName1 = $_POST['coachFirstName1'];
    $coachLastName1 = $_POST['coachLastName1'];
    $coachTelephoneNum1 = $_POST['coachTelephoneNum1'];
    $coachFirstName2 = $_POST['coachFirstName2'];
    $coachLastName2 = $_POST['coachLastName2'];
    $coachTelephoneNum2 = $_POST['coachTelephoneNum2'];
    $type = $_POST['type'];
    $locPostalCode = $_POST['locPostalCode'];
    $locPhoneNum = $_POST['locPhoneNum'];

    $sql = "INSERT INTO Session (DateTime, coachFirstName1, coachLastName1, coachTelephoneNum1, coachFirstName2, coachLastName2, coachTelephoneNum2, type, locPostalCode, locPhoneNum) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $dateTime, $coachFirstName1, $coachLastName1, $coachTelephoneNum1, $coachFirstName2, $coachLastName2, $coachTelephoneNum2, $type, $locPostalCode, $locPhoneNum);

    if ($stmt->execute()) {
        header("Location: session.php");
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
    <title>Add Session</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="form-container">
        <h1>Add New Session</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="dateTime">Date-Time:</label>
                <input type="datetime-local" name="dateTime" required>
            </div>
            <div class="form-group">
                <label for="coachFirstName1">Coach First Name 1:</label>
                <input type="text" name="coachFirstName1" required>
            </div>
            <div class="form-group">
                <label for="coachLastName1">Coach Last Name 1:</label>
                <input type="text" name="coachLastName1" required>
            </div>
            <div class="form-group">
                <label for="coachTelephoneNum1">Coach Telephone Number 1:</label>
                <input type="text" name="coachTelephoneNum1" required>
            </div>
            <div class="form-group">
                <label for="coachFirstName2">Coach First Name 2:</label>
                <input type="text" name="coachFirstName2">
            </div>
            <div class="form-group">
                <label for="coachLastName2">Coach Last Name 2:</label>
                <input type="text" name="coachLastName2">
            </div>
            <div class="form-group">
                <label for="coachTelephoneNum2">Coach Telephone Number 2:</label>
                <input type="text" name="coachTelephoneNum2">
            </div>
            <div class="form-group">
                <label for="type">Type:</label>
                <input type="text" name="type" required>
            </div>
            <div class="form-group">
                <label for="locPostalCode">Location Postal Code:</label>
                <input type="text" name="locPostalCode" required>
            </div>
            <div class="form-group">
                <label for="locPhoneNum">Location Phone Number:</label>
                <input type="text" name="locPhoneNum" required>
            </div>
            <div class="form-row buttons">
                <input type="submit" class="submit-btn" value="Submit">
                <button class="back-btn" onclick="window.location.href='session.php'">Back to Sessions</button>
            </div>
        </form>
    </div>
</body>
</html>
