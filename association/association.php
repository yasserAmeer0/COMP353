<?php
include '../config.php';

// Fetch association data from the database
$sql = "SELECT * FROM Association";
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
    <title>Manage Associations</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h1>Manage Associations</h1>
    <button onclick="window.location.href='add_association.php'">Add New Association</button>
    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Telephone Number</th>
                <th>Postal Code</th>
                <th>Location Phone Number</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['famFirstName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['famLastName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['famTelephoneNum']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['locPostalCode']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['locPhoneNum']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['startDate']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['endDate']) . "</td>";
                    echo "<td><a href='edit_association.php?id=" . urlencode($row['famTelephoneNum']) . "'>Edit</a> | <a href='delete_association.php?id=" . urlencode($row['famTelephoneNum']) . "'>Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No associations found</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <button onclick="window.location.href='http://localhost/YSCS/index.php'">Back to Main Menu</button>
</body>
</html>
