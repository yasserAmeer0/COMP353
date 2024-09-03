<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $famFirstName = $_POST['famFirstName'];
    $famLastName = $_POST['famLastName'];
    $famTelephoneNum = $_POST['famTelephoneNum'];
    $locPostalCode = $_POST['locPostalCode'];
    $locPhoneNum = $_POST['locPhoneNum'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    $sql = "INSERT INTO Association (famFirstName, famLastName, famTelephoneNum, locPostalCode, locPhoneNum, startDate, endDate) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $famFirstName, $famLastName, $famTelephoneNum, $locPostalCode, $locPhoneNum, $startDate, $endDate);

    if ($stmt->execute()) {
        header("Location: association.php");
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
    <title>Add Association</title>
</head>
<body>
    <h1>Add New Association</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        First Name: <input type="text" name="famFirstName" required><br>
        Last Name: <input type="text" name="famLastName" required><br>
        Telephone Number: <input type="text" name="famTelephoneNum" required><br>
        Postal Code: <input type="text" name="locPostalCode" required><br>
        Location Phone Number: <input type="text" name="locPhoneNum" required><br>
        Start Date: <input type="date" name="startDate" required><br>
        End Date: <input type="date" name="endDate"><br>
        <input type="submit" value="Submit">
    </form>
    <button onclick="window.location.href='association.php'">Back to Associations</button>
</body>
</html>
