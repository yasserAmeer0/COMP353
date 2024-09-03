<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $telephoneNum = $_POST['telephoneNum'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $medicareNum = $_POST['medicareNum'];
    $SSN = $_POST['SSN'];
    $postalCode = $_POST['postalCode'];

    $sql = "INSERT INTO Individual (firstName, lastName, telephoneNum, datefBirth, medicareNum, SSN, postalCode) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $firstName, $lastName, $telephoneNum, $dateOfBirth, $medicareNum, $SSN, $postalCode);

    if ($stmt->execute()) {
        header("Location: individual.php");
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
    <title>Add Individual</title>
</head>
<body>
    <h1>Add New Individual</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        First Name: <input type="text" name="firstName" required><br>
        Last Name: <input type="text" name="lastName" required><br>
        Telephone Number: <input type="text" name="telephoneNum" required><br>
        Date of Birth: <input type="date" name="dateofBirth" required><br>
        Medicare Number: <input type="text" name="medicareNum" required><br>
        SSN: <input type="text" name="SSN" required><br>
        Postal Code: <input type="text" name="postalCode" required><br>
        <input type="submit" value="Submit">
    </form>
    <button onclick="window.location.href='individual.php'">Back to Individuals</button>
</body>
</html>
