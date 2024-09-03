<?php
include '../config.php';

// Default date range values
$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : '2024-06-01';
$endDate = isset($_POST['endDate']) ? $_POST['endDate'] : '2024-08-31';

// Fetch data from the database using the user-provided dates
$sql = "SELECT
            L.name AS locationName,
            COUNT(DISTINCT S.DateTime) AS totalTrainingSessions,
            COUNT(DISTINCT CASE WHEN S.type = 'Training' THEN TM.membershipNum END) AS totalPlayersTrainingSessions,
            COUNT(DISTINCT CASE WHEN S.type = 'Game' THEN S.DateTime END) AS totalGameSessions,
            COUNT(DISTINCT CASE WHEN S.type = 'Game' THEN TM.membershipNum END) AS totalPlayersGameSessions
        FROM
            Location L
        JOIN
            Session S ON L.postalCode = S.locPostalCode
        LEFT JOIN
            TeamFormation TF ON S.DateTime = TF.dateTime AND L.postalCode = TF.locPostalCode
        LEFT JOIN
            TeamMembers TM ON TF.dateTime = TM.DateTime 
                            AND TF.coachFirstName = TM.coachFirstName 
                            AND TF.coachLastName = TM.coachLastName 
                            AND TF.coachTelephoneNum = TM.coachTelephoneNum
        WHERE
            S.DateTime BETWEEN '$startDate' AND '$endDate'
        GROUP BY
            L.name
        HAVING
            totalGameSessions >= 0
        ORDER BY
            totalGameSessions DESC";

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
    <title>Manage Locations</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<div class="top">
    <h1>Manage Locations</h1>
</div>

<!-- Form for date range input -->
<div class="dateFilter">
    <form method="post" action="">
        <label for="startDate">Start Date:</label>
        <input type="date" id="startDate" name="startDate" value="<?php echo htmlspecialchars($startDate); ?>">
        <label for="endDate">End Date:</label>
        <input type="date" id="endDate" name="endDate" value="<?php echo htmlspecialchars($endDate); ?>">
        <button type="submit">Filter</button>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>Location Name</th>
            <th>Total Training Sessions</th>
            <th>Total Players Training Sessions</th>
            <th>Total Game Sessions</th>
            <th>Total Players Game Sessions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['locationName'] . "</td>";
                echo "<td>" . $row['totalTrainingSessions'] . "</td>";
                echo "<td>" . $row['totalPlayersTrainingSessions'] . "</td>";
                echo "<td>" . $row['totalGameSessions'] . "</td>";
                echo "<td>" . $row['totalPlayersGameSessions'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No data found</td></tr>";
        }
        $conn->close();
        ?>
    </tbody>
</table>
</body>
</html>

