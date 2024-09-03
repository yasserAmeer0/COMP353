<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gather data from the form
    $membershipNum = $_POST['membershipNum'];  // Primary key, assumed not to change
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $telephoneNum = $_POST['telephoneNum'];
    $relationship = $_POST['relationship'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $primFamFirstName = $_POST['primFamFirstName'];
    $primFamLastName = $_POST['primFamLastName'];
    $primFamTelephoneNum = $_POST['primFamTelephoneNum'];

    // SQL query to update the relation
    $sql = "UPDATE Relations SET firstName=?, lastName=?, telephoneNum=?, relationship=?, startDate=?, endDate=?, primFamFirstName=?, primFamLastName=?, primFamTelephoneNum=? WHERE membershipNum=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $firstName, $lastName, $telephoneNum, $relationship, $startDate, $endDate, $primFamFirstName, $primFamLastName, $primFamTelephoneNum, $membershipNum);

    if ($stmt->execute()) {
        header("Location: relations.php");
        exit();
    } else {
        echo "Error updating relation: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}

// Fetch existing data for the form
if (isset($_GET['membershipNum'])) {
    $membershipNum = $_GET['membershipNum'];
    $sql = "SELECT * FROM Relations WHERE membershipNum = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $membershipNum);
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
    <title>Edit Relation</title>
</head>
<body>
    <h1>Edit Relation</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="membershipNum" value="<?php echo $membershipNum; ?>">
        First Name: <input type="text" name="firstName" value="<?php echo $row['firstName']; ?>"><br>
        Last Name: <input type="text" name="lastName" value="<?php echo $row['lastName']; ?>"><br>
        Telephone Number: <input type="text" name="telephoneNum" value="<?php echo $row['telephoneNum']; ?>"><br>
        Relationship: <input type="text" name="relationship" value="<?php echo $row['relationship']; ?>"><br>
        Start Date: <input type="date" name="startDate" value="<?php echo $row['startDate']; ?>"><br>
        End Date: <input type="date" name="endDate" value="<?php echo $row['endDate']; ?>"><br>
        Primary First Name: <input type="text" name="primFamFirstName" value="<?php echo $row['primFamFirstName']; ?>"><br>
        Primary Last Name: <input type="text" name="primFamLastName" value="<?php echo $row['primFamLastName']; ?>"><br>
        Primary Phone Number: <input type="text" name="primFamTelephoneNum" value="<?php echo $row['primFamTelephoneNum']; ?>"><br>
        <input type="submit" value="Update">
    </form>
    <button onclick="window.location.href='relations.php'">Back to Relations</button>
</body>
</html>
