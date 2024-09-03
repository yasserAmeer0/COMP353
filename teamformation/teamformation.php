<?php
include '../config.php';

// Fetch data from the TeamFormation table
$sql = "SELECT * FROM TeamFormation";
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
    <title>Manage Team Formations</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="top">
        <h2>Manage Team Formations</h2>
    </div>
    <div class="add-button">
        <button onclick="window.location.href='add_teamformation.php'">Add New Team Formation</button>
    </div>
    <table>
        <thead>
            <tr>
                <th>Date-Time</th>
                <th>Team Name</th>
                <th>Coach First Name</th>
                <th>Coach Last Name</th>
                <th>Coach Telephone Number</th>
                <th>Location Postal Code</th>
                <th>Location Phone Number</th>
                <th>Score</th>
                <th>Gender</th>
                <th id="actions">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['dateTime']) ?></td>
                    <td><?= htmlspecialchars($row['teamName']) ?></td>
                    <td><?= htmlspecialchars($row['coachFirstName']) ?></td>
                    <td><?= htmlspecialchars($row['coachLastName']) ?></td>
                    <td><?= htmlspecialchars($row['coachTelephoneNum']) ?></td>
                    <td><?= htmlspecialchars($row['locPostalCode']) ?></td>
                    <td><?= htmlspecialchars($row['locPhoneNum']) ?></td>
                    <td><?= htmlspecialchars($row['score']) ?></td>
                    <td><?= htmlspecialchars($row['gender']) ?></td>
                    <td class='action-buttons'>
                        <a class='edit-btn' href="edit_teamformation.php?dateTime=<?= urlencode($row['dateTime']) ?>&teamName=<?= urlencode($row['teamName']) ?>">Edit</a>
                        <a class='delete-btn' href="delete_teamformation.php?dateTime=<?= urlencode($row['dateTime']) ?>&teamName=<?= urlencode($row['teamName']) ?>" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <button onclick="window.location.href='http://localhost/YSCS/index.php'">Back to Main Menu</button>
</body>
</html>

