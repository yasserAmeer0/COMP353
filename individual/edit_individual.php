<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $SSN = $_POST['SSN'];  // Primary key, assumed not to change
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $telephoneNum = $_POST['telephoneNum'];
    $dateofBirth = $_POST['dateofBirth'];
    $medicareNum = $_POST['medicareNum'];
    $postalCode = $_POST['postalCode'];

    $sql = "UPDATE Individual SET firstName=?, lastName=?, telephoneNum=?, dateofBirth=?, medicareNum=?, postalCode=? WHERE SSN=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $firstName, $lastName, $telephoneNum, $dateofBirth, $medicareNum, $postalCode, $SSN);

    if ($stmt->execute()) {
        header("Location: individual.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}

// Assuming you pass `SSN` via GET request to edit the record
if (isset($_GET['SSN'])) {
    $SSN = $_GET['SSN'];
    $sql = "SELECT * FROM Individual WHERE SSN = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $SSN);
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
    <title>Edit Individual</title>
</head>
<body>
    <h1>Edit Individual</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="SSN" value="<?php echo $SSN; ?>">
        First Name: <input type="text" name="firstName" value="<?php echo $row['firstName']; ?>"><br>
        Last Name: <input type="text" name="lastName" value="<?php echo $row['lastName']; ?>"><br>
        Telephone Number: <input type="text" name="telephoneNum" value="<?php echo $row['telephoneNum']; ?>"><br>
        Date of Birth: <input type="date" name="dateofBirth" value="<?php echo $row['dateofBirth']; ?>"><br>
        Medicare Number: <input type="text" name="medicareNum" value="<?php echo $row['medicareNum']; ?>"><br>
        Postal Code: <input type="text" name="postalCode" value="<?php echo $row['postalCode']; ?>"><br>
        <input type="submit" value="Update">
    </form>
    <button onclick="window.location.href='individual.php'">Back to Individuals</button>
</body>
</html>
