<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postalCode = $_POST['postalCode'];  // Assuming postalCode is unique and doesn't change
    $city = $_POST['city'];
    $province = $_POST['province'];
    $address = $_POST['address'];

    // Update the area in the database
    $sql = "UPDATE Area SET city='$city', province='$province', address='$address' WHERE postalCode='$postalCode'";

    if ($conn->query($sql) === TRUE) {
        header("Location: area.php");
        exit();
    } else {
        echo "Error updating area: " . $conn->error;
    }
} elseif (isset($_GET['postalCode'])) {
    $postalCode = $_GET['postalCode'];

    // Fetch the area details from the database
    $sql = "SELECT * FROM Area WHERE postalCode='$postalCode'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Area not found";
        exit();
    }
} else {
    echo "No area specified";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Area</title>
</head>
<body>
    <h1>Edit Area</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <input type="hidden" name="postalCode" value="<?php echo $row['postalCode']; ?>">
        City: <input type="text" name="city" value="<?php echo $row['city']; ?>" required><br>
        Province: <input type="text" name="province" value="<?php echo $row['province']; ?>" required><br>
        Address: <input type="text" name="address" value="<?php echo $row['address']; ?>" required><br>
        <input type="submit" value="Update">
    </form>
    <button onclick="window.location.href='area.php'">Back to Areas</button>
</body>
</html>
