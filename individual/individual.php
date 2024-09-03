<?php
include '../config.php';

// Fetch individual data from the database
$sql = "SELECT * FROM Individual";
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
    <title>Manage Individuals</title>
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
    <h1>Manage Individuals</h1>
    <button onclick="window.location.href='add_individual.php'">Add New Individual</button>
    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Telephone Number</th>
                <th>Date of Birth</th>
                <th>Medicare Number</th>
                <th>SSN</th>
                <th>Postal Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['firstName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['lastName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['telephoneNum']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['dateofBirth']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['medicareNum']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['SSN']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['postalCode']) . "</td>";
                    echo "<td><a href='edit_individual.php?SSN=" . urlencode($row['SSN']) . "'>Edit</a> | <a href='delete_individual.php?SSN=" . urlencode($row['SSN']) . "'>Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No individuals found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
