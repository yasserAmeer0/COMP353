<?php
include '../config.php';

// Fetch session data from the database
$sql = "SELECT * FROM Session";
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
    <title>Manage Sessions</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="top">
        <h2>Manage Sessions</h2>
    </div>
    <div class="add-button">
        <button onclick="window.location.href='add_session.php'">Add New Session</button>
    </div>
    <table>
        <thead>
            <tr>
                <th>Date-Time</th>
                <th>Coach First Name</th>
                <th>Coach Last Name</th>
                <th>Coach Telephone Number</th>
                <th>Coach First Name 2</th>
                <th>Coach Last Name 2</th>
                <th>Coach Telephone Number 2</th>
                <th>Type</th>
                <th>Location Postal Code</th>
                <th>Location Phone Number</th>
                <th id="actions">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['DateTime']) ?></td>
                    <td><?= htmlspecialchars($row['coachFirstName1']) ?></td>
                    <td><?= htmlspecialchars($row['coachLastName1']) ?></td>
                    <td><?= htmlspecialchars($row['coachTelephoneNum1']) ?></td>
                    <td><?= htmlspecialchars($row['coachFirstName2']) ?></td>
                    <td><?= htmlspecialchars($row['coachLastName2']) ?></td>
                    <td><?= htmlspecialchars($row['coachTelephoneNum2']) ?></td>
                    <td><?= htmlspecialchars($row['type']) ?></td>
                    <td><?= htmlspecialchars($row['locPostalCode']) ?></td>
                    <td><?= htmlspecialchars($row['locPhoneNum']) ?></td>
                    <td class="action-buttons">
                        <a class="edit-btn" href="edit_session.php?DateTime=<?= urlencode($row['DateTime']) ?>">Edit</a>
                        <a class="delete-btn" href="delete_session.php?DateTime=<?= urlencode($row['DateTime']) ?>" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <button onclick="window.location.href='http://localhost/YSCS/index.php'">Back to Main Menu</button>
</body>
</html>

