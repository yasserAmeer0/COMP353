<?php
include '../config.php';

// Fetch club members from the database
$sql = "SELECT * FROM ClubMembers";
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
    <title>Manage Club Members</title>
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
    <h1>Manage Club Members</h1>
    <button onclick="window.location.href='add_clubmember.php'">Add New Club Member</button>
    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Telephone Number</th>
                <th>Membership Number</th>
                <th>Gender</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $encodedFirstName = urlencode($row['firstName']);
                    $encodedLastName = urlencode($row['lastName']);
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['firstName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['lastName']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['telephoneNum']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['membershipNum']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                    echo "<td><a href='edit_clubmember.php?membershipNum={$row['membershipNum']}'>Edit</a> | 
                    <a href='delete_clubmember.php?membershipNum={$row['membershipNum']}' onclick=\"return confirm('Are you sure?');\">Delete</a></td>";              
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No club members found</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <button onclick="window.location.href='http://localhost/YSCS/index.php'">Back to Main Menu</button>
</body>
</html>
