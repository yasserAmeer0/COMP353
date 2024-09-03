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

    $sql = "UPDATE Session SET coachFirstName1 = ?, coachLastName1 = ?, coachTelephoneNum1 = ?, coachFirstName2 = ?, coachLastName2 = ?, coachTelephoneNum2 = ?, type = ?, locPostalCode = ?, locPhoneNum = ? WHERE DateTime = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $coachFirstName1, $coachLastName1, $coachTelephoneNum1, $coachFirstName2, $coachLastName2, $coachTelephoneNum2, $type, $locPostalCode, $locPhoneNum, $dateTime);

    if ($stmt->execute()) {
        header("Location: session.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
} else {
    $dateTime = $_GET['DateTime'];
    $sql = "SELECT * FROM Session WHERE DateTime = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $dateTime);
    $stmt->execute();
    $result = $stmt->get_result();
    $session = $result->fetch_assoc();

    if (!$session) {
        die("Session not found.");
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
    <title>Edit Session</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="form-container">
        <h1>Edit Session</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="dateTime" value="<?= htmlspecialchars($session['DateTime']) ?>">
            <div class="form-group">
                <label for="coachFirstName1">Coach First Name 1:</label>
                <input type="text" name="coachFirstName1" value="<?= htmlspecialchars($session['coachFirstName1']) ?>" required>
            </div>
            <div class="form-group">
                <label for="coachLastName1">Coach Last Name 1:</label>
                <input type="text" name="coachLastName1" value="<?= htmlspecialchars($session['coachLastName1']) ?>" required>
            </div>
            <div class="form-group">
                <label for="coachTelephoneNum1">Coach Telephone Number 1:</label>
                <input type="text" name="coachTelephoneNum1" value="<?= htmlspecialchars($session['coachTelephoneNum1']) ?>" required>
            </div>
            <div class="form-group">
                <label for="coachFirstName2">Coach First Name 2:</label>
                <input type="text" name="coachFirstName2" value="<?= htmlspecialchars($session['coachFirstName2']) ?>">
            </div>
            <div class="form-group">
                <label for="coachLastName2">Coach Last Name 2:</label>
                <input type="text" name="coachLastName2" value="<?= htmlspecialchars($session['coachLastName2']) ?>">
            </div>
            <div class="form-group">
                <label for="coachTelephoneNum2">Coach Telephone Number 2:</label>
                <input type="text" name="coachTelephoneNum2" value="<?= htmlspecialchars($session['coachTelephoneNum2']) ?>">
            </div>
            <div class="form-group">
                <label for="type">Type:</label>
                <input type="text" name="type" value="<?= htmlspecialchars($session['type']) ?>" required>
            </div>
            <div class="form-group">
                <label for="locPostalCode">Location Postal Code:</label>
                <input type="text" name="locPostalCode" value="<?= htmlspecialchars($session['locPostalCode']) ?>" required>
            </div>
            <div class="form-group">
                <label for="locPhoneNum">Location Phone Number:</label>
                <input type="text" name="locPhoneNum" value="<?= htmlspecialchars($session['locPhoneNum']) ?>" required>
            </div>
            <div class="form-row">
                <input type="submit" class="submit-btn" value="Submit">
                <button type="button" class="back-btn" onclick="window.location.href='session.php'">Back to Sessions</button>
            </div>
        </form>
    </div>
</body>
</html>
