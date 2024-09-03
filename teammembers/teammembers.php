<?php
include '../config.php';

// Fetch team members data from the database
$sql = "SELECT * FROM TeamMembers";
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
    <title>Manage Team Members</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="top">
        <h2>Manage Team Members</h2>
    </div>
    <div class="add-button">
        <button onclick="window.location.href='add_teammember.php'">Add New Team Member</button>
    </div>
    <table>
        <thead>
            <tr>
                <th>Date-Time</th>
                <th>Coach First Name</th>
                <th>Coach Last Name</th>
                <th>Coach Telephone Number</th>
                <th>Membership Number</th>
                <th>Role</th>
                <th id="actions">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['DateTime']) ?></td>
                    <td><?= htmlspecialchars($row['coachFirstName']) ?></td>
                    <td><?= htmlspecialchars($row['coachLastName']) ?></td>
                    <td><?= htmlspecialchars($row['coachTelephoneNum']) ?></td>
                    <td><?= htmlspecialchars($row['membershipNum']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td class="action-buttons">
                        <a class="edit-btn" href="edit_teammember.php?membershipNum=<?= urlencode($row['membershipNum']) ?>">Edit</a>
                        <a class="delete-btn" href="delete_teammember.php?membershipNum=<?= urlencode($row['membershipNum']) ?>" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <button onclick="window.location.href='http://localhost/YSCS/index.php'">Back to Main Menu</button>
</body>
</html>

