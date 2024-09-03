<?php
include '../config.php';

// Fetch operation details from the database
$sql = "SELECT * FROM OperationLength";
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
    <title>Manage Operation Lengths</title>
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
    <h1>Manage Operation Lengths</h1>
    <button onclick="window.location.href='add_operationlength.php'">Add New Operation Length</button>
    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Telephone Number</th>
                <th>Postal Code</th>
                <th>Location Phone Number</th>
                <th>Role</th>
                <th>Mandate</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['personnelFirstName']) ?></td>
                    <td><?= htmlspecialchars($row['personnelLastName']) ?></td>
                    <td><?= htmlspecialchars($row['personnelTelephoneNum']) ?></td>
                    <td><?= htmlspecialchars($row['locPostalCode']) ?></td>
                    <td><?= htmlspecialchars($row['locPhoneNum']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td><?= htmlspecialchars($row['mandate']) ?></td>
                    <td><?= htmlspecialchars($row['startDate']) ?></td>
                    <td><?= htmlspecialchars($row['endDate']) ?></td>
                    <td>
                        <a href="edit_operationlength.php?personnelTelephoneNum=<?= urlencode($row['personnelTelephoneNum']) ?>">Edit</a> |
                        <a href="delete_operationlength.php?personnelTelephoneNum=<?= urlencode($row['personnelTelephoneNum']) ?>" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <button onclick="window.location.href='http://localhost/YSCS/index.php'">Back to Main Menu</button>
</body>
</html>
