<?php
include '../config.php';

// Fetch relations data from the database
$sql = "SELECT * FROM Relations";
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
    <title>Manage Relations</title>
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
    <h1>Manage Relations</h1>
    <button onclick="window.location.href='add_relation.php'">Add New Relation</button>
    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Telephone Number</th>
                <th>Membership Number</th>
                <th>Relationship</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Primary First Name</th>
                <th>Primary Last Name</th>
                <th>Primary Phone Number</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['firstName']) ?></td>
                    <td><?= htmlspecialchars($row['lastName']) ?></td>
                    <td><?= htmlspecialchars($row['telephoneNum']) ?></td>
                    <td><?= htmlspecialchars($row['membershipNum']) ?></td>
                    <td><?= htmlspecialchars($row['relationship']) ?></td>
                    <td><?= htmlspecialchars($row['startDate']) ?></td>
                    <td><?= htmlspecialchars($row['endDate']) ?></td>
                    <td><?= htmlspecialchars($row['primFamFirstName']) ?></td>
                    <td><?= htmlspecialchars($row['primFamLastName']) ?></td>
                    <td><?= htmlspecialchars($row['primFamTelephoneNum']) ?></td>
                    <td>
                        <a href="edit_relation.php?membershipNum=<?= urlencode($row['membershipNum']) ?>">Edit</a> |
                        <a href="delete_relation.php?membershipNum=<?= urlencode($row['membershipNum']) ?>" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <button onclick="window.location.href='http://localhost/YSCS/index.php'">Back to Main Menu</button>
</body>
</html>
