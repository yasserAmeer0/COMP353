<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $telephoneNum = $_POST['telephoneNum'];
    $postalCode = $_POST['postalCode'];
    $phoneNum = $_POST['phoneNum'];
    $role = $_POST['role'];
    $mandate = $_POST['mandate'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    $sql = "INSERT INTO OperationLength (personnelFirstName, personnelLastName, personnelTelephoneNum, locPostalCode, locPhoneNum, role, mandate, startDate, endDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $firstName, $lastName, $telephoneNum, $postalCode, $phoneNum, $role, $mandate, $startDate, $endDate);

    if ($stmt->execute()) {
        header("Location: operationlength.php");
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
    <title>Add Operation Length</title>
</head>
<body>
    <h1>Add New Operation Length</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        First Name: <input type="text" name="firstName" required><br>
        Last Name: <input type="text" name="lastName" required><br>
        Telephone Number: <input type="text" name="telephoneNum" required><br>
