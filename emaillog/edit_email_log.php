<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emailDate = $_POST['emailDate'];
    $sender = $_POST['sender'];
    $receiver = $_POST['receiver'];
    $first100Char = $_POST['first100Char'];

    // Update the email log in the database
    $sql = "UPDATE EmailLog SET First100Char='$first100Char' WHERE EmailDate='$emailDate' AND Sender='$sender' AND Receiver='$receiver'";

    if ($conn->query($sql) === TRUE) {
        header("Location: email_log.php");
        exit();
    } else {
        echo "Error updating email log: " . $conn->error;
    }
} elseif (isset($_GET['EmailDate']) && isset($_GET['Sender']) && isset($_GET['Receiver'])) {
    $emailDate = $_GET['EmailDate'];
    $sender = $_GET['Sender'];
    $receiver = $_GET['Receiver'];

    // Fetch the email log details from the database
    $sql = "SELECT * FROM EmailLog WHERE EmailDate='$emailDate' AND Sender='$sender' AND Receiver='$receiver'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Email log not found";
        exit();
    }
} else {
    echo "No email log specified";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Email Log</title>
</head>
<body>
    <h1>Edit Email Log</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <input type="hidden" name="emailDate" value="<?php echo $row['EmailDate']; ?>">
        <input type="hidden" name="sender" value="<?php echo $row['Sender']; ?>">
        <input type="hidden" name="receiver" value="<?php echo $row['Receiver']; ?>">
        First 100 Characters: <input type="text" name="first100Char" value="<?php echo $row['First100Char']; ?>" required><br>
        <input type="submit" value="Update">
    </form>
    <button onclick="window.location.href='email_log.php'">Back to Email Logs</button>
</body>
</html>
