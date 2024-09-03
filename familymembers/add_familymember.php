<?php
include '../config.php';

// Function to fetch postal codes from the Location table
function getLocations($conn) {
    $locations = [];
    $sql = "SELECT postalCode, phoneNum FROM Location";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $locations[] = $row;
    }
    return $locations;
}

$locations = getLocations($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract and sanitize input
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $telephoneNum = $conn->real_escape_string($_POST['telephoneNum']);
    $emailAddress = $conn->real_escape_string($_POST['emailAddress']);

    // Split the selected value into locPostalCode and locPhoneNum
    list($locPostalCode, $locPhoneNum) = explode(',', $conn->real_escape_string($_POST['location']));

    // Start date and end date for the association
    $startDate = date('Y-m-d');
    $endDate = NULL; // NULL for the end date

    // Start database transaction
    $conn->begin_transaction();

    try {
        // Check if Individual exists
        $stmt = $conn->prepare("SELECT * FROM Individual WHERE firstName = ? AND lastName = ? AND telephoneNum = ?");
        $stmt->bind_param("sss", $firstName, $lastName, $telephoneNum);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // Individual does not exist, insert into Individual
            $stmt = $conn->prepare("INSERT INTO Individual (firstName, lastName, telephoneNum, postalCode) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $firstName, $lastName, $telephoneNum, $locPostalCode);
            $stmt->execute();
            $stmt->close();
        }

        // Insert into FamilyMembers
        $stmt = $conn->prepare("INSERT INTO FamilyMembers (firstName, lastName, telephoneNum, emailAddress) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstName, $lastName, $telephoneNum, $emailAddress);
        $stmt->execute();

        // Insert into Association
        $stmt = $conn->prepare("INSERT INTO Association (famFirstName, famLastName, famTelephoneNum, locPostalCode, locPhoneNum, startDate, endDate) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $firstName, $lastName, $telephoneNum, $locPostalCode, $locPhoneNum, $startDate, $endDate);
        $stmt->execute();

        // Commit transaction
        $conn->commit();
        echo "Family member  added successfully.";
    } catch (Exception $e) {
        // Something went wrong, rollback
        $conn->rollback();
        echo "Error: " . $e->getMessage();
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
    <title>Add Family Member</title>
</head>
<body>
    <h1>Add New Family Member</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        First Name: <input type="text" name="firstName" required><br>
        Last Name: <input type="text" name="lastName" required><br>
        Telephone Number: <input type="text" name="telephoneNum" required><br>
        Email Address: <input type="email" name="emailAddress" required><br>
        Location:
        <select name="location" required>
            <?php foreach ($locations as $location): ?>
                <option value="<?php echo $location['postalCode'] . ',' . $location['phoneNum']; ?>">
                    <?php echo $location['postalCode'] . ' - ' . $location['phoneNum']; ?>
                </option>
            <?php endforeach; ?>
        </select><br>
        <input type="submit" value="Submit">
    </form>
    <button onclick="window.location.href='familymembers.php'">Back to Family Members</button>
</body>
</html>
