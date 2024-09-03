<?php
include '../config.php';

// Function to fetch postal codes from the Area table
function getPostalCodes($conn) {
    $postalCodes = [];
    $sql = "SELECT postalCode FROM Area";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $postalCodes[] = $row['postalCode'];
    }
    return $postalCodes;
}

$postalCodes = getPostalCodes($conn);

// Check if the `firstName` and `lastName` are provided via GET request to edit the record
if (isset($_GET['firstName']) && isset($_GET['lastName'])) {
    $firstName = $conn->real_escape_string($_GET['firstName']);
    $lastName = $conn->real_escape_string($_GET['lastName']);
    $sql = "SELECT FamilyMembers.*, Individual.postalCode FROM FamilyMembers 
            LEFT JOIN Individual ON FamilyMembers.firstName = Individual.firstName 
            AND FamilyMembers.lastName = Individual.lastName 
            AND FamilyMembers.telephoneNum = Individual.telephoneNum
            WHERE FamilyMembers.firstName = ? AND FamilyMembers.lastName = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $firstName, $lastName);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
} else {
    // Redirect or display error if no name is provided
    echo "No family member selected for editing.";
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $originalFirstName = $conn->real_escape_string($_POST['originalFirstName']);
    $originalLastName = $conn->real_escape_string($_POST['originalLastName']);
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $telephoneNum = $conn->real_escape_string($_POST['telephoneNum']);
    $emailAddress = $conn->real_escape_string($_POST['emailAddress']);
    $postalCode = $conn->real_escape_string($_POST['postalCode']);

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Verify if postal code exists in Area
        $stmt = $conn->prepare("SELECT postalCode FROM Area WHERE postalCode = ?");
        $stmt->bind_param("s", $postalCode);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            throw new Exception("Selected postal code does not exist in the Area table.");
        }

        // Check if Individual exists
        $stmt = $conn->prepare("SELECT * FROM Individual WHERE firstName = ? AND lastName = ? AND telephoneNum = ?");
        $stmt->bind_param("sss", $firstName, $lastName, $telephoneNum);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // Individual does not exist, insert into Individual
            $stmt = $conn->prepare("INSERT INTO Individual (firstName, lastName, telephoneNum, postalCode) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $firstName, $lastName, $telephoneNum, $postalCode);
            $stmt->execute();
            $stmt->close();
        }

        // Update FamilyMembers
        $stmt = $conn->prepare("UPDATE FamilyMembers SET firstName=?, lastName=?, telephoneNum=?, emailAddress=? WHERE firstName=? AND lastName=?");
        $stmt->bind_param("ssssss", $firstName, $lastName, $telephoneNum, $emailAddress, $originalFirstName, $originalLastName);

        if ($stmt->execute()) {
            $conn->commit(); // Commit transaction
            header("Location: familymembers.php");
            exit();
        } else {
            throw new Exception("Error updating record: " . $stmt->error);
        }
    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaction in case of error
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
    <title>Edit Family Member</title>
</head>
<body>
    <h1>Edit Family Member</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?firstName=' . urlencode($firstName) . '&lastName=' . urlencode($lastName); ?>" method="post">
        <input type="hidden" name="originalFirstName" value="<?php echo htmlspecialchars($firstName); ?>">
        <input type="hidden" name="originalLastName" value="<?php echo htmlspecialchars($lastName); ?>">
        First Name: <input type="text" name="firstName" value="<?php echo htmlspecialchars($row['firstName']); ?>" required><br>
        Last Name: <input type="text" name="lastName" value="<?php echo htmlspecialchars($row['lastName']); ?>" required><br>
        Telephone Number: <input type="text" name="telephoneNum" value="<?php echo htmlspecialchars($row['telephoneNum']); ?>" required><br>
        Email Address: <input type="email" name="emailAddress" value="<?php echo htmlspecialchars($row['emailAddress']); ?>" required><br>
        Postal Code:
        <select name="postalCode" required>
            <?php foreach ($postalCodes as $code): ?>
                <option value="<?php echo $code; ?>" <?php echo (isset($row['postalCode']) && $code === $row['postalCode']) ? 'selected' : ''; ?>>
                    <?php echo $code; ?>
                </option>
            <?php endforeach; ?>
        </select><br>
        <input type="submit" value="Update">
    </form>
    <button onclick="window.location.href='familymembers.php'">Back to Family Members</button>
</body>
</html>
