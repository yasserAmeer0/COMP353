<?php
include '../config.php';

// SQL query to fetch the required information
$sql = "
    SELECT 
        tm.membershipNum, 
        cm.firstName, 
        cm.lastName, 
        TIMESTAMPDIFF(YEAR, i.dateOfBirth, CURDATE()) AS age, 
        cm.telephoneNum, 
        l.name AS locationName
    FROM (
        SELECT 
            s1.DateTime as DateTime, 
            s1.locPostalCode as PostCode, 
            s1.teamName as TeamName, 
            s1.score as winnersScore 
        FROM 
            TeamFormation s1
        INNER JOIN 
            TeamFormation s2 ON s1.DateTime = s2.DateTime AND s1.score > s2.score
    ) as winners
    INNER JOIN 
        TeamMembers tm ON winners.DateTime = tm.DateTime
    INNER JOIN 
        ClubMembers cm ON tm.membershipNum = cm.membershipNum
    INNER JOIN 
        Individual i ON cm.firstName = i.firstName AND cm.lastName = i.lastName
    INNER JOIN 
        Location l ON winners.PostCode = l.postalCode
    GROUP BY 
        tm.membershipNum
    ORDER BY 
        tm.membershipNum;
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
    <title>Winners</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<div class="top">
    <h1>Winners</h1>
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
