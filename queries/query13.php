<?php
include '../config.php';

// SQL query to fetch the required information
$sql = "
    SELECT DISTINCT 
        cm.membershipNum, 
        cm.firstName, 
        cm.lastName, 
        TIMESTAMPDIFF(YEAR, i.dateOfBirth, CURDATE()) AS age, 
        i.telephoneNum, 
        l.name AS locationName
    FROM 
        ClubMembers cm
    INNER JOIN 
        TeamMembers tm ON tm.membershipNum = cm.membershipNum
    INNER JOIN 
        Individual i ON i.firstName = cm.firstName 
                      AND i.lastName = cm.lastName 
                      AND i.telephoneNum = cm.telephoneNum
    INNER JOIN 
        Relations r ON r.membershipNum = cm.membershipNum
    INNER JOIN 
        FamilyMembers fm ON fm.firstName = r.firstName 
                          AND fm.lastName = r.lastName 
                          AND fm.telephoneNum = r.telephoneNum
    INNER JOIN 
        Association a ON a.famFirstName = fm.firstName 
                      AND a.famLastName = fm.lastName 
                      AND a.famTelephoneNum = fm.telephoneNum
    INNER JOIN 
        Location l ON a.locPostalCode = l.postalCode 
                   AND a.locPhoneNum = l.phoneNum
    WHERE 
        r.endDate IS NULL 
        AND r.primFamFirstName IS NULL 
        AND r.primFamLastName IS NULL 
        AND r.primFamTelephoneNum IS NULL
        AND cm.membershipNum = ALL (
            SELECT tm2.membershipNum
            FROM TeamMembers tm2
            WHERE tm2.role = 'Goalkeeper' 
            AND tm2.membershipNum NOT IN (
                SELECT tm3.membershipNum
                FROM TeamMembers tm3
                WHERE NOT (tm3.role = 'Goalkeeper')
            )
        )
    ORDER BY 
        l.name, cm.membershipNum;
";

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
    <title>Goalkeepers Only</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<div class="top">
    <h1>Goalkeepers Only</h1>
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
                echo "<td>" . htmlspecialchars($row['membershipNum']) . "</td>";
                echo "<td>" . htmlspecialchars($row['firstName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['lastName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['age']) . "</td>";
                echo "<td>" . htmlspecialchars($row['telephoneNum']) . "</td>";
                echo "<td>" . htmlspecialchars($row['locationName']) . "</td>";
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