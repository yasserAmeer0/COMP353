<?php
include '../config.php';

// Fetch postal codes
function getPostalCodes($conn) {
    $postalCodes = [];
    $sql = "SELECT postalCode FROM Area";
    $result = $conn->query($sql);
    if (!$result) {
        die("Error fetching postal codes: " . $conn->error);
    }
    while ($row = $result->fetch_assoc()) {
        $postalCodes[] = $row['postalCode'];
    }
    return $postalCodes;
}

$postalCodes = getPostalCodes($conn);

$row = null; // Initialize $row to prevent undefined variable errors

// Use membership number for identification
if (isset($_GET['membershipNum'])) {
    $membershipNum = $conn->real_escape_string($_GET['membershipNum']);

    $sql = "SELECT ClubMembers.*, Individual.postalCode FROM ClubMembers 
            JOIN Individual ON ClubMembers.firstName = Individual.firstName AND 
                              ClubMembers.lastName = Individual.lastName AND 
                              ClubMembers.telephoneNum = Individual.telephoneNum
            WHERE ClubMembers.membershipNum = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $membershipNum);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "No matching club member found.";
        exit();
    }
    $stmt->close();
} else {
    echo "No club member selected for editing.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $membershipNum = $conn->real_escape_string($_POST['membershipNum']);
    $newFirstName = $conn->real_escape_string($_POST['firstName']);
    $newLastName = $conn->real_escape_string($_POST['lastName']);
    $newTelephoneNum = $conn->real_escape_string($_POST['telephoneNum']);
    $newGender = $conn->real_escape_string($_POST['gender']);
    $newPostalCode = $conn->real_escape_string($_POST['postalCode']);

    // Begin transaction
    $conn->begin_transaction();
    try {
        // Update Individual
        $stmt = $conn->prepare("UPDATE Individual SET firstName = ?, lastName = ?, telephoneNum = ?, postalCode = ? WHERE firstName = ? AND lastName = ? AND telephoneNum = ?");
        $stmt->bind_param("sssssss", $newFirstName, $newLastName, $newTelephoneNum, $newPostalCode, $row['firstName'], $row['lastName'], $row['telephoneNum']);
        if (!$stmt->execute()) {
            throw new Exception("Failed to update Individual: " . $stmt->error);
        }
        $stmt->close();

        // Update ClubMembers
        $stmt = $conn->prepare("UPDATE ClubMembers SET firstName = ?, lastName = ?, telephoneNum = ?, gender = ? WHERE membershipNum = ?");
        $stmt->bind_param("ssssi", $newFirstName, $newLastName, $newTelephoneNum, $newGender, $membershipNum);
        if (!$stmt->execute()) {
            throw new Exception("Failed to update ClubMember: " . $stmt->error);
        }
        $stmt->close();

        // Commit transaction
        $conn->commit();
        echo "<script>alert('Club member updated successfully.'); window.location.href='clubmembers.php';</script>";
        exit();
    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaction in case of error
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Club Member</title>
</head>
<body>
    <h1>Edit Club Member</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?membershipNum=' . urlencode($membershipNum); ?>" method="post">
        <input type="hidden" name="membershipNum" value="<?php echo htmlspecialchars($membershipNum); ?>">
        First Name: <input type="text" name="firstName" value="<?php echo htmlspecialchars($row['firstName'] ?? ''); ?>" required><br>
        Last Name: <input type="text" name="lastName" value="<?php echo htmlspecialchars($row['lastName'] ?? ''); ?>" required><br>
        Telephone Number: <input type="text" name="telephoneNum" value="<?php echo htmlspecialchars($row['telephoneNum'] ?? ''); ?>" required><br>
        Gender: <select name="gender">
            <option value="Male" <?php echo (isset($row) && $row['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo (isset($row) && $row['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
        </select><br>
        Postal Code:
        <select name="postalCode" required>
            <?php foreach ($postalCodes as $code): ?>
                <option value="<?php echo $code; ?>" <?php echo (isset($row) && $code === $row['postalCode']) ? 'selected' : ''; ?>>
                    <?php echo $code; ?>
                </option>
            <?php endforeach; ?>
        </select><br>
        <input type="submit" value="Update">
    </form>
    <button onclick="window.location.href='clubmembers.php'">Back to Club Members</button>
</body>
</html>
