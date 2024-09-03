<?php
include '../config.php'; // Ensure this file contains your database connection setup

// Prepare and execute the SQL query
$sql = "SELECT ClubMembers.membershipNum, ClubMembers.firstName, ClubMembers.lastName
FROM ClubMembers
INNER JOIN Relations ON Relations.membershipNum=ClubMembers.membershipNum
INNER JOIN FamilyMembers ON FamilyMembers.firstName=Relations.firstName AND FamilyMembers.lastName=Relations.lastName AND FamilyMembers.telephoneNum=Relations.telephoneNum
INNER JOIN Association ON Association.famFirstName=FamilyMembers.firstName AND Association.famLastName=FamilyMembers.lastName AND Association.famTelephoneNum=FamilyMembers.telephoneNum
WHERE Relations.endDate IS NULL AND 
(TIMESTAMPDIFF(YEAR, Relations.startDate, CURDATE())<=2)
GROUP BY Association.startDate
HAVING COUNT(Association.startDate)>=4
ORDER BY ClubMembers.membershipNum";

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
    <title>Club Members with Specific Criteria</title>
    <link rel="stylesheet" href="../styles.css"> <!-- Optional: add your CSS file -->
</head>
<body>
<div class="top">
    <h1>Club Members with Specific Criteria</h1>
</div>

<!-- Display results -->
<table>
    <thead>
        <tr>
            <th>Membership Number</th>
            <th>First Name</th>
            <th>Last Name</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['membershipNum']) . "</td>";
                echo "<td>" . htmlspecialchars($row['firstName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['lastName']) . "</td>";
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
