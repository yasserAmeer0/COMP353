<?php
include '../config.php';

// Fetch locations from the database
$sql = "SELECT * FROM Location";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Locations</title>
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
    <h1>Manage Locations</h1>
    <button onclick="window.location.href='add_location.php'">Add Location</button>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Web Address</th>
                <th>Type</th>
                <th>Capacity</th>
                <th>Postal Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['phoneNum'] . "</td>";
                    echo "<td>" . $row['webAddress'] . "</td>";
                    echo "<td>" . $row['type'] . "</td>";
                    echo "<td>" . $row['capacity'] . "</td>";
                    echo "<td>" . $row['postalCode'] . "</td>";
                    echo "<td>";
                    echo "<a href='edit_location.php?phoneNum=" . urlencode($row['phoneNum']) . "'>Edit</a> | ";
                    echo "<a href='delete_location.php?phoneNum=" . urlencode($row['phoneNum']) . "'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No locations found</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
    <button onclick="window.location.href='http://localhost/YSCS/index.php'">Back to Main Menu</button>
</body>
</html>
