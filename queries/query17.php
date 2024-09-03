<?php
include '../config.php'; // Ensure this file contains your database connection setup

// Prepare and execute the SQL query
$sql = "SELECT OperationLength.personnelFirstName, 
               OperationLength.personnelLastName, 
               MAX(OperationLength.endDate) AS maxEndDate
        FROM OperationLength
        INNER JOIN Location ON Location.postalCode = OperationLength.locPostalCode 
                             AND Location.phoneNum = OperationLength.locPhoneNum
        WHERE OperationLength.role = 'Administrator' 
          AND Location.type = 'Head'
        GROUP BY OperationLength.personnelFirstName, 
                 OperationLength.personnelLastName
        ORDER BY OperationLength.personnelFirstName, 
                 OperationLength.personnelLastName, 
                 OperationLength.startDate";

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
    <title>Administrator Operation Length</title>
    <link rel="stylesheet" href="../styles.css"> <!-- Optional: add your CSS file -->
</head>
<body>
<div class="top">
    <h1>Administrator Operation Length</h1>
</div>

<!-- Display results -->
<table>
    <thead>
        <tr>
            <th>Personnel First Name</th>
            <th>Personnel Last Name</th>
            <th>Max End Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['personnelFirstName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['personnelLastName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['maxEndDate']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No data found</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php
// Close the connection
$conn->close();
?>
</body>
</html>
