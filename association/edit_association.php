<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $famTelephoneNum = $_POST['famTelephoneNum'];
    $famFirstName = $_POST['famFirstName'];
    $famLastName = $_POST['famLastName'];
    $locPostalCode = $_POST['locPostalCode'];
    $locPhoneNum = $_POST['locPhoneNum'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    $sql = "UPDATE Association SET famFirstName=?, famLastName=?, locPostalCode=?, locPhoneNum=?, startDate=?, endDate=? WHERE famTelephoneNum=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $famFirstName, $famLastName, $locPostalCode, $locPhoneNum, $startDate, $endDate, $famTelephoneNum);

    if ($stmt->execute()) {
        header("Location: association.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}

// Assuming you pass `famTelephoneNum` via GET request to edit the record
if (isset($_GET['famTelephoneNum'])) {
    $famTelephoneNum = $_GET['famTelephoneNum'];
    $sql = "SELECT * FROM Association WHERE famTelephoneNum = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $famTelephoneNum);
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
    <title>Edit Association</title>
</head>
<body>
    <h1>Edit Association</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="famTelephoneNum" value="<?php echo $famTelephoneNum; ?>">
        First Name: <input type="text" name="famFirstName" value="<?php echo $row['famFirstName']; ?>"><br>
        Last Name: <input type="text" name="famLastName" value="<?php echo $row['famLastName']; ?>"><br>
        Postal Code: <input type="text" name="locPostalCode" value="<?php echo $row['locPostalCode']; ?>"><br>
        Location Phone Number: <input type="text" name="locPhoneNum" value="<?php echo $row['locPhoneNum']; ?>"><br>
        Start Date: <input type="date" name="startDate" value="<?php echo $row['startDate']; ?>"><br>
        End Date: <input type="date" name="endDate" value="<?php echo $row['endDate']; ?>"><br>
        <input type="submit" value="Update">
    </form>
    <button onclick="window.location.href='association.php'">Back to Associations</button>
</body>
</html>
