<?php
include '../config.php'; // Ensure this file contains your database connection setup

// Prepare and execute the SQL query
$sql = "SELECT OperationLength.personnelFirstName, 
               OperationLength.personnelLastName, 
               OperationLength.personnelTelephoneNum
        FROM OperationLength
        INNER JOIN Personnels 
            ON Personnels.firstName = OperationLength.personnelFirstName 
            AND Personnels.lastName = OperationLength.personnelLastName 
            AND Personnels.telephoneNum = OperationLength.personnelTelephoneNum
        WHERE OperationLength.endDate IS NULL 
          AND OperationLength.mandate = 'Volunteer'
          AND Personnels.firstName NOT IN (SELECT firstName FROM FamilyMembers)
          AND Personnels.lastName NOT IN (SELECT lastName FROM FamilyMembers)";

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
    <title>Volunteer Personnels</title>
    <link rel="stylesheet" href="../styles.css"> <!-- Optional: add your CSS file -->
</head>
<body>
<div class="top">
    <h1>Volunteer Personnels</h1>
</div>

<!-- Display results -->
<table>
    <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Telephone Number</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['personnelFirstName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['personnelLastName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['personnelTelephoneNum']) . "</td>";
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
