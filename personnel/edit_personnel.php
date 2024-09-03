<?php
include '../config.php';

$originalFirstName = $originalLastName = $originalTelephoneNum = "";
$newFirstName = $newLastName = $newTelephoneNum = $newEmail = "";
$row = array('firstName' => '', 'lastName' => '', 'telephoneNum' => '', 'emailAddress' => '');

// Retrieve existing personnel data to prefill the form for editing
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['firstName']) && isset($_GET['lastName']) && isset($_GET['telephoneNum'])) {
    $firstName = $conn->real_escape_string($_GET['firstName']);
    $lastName = $conn->real_escape_string($_GET['lastName']);
    $telephoneNum = $conn->real_escape_string($_GET['telephoneNum']);

    $sql = "SELECT Personnels.*, Individual.dateOfBirth, Individual.medicareNum, Individual.SSN, Individual.postalCode FROM Personnels JOIN Individual ON Personnels.firstName = Individual.firstName AND Personnels.lastName = Individual.lastName AND Personnels.telephoneNum = Individual.telephoneNum WHERE Personnels.firstName=? AND Personnels.lastName=? AND Personnels.telephoneNum=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $firstName, $lastName, $telephoneNum);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Personnel not found";
        exit();
    }
    $stmt->close();

    // Set variables for form defaults
    $originalFirstName = $firstName;
    $originalLastName = $lastName;
    $originalTelephoneNum = $telephoneNum;
}

// Handle form submission for updating personnel details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $originalFirstName = $conn->real_escape_string($_POST['originalFirstName']);
    $originalLastName = $conn->real_escape_string($_POST['originalLastName']);
    $originalTelephoneNum = $conn->real_escape_string($_POST['originalTelephoneNum']);

    $newFirstName = $conn->real_escape_string($_POST['firstName']);
    $newLastName = $conn->real_escape_string($_POST['lastName']);
    $newTelephoneNum = $conn->real_escape_string($_POST['telephoneNum']);
    $newEmail = $conn->real_escape_string($_POST['emailAddress']);

    // Start a transaction to ensure atomic updates
    $conn->begin_transaction();
    try {
        // Update Individual
        $stmt = $conn->prepare("UPDATE Individual SET firstName = ?, lastName = ?, telephoneNum = ? WHERE firstName = ? AND lastName = ? AND telephoneNum = ?");
        $stmt->bind_param("ssssss", $newFirstName, $newLastName, $newTelephoneNum, $originalFirstName, $originalLastName, $originalTelephoneNum);
        $stmt->execute();
        $stmt->close();

        // Update Personnels
        $stmt = $conn->prepare("UPDATE Personnels SET firstName = ?, lastName = ?, telephoneNum = ?, emailAddress = ? WHERE firstName = ? AND lastName = ? AND telephoneNum = ?");
        $stmt->bind_param("sssssss", $newFirstName, $newLastName, $newTelephoneNum, $newEmail, $originalFirstName, $originalLastName, $originalTelephoneNum);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        echo "<script>alert('Personnel updated successfully.'); window.location.href='personnel.php';</script>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Personnel</title>
</head>
<body>
    <h1>Edit Personnel</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="originalFirstName" value="<?php echo htmlspecialchars($originalFirstName); ?>">
        <input type="hidden" name="originalLastName" value="<?php echo htmlspecialchars($originalLastName); ?>">
        <input type="hidden" name="originalTelephoneNum" value="<?php echo htmlspecialchars($originalTelephoneNum); ?>">
        First Name: <input type="text" name="firstName" value="<?php echo htmlspecialchars($newFirstName ?: $row['firstName']); ?>" required><br>
        Last Name: <input type="text" name="lastName" value="<?php echo htmlspecialchars($newLastName ?: $row['lastName']); ?>" required><br>
        Phone Number: <input type="text" name="telephoneNum" value="<?php echo htmlspecialchars($newTelephoneNum ?: $row['telephoneNum']); ?>" required><br>
        Email Address: <input type="email" name="emailAddress" value="<?php echo htmlspecialchars($newEmail ?: $row['emailAddress']); ?>" required><br>
        <input type="submit" value="Update">
    </form>
    <button onclick="window.location.href='personnel.php'">Back to Personnel</button>
</body>
</html>
