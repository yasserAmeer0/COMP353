<?php
include '../config.php';

// Fetch areas from the database
$sql = "SELECT * FROM Area";
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
    <title>Manage Areas</title>
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
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Manage Areas</h1>
    <?php
    if (isset($_GET['error'])) {
        echo '<p class="error">' . htmlspecialchars($_GET['error']) . '</p>';
    }
    ?>
    <button onclick="window.location.href='add_area.php'">Add New Area</button>
    <table>
        <thead>
            <tr>
                <th>Postal Code</th>
                <th>City</th>
                <th>Province</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['postalCode']); ?></td>
                    <td><?php echo htmlspecialchars($row['city']); ?></td>
                    <td><?php echo htmlspecialchars($row['province']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td>
                        <a href="edit_area.php?postalCode=<?php echo urlencode($row['postalCode']); ?>">Edit</a>
                        <a href="delete_area.php?postalCode=<?php echo urlencode($row['postalCode']); ?>" onclick="return confirm('Are you sure you want to delete this area?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
