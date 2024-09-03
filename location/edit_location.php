<?php
include '../config.php';

function getPostalCodes($conn) {
    $postalCodes = [];
    $sql = "SELECT postalCode FROM Area";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $postalCodes[] = $row['postalCode'];
    }
    return $postalCodes;
}

$postalCodes = getPostalCodes($conn);

// Retrieve existing details
if (isset($_GET['phoneNum'])) {
    $phoneNum = $conn->real_escape_string($_GET['phoneNum']);
    $sql = "SELECT * FROM Location WHERE phoneNum = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phoneNum);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $stmt->close();
    } else {
        echo "Location not found.";
        exit();
    }
} else {
    echo "No location selected for editing.";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phoneNum = $_POST['phoneNum']; // Assume phone number remains the same for simplicity
    $name = $_POST['name'];
    $webAddress = $_POST['webAddress'];
    $type = $_POST['type'];
    $capacity = $_POST['capacity'];
    $newPostalCode = $_POST['postalCode'];

    // Check if the new postal code exists in Area
    if (!in_array($newPostalCode, $postalCodes)) {
        echo "Error: Postal code does not exist in Area table.";
    } else {
        $conn->begin_transaction();
        try {
            $sql = "UPDATE Location SET name=?, webAddress=?, type=?, capacity=?, postalCode=? WHERE phoneNum=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssiis", $name, $webAddress, $type, $capacity, $newPostalCode, $phoneNum);
            $stmt->execute();
            $stmt->close();

            $conn->commit();
            echo "Location updated successfully.";
        } catch (Exception $e) {
            $conn->rollback();
            echo "Error updating location: " . $e->getMessage();
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Location</title>
</head>
<body>
    <h1>Edit Location</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?phoneNum=' . urlencode($phoneNum); ?>" method="post">
        Postal Code: <select name="postalCode">
            <?php foreach ($postalCodes as $code) {
                echo "<option value='$code'" . ($code == $row['postalCode'] ? " selected" : "") . ">$code</option>";
            } ?>
        </select><br>
        Name: <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required><br>
        Phone Number: <input type="text" name="phoneNum" value="<?php echo htmlspecialchars($row['phoneNum']); ?>" readonly><br>
        Web Address: <input type="text" name="webAddress" value="<?php echo htmlspecialchars($row['webAddress']); ?>"><br>
        Type: <select name="type">
            <option value="Head" <?= $row['type'] == 'Head' ? 'selected' : ''; ?>>Head</option>
            <option value="Branch" <?= $row['type'] == 'Branch' ? 'selected' : ''; ?>>Branch</option>
        </select><br>
        Capacity: <input type="number" name="capacity" value="<?php echo htmlspecialchars($row['capacity']); ?>"><br>
        <input type="submit" value="Update">
    </form>
    <button onclick="window.location.href='location.php'">Back to Locations</button>
</body>
</html>
