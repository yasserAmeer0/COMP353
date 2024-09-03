<?php
include '../config.php';

// Fetch all personnel records from the database
$sql = "SELECT * FROM Personnels";
$result = $conn->query($sql);

if ($result === FALSE) {
    die("Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Personnel</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h1>Manage Personnel</h1>
    <button onclick="window.location.href='add_personnel.php'">Add Personnel</button>
    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone Number</th>
                <th>Email Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $encodedFirstName = urlencode($row['firstName']);
                    $encodedLastName = urlencode($row['lastName']);
                    $encodedTelephoneNum = urlencode($row['telephoneNum']); // Encode the telephone number for URL
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['firstName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['lastName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['telephoneNum']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['emailAddress']) . "</td>";
                    echo "<td>";
                    echo "<a href='edit_personnel.php?firstName={$encodedFirstName}&lastName={$encodedLastName}&telephoneNum={$encodedTelephoneNum}'>Edit</a> | ";
                    echo "<a href='delete_personnel.php?firstName={$encodedFirstName}&lastName={$encodedLastName}&telephoneNum={$encodedTelephoneNum}' onclick=\"return confirm('Are you sure you want to delete this personnel?');\">Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No personnel found</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
    <button onclick="window.location.href='http://localhost/YSCS/index.php'">Back to Main Menu</button>
</body>
</html>
