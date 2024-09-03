<?php
include '../config.php'; // Include your database connection settings

// Handling form submission for adding both an area and a location
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extracting form data
    $postalCode = $_POST['postalCode'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $address = $_POST['address'];
    $name = $_POST['name'];
    $phoneNum = $_POST['phoneNum'];
    $webAddress = $_POST['webAddress'];
    $type = $_POST['type'];
    $capacity = $_POST['capacity'];

    // Begin transaction to ensure data integrity
    $conn->begin_transaction();
    try {
        // Insert into Area
        $stmt = $conn->prepare("INSERT INTO Area (postalCode, city, province, address) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $postalCode, $city, $province, $address);
        $stmt->execute();
        $stmt->close();

        // Insert into Location
        $stmt = $conn->prepare("INSERT INTO Location (name, phoneNum, webAddress, type, capacity, postalCode) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssis", $name, $phoneNum, $webAddress, $type, $capacity, $postalCode);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();
        echo " location added successfully.";
    } catch (Exception $e) {
        // Rollback transaction in case of any error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Location </title>
</head>
<body>
    <h1>Add Location</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Postal Code: <input type="text" name="postalCode" required><br>
        City: <input type="text" name="city" required><br>
        Province: <input type="text" name="province" required><br>
        Address: <input type="text" name="address" required><br>
        Name: <input type="text" name="name" required><br>
        Phone Number: <input type="text" name="phoneNum" required><br>
        Web Address: <input type="text" name="webAddress" required><br>
        Type: <select name="type" required>
            <option value="Head">Head</option>
            <option value="Branch">Branch</option>
        </select><br>
        Capacity: <input type="number" name="capacity" required><br>
        <input type="submit" value="Add location ">
    </form>
</body>
<button onclick="window.location.href='location.php'">Back to location</button>
</html>
