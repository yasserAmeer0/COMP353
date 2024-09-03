<?php
include '../config.php';  // Ensure your database connection file is correctly set up

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $telephoneNum = $conn->real_escape_string($_POST['telephoneNum']);
    $membershipNum = $conn->real_escape_string($_POST['membershipNum']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $dateOfBirth = $conn->real_escape_string($_POST['dateOfBirth']);
    $medicareNum = $conn->real_escape_string($_POST['medicareNum']);
    $SSN = $conn->real_escape_string($_POST['SSN']);
    $postalCode = $conn->real_escape_string($_POST['postalCode']);

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
            $stmt = $conn->prepare("INSERT INTO Individual (firstName, lastName, telephoneNum, dateOfBirth, medicareNum, SSN, postalCode) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $firstName, $lastName, $telephoneNum, $dateOfBirth, $medicareNum, $SSN, $postalCode);
            $stmt->execute();
            $stmt->close();
        }

        // Insert into ClubMembers
        $sql = "INSERT INTO ClubMembers (firstName, lastName, telephoneNum, membershipNum, gender) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssis", $firstName, $lastName, $telephoneNum, $membershipNum, $gender);

        if ($stmt->execute()) {
            $conn->commit();  // Commit transaction
            header("Location: clubmembers.php");
            exit();
        } else {
            throw new Exception("Error: " . $stmt->error);
        }
    } catch (Exception $e) {
        // Rollback transaction in case of error
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
    <title>Add Club Member</title>
</head>
<body>
    <h1>Add New Club Member</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        First Name: <input type="text" name="firstName" required><br>
        Last Name: <input type="text" name="lastName" required><br>
        Telephone Number: <input type="text" name="telephoneNum" required><br>
        Date of Birth: <input type="date" name="dateOfBirth" required><br>
        Medicare Number: <input type="text" name="medicareNum" required><br>
        SSN: <input type="text" name="SSN" required><br>
        Postal Code: 
        <select name="postalCode" required>
            <?php foreach ($postalCodes as $code) {
                echo "<option value='{$code}'>{$code}</option>";
            } ?>
        </select><br>
        Membership Number: <input type="text" name="membershipNum" required><br>
        Gender: <select name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select><br>
        <input type="submit" value="Submit">
    </form>
    <button onclick="window.location.href='clubmembers.php'">Back to Club Members</button>
</body>
</html>
