<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postalCode = $_POST['postalCode'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $address = $_POST['address'];

    // Insert new area into the database
    $sql = "INSERT INTO Area (postalCode, city, province, address)
            VALUES ('$postalCode', '$city', '$province', '$address')";

if ($conn->query($sql) === TRUE) {
    // Redirect back to the specified location path if provided
    if (isset($_GET['redirect'])) {
        $redirectUrl = $_GET['redirect'];
        
        // Check if the redirect URL already contains the base directory
        if (strpos($redirectUrl, '/YSCS/location/') === false) {
            $redirectUrl = '/YSCS/location/' . ltrim($redirectUrl, '/');
        }
        
        // header("Location: " . $redirectUrl);
        exit();
    } else {
        header("Location: /YSCS/area/area.php");
        exit();
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Area</title>
</head>
<body>
    <h1>Add New Area</h1>
    <?php
    if (isset($_GET['message'])) {
        echo '<p style="color: red;">' . htmlspecialchars($_GET['message']) . '</p>';
    }
    ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . '?' . $_SERVER['QUERY_STRING']); ?>">
        Postal Code: <input type="text" name="postalCode" required><br>
        City: <input type="text" name="city" required><br>
        Province: <input type="text" name="province" required><br>
        Address: <input type="text" name="address" required><br>
        <input type="submit" value="Add Area">
    </form>
</body>
</html>
