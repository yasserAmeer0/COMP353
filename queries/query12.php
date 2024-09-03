<?php
include '../config.php';

// Fetch data from the database
$sql = "SELECT 
            ClubMembers.membershipNum, 
            ClubMembers.firstName, 
            ClubMembers.lastName, 
            TIMESTAMPDIFF(YEAR, Individual.dateOfBirth, CURDATE()) AS age, 
            Individual.telephoneNum, 
            Location.name
        FROM 
            ClubMembers
        INNER JOIN 
            Individual ON Individual.firstName = ClubMembers.firstName 
                       AND Individual.lastName = ClubMembers.lastName 
                       AND Individual.telephoneNum = ClubMembers.telephoneNum
        INNER JOIN 
            Relations ON Relations.membershipNum = ClubMembers.membershipNum
        INNER JOIN 
            FamilyMembers ON FamilyMembers.firstName = Relations.firstName 
                          AND FamilyMembers.lastName = Relations.lastName 
                          AND FamilyMembers.telephoneNum = Relations.telephoneNum
        INNER JOIN 
            Association ON Association.famFirstName = FamilyMembers.firstName 
                        AND Association.famLastName = FamilyMembers.lastName 
                        AND Association.famTelephoneNum = FamilyMembers.telephoneNum
        INNER JOIN 
            Location ON Location.postalCode = Association.locPostalCode 
                     AND Location.phoneNum = Association.locPhoneNum
        WHERE 
            NOT EXISTS (SELECT 1 FROM TeamMembers WHERE TeamMembers.membershipNum = ClubMembers.membershipNum) 
            AND Relations.endDate IS NULL
        ORDER BY 
            Location.name, ClubMembers.membershipNum";

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
    <title>Unassigned Club Members</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<div class="top">
    <h1>Unassigned Club Members</h1>
</div>

<table>
    <thead>
        <tr>
            <th>Membership Number</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Age</th>
            <th>Telephone Number</th>
            <th>Location Name</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['membershipNum'] . "</td>";
                echo "<td>" . $row['firstName'] . "</td>";
                echo "<td>" . $row['lastName'] . "</td>";
                echo "<td>" . $row['age'] . "</td>";
                echo "<td>" . $row['telephoneNum'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No data found</td></tr>";
        }
        $conn->close();
        ?>
    </tbody>
</table>
</body>
</html>