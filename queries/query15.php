<?php
include '../config.php'; // Make sure this includes your database connection settings

// Prepare and execute the SQL query
$sql = "SELECT FamilyMembers.firstName, 
               FamilyMembers.lastName, 
               FamilyMembers.telephoneNum, 
               TeamFormation.locPostalCode 
        FROM FamilyMembers
        INNER JOIN TeamFormation 
            ON FamilyMembers.firstName = TeamFormation.coachFirstName 
            AND FamilyMembers.lastName = TeamFormation.coachLastName
        INNER JOIN Association 
            ON FamilyMembers.firstName = Association.famFirstName 
            AND FamilyMembers.lastName = Association.famLastName 
            AND TeamFormation.locPostalCode = Association.locPostalCode
        GROUP BY FamilyMembers.telephoneNum
        HAVING COUNT(FamilyMembers.telephoneNum) >= 1";

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
    <title>Family Members and Team Formation</title>
    <link rel="stylesheet" href="../styles.css"> <!-- Link to CSS file -->
</head>
<body>
<div class="top">
    <h1>Family Members and Team Formation</h1>
</div>

<!-- Display results -->
<table>
    <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Telephone Number</th>
            <th>Location Postal Code</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['firstName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['lastName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['telephoneNum']) . "</td>";
                echo "<td>" . htmlspecialchars($row['locPostalCode']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No data found</td></tr>";
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