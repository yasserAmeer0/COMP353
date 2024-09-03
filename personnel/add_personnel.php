<?php
include '../config.php'; // Ensure your database connection file is properly set up

function getLocations($conn) {
    $locations = [];
    $sql = "SELECT postalCode, phoneNum FROM Location";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $locations[] = $row;
    }
    return $locations;
}

// Handling the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Extract and sanitize input
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $telephoneNum = $conn->real_escape_string($_POST['telephoneNum']);
    $dateOfBirth = $conn->real_escape_string($_POST['dateOfBirth']);
    $medicareNum = $conn->real_escape_string($_POST['medicareNum']);
    $SSN = $conn->real_escape_string($_POST['SSN']);
    $postalCode = $conn->real_escape_string($_POST['locPostalCode']);
    $emailAddress = $conn->real_escape_string($_POST['emailAddress']);
    $role = $conn->real_escape_string($_POST['role']);
    $mandate = $conn->real_escape_string($_POST['mandate']);
    $locPostalCode = $conn->real_escape_string($_POST['locPostalCode']);
    $locPhoneNum = $conn->real_escape_string($_POST['locPhoneNum']);

    // Start database transaction
    $conn->begin_transaction();

    try {
        // Insert into Individual
        $stmt = $conn->prepare("INSERT INTO Individual (firstName, lastName, telephoneNum, dateOfBirth, medicareNum, SSN, postalCode) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $firstName, $lastName, $telephoneNum, $dateOfBirth, $medicareNum, $SSN, $locPostalCode);
        $stmt->execute();
        $stmt->close();

        // Insert into Personnels
        $stmt = $conn->prepare("INSERT INTO Personnels (firstName, lastName, telephoneNum, emailAddress) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstName, $lastName, $telephoneNum, $emailAddress);
        $stmt->execute();
        $stmt->close();

        // Insert into OperationLength
        $startDate = date('Y-m-d'); // Current date for start date
        $endDate = NULL; // NULL for end date
        $stmt = $conn->prepare("INSERT INTO OperationLength (personnelFirstName, personnelLastName, personnelTelephoneNum, locPostalCode, locPhoneNum, role, mandate, startDate, endDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $firstName, $lastName, $telephoneNum, $locPostalCode, $locPhoneNum, $role, $mandate, $startDate, $endDate);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();
        echo "Personnel added successfully.";
    } catch (Exception $e) {
        // Something went wrong, rollback
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

$locations = getLocations($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Personnel</title>
</head>
<body>
    <h1>Add Personnel</h1>
    <form method="post" action="">
        First Name: <input type="text" name="firstName" required><br>
        Last Name: <input type="text" name="lastName" required><br>
        Telephone Number: <input type="text" name="telephoneNum" required><br>
        Date of Birth: <input type="date" name="dateOfBirth" required><br>
        Medicare Number: <input type="text" name="medicareNum" required><br>
        SSN: <input type="text" name="SSN" required><br>
        Email Address: <input type="email" name="emailAddress" required><br>
        Role: 
        <select name="role" required>
            <option value="Administrator">Administrator</option>
            <option value="Trainer">Trainer</option>
            <option value="Another">Another</option>
        </select><br>
        Mandate: 
        <select name="mandate" required>
            <option value="Volunteer">Volunteer</option>
            <option value="Salaries">Salaries</option>
        </select><br>
        Location Postal Code: 
        <select name="locPostalCode" required>
            <?php foreach ($locations as $location) {
                echo "<option value='{$location['postalCode']}'>{$location['postalCode']}</option>";
            } ?>
        </select><br>
        Location Phone Number: 
        <select name="locPhoneNum" required>
            <?php foreach ($locations as $location) {
                echo "<option value='{$location['phoneNum']}'>{$location['phoneNum']}</option>";
            } ?>
        </select><br>
        <input type="submit" name="submit" value="Add Personnel">
    </form>
    <button onclick="window.location.href='personnel.php'">Back to Personnel</button>
</body>
</html>
