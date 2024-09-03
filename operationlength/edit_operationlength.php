<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gathering data from the form
    $personnelTelephoneNum = $_POST['personnelTelephoneNum'];  // Key for update
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $postalCode = $_POST['postalCode'];
    $phoneNum = $_POST['phoneNum'];
    $role = $_POST['role'];
    $mandate = $_POST['mandate'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    // Prepare SQL query
    $sql = "UPDATE OperationLength SET personnelFirstName=?, personnelLastName=?, locPostalCode=?, locPhoneNum=?, role=?, mandate=?, startDate=?, endDate=? WHERE personnelTelephoneNum=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $firstName, $lastName, $postalCode, $phoneNum, $role, $mandate, $startDate, $endDate, $personnelTelephoneNum);

    // Execute and redirect or show error
    if ($stmt->execute()) {
        header("Location: operationlength.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}

// Load current data for display in the form
if (isset($_GET['personnelTelephoneNum'])) {
    $personnelTelephoneNum = $_GET['personnelTelephoneNum'];
    $sql = "SELECT * FROM OperationLength WHERE personnelTelephoneNum = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $personnelTelephoneNum);
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
    <title>Edit Operation Length</title>
</head>
<body>
    <h1>Edit Operation Length</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="personnelTelephoneNum" value="<?php echo $personnelTelephoneNum; ?>">
        First Name: <input type="text" name="firstName" value="<?php echo $row['personnelFirstName']; ?>" required><br>
        Last Name: <input type="text" name="lastName" value="<?php echo $row['personnelLastName']; ?>" required><br>
        Postal Code: <input type="text" name="postalCode" value="<?php echo $row['locPostalCode']; ?>" required><br>
        Location Phone Number: <input type="text" name="phoneNum" value="<?php echo $row['locPhoneNum']; ?>" required><br>
        Role: <input type="text" name="role" value="<?php echo $row['role']; ?>" required><br>
        Mandate: <input type="text" name="mandate" value="<?php echo $row['mandate']; ?>" required><br>
        Start Date: <input type="date" name="startDate" value="<?php echo $row['startDate']; ?>" required><br>
        End Date: <input type="date" name="endDate" value="<?php echo $row['endDate']; ?>"><br>
        <input type="submit" value="Update">
    </form>
    <button onclick="window.location.href='operationlength.php'">Back to Operation Lengths</button>
</body>
</html>
