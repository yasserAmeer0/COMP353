<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $telephoneNum = $_POST['telephoneNum'];
    $membershipNum = $_POST['membershipNum'];
    $relationship = $_POST['relationship'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $primFamFirstName = $_POST['primFamFirstName'];
    $primFamLastName = $_POST['primFamLastName'];
    $primFamTelephoneNum = $_POST['primFamTelephoneNum'];

    $sql = "INSERT INTO Relations (firstName, lastName, telephoneNum, membershipNum, relationship, startDate, endDate, primFamFirstName, primFamLastName, primFamTelephoneNum) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssissssss", $firstName, $lastName, $telephoneNum, $membershipNum, $relationship, $startDate, $endDate, $primFamFirstName, $primFamLastName, $primFamTelephoneNum);

    if ($stmt->execute()) {
        header("Location: relations.php");
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
    <title>Add Relation</title>
</head>
<body>
    <h1>Add New Relation</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        First Name: <input type="text" name="firstName" required><br>
        Last Name: <input type="text" name="lastName" required><br>
        Telephone Number: <input type="text" name="telephoneNum" required><br>
        Membership Number: <input type="text" name="membershipNum" required><br>
        Relationship: <input type="text" name="relationship" required><br>
        Start Date: <input type="date" name="startDate" required><br>
        End Date: <input type="date" name="endDate"><br>
        Primary First Name: <input type="text" name="primFamFirstName" required><br>
        Primary Last Name: <input type="text" name="primFamLastName" required><br>
        Primary Phone Number: <input type="text" name="primFamTelephoneNum" required><br>
        <input type="submit" value="Submit">
    </form>
    <button onclick="window.location.href='relations.php'">Back to Relations</button>
</body>
</html>
