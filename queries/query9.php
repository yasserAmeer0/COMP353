<?php
include '../config.php'; // Ensure this file contains your database connection setup

// Initialize variables with default values
$locPostalCode = '';
$locPhoneNum = '';
$dateTime = '';

// Check if form is submitted and sanitize input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $locPostalCode = $conn->real_escape_string($_POST['locPostalCode']);
    $locPhoneNum = $conn->real_escape_string($_POST['locPhoneNum']);
    $dateTime = $conn->real_escape_string($_POST['dateTime']);

    // Prepare SQL query with user inputs
    $sql = "SELECT TeamFormation.coachFirstName, 
                   TeamFormation.coachLastName, 
                   CAST(TeamFormation.dateTime AS time) AS sessionTime, 
                   Area.address, 
                   Session.type, 
                   TeamFormation.teamName, 
                   TeamFormation.score, 
                   ClubMembers.firstName, 
                   ClubMembers.lastName, 
                   TeamMembers.role
            FROM TeamFormation
            INNER JOIN Session ON Session.DateTime = TeamFormation.dateTime 
                AND ((Session.coachFirstName1 = TeamFormation.coachFirstName 
                      AND Session.coachLastName1 = TeamFormation.coachLastName 
                      AND Session.coachTelephoneNum1 = TeamFormation.coachTelephoneNum) 
                     OR 
                     (Session.coachFirstName2 = TeamFormation.coachFirstName 
                      AND Session.coachLastName2 = TeamFormation.coachLastName 
                      AND Session.coachTelephoneNum2 = TeamFormation.coachTelephoneNum))
            INNER JOIN Location ON Location.postalCode = Session.locPostalCode 
                               AND Location.phoneNum = Session.locPhoneNum
            INNER JOIN Area ON Location.postalCode = Area.postalCode
            INNER JOIN TeamMembers ON TeamMembers.DateTime = TeamFormation.dateTime 
                                   AND TeamMembers.coachFirstName = TeamFormation.coachFirstName 
                                   AND TeamMembers.coachLastName = TeamFormation.coachLastName 
                                   AND TeamMembers.coachTelephoneNum = TeamFormation.coachTelephoneNum
            INNER JOIN ClubMembers ON TeamMembers.membershipNum = ClubMembers.membershipNum
            WHERE Session.locPostalCode = ?
              AND Session.locPhoneNum = ?
              AND DATE(Session.DateTime) = ?
            ORDER BY sessionTime";

    // Prepare statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $locPostalCode, $locPhoneNum, $dateTime);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Default empty result set
    $result = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter Team Sessions</title>
    <link rel="stylesheet" href="../styles.css"> <!-- Optional: add your CSS file -->
</head>
<body>
<div class="top">
    <h1>Filter Team Sessions</h1>
</div>
<button onclick="window.location.href='http://localhost/YSCS/index.php'">Back to Main Menu</button>
<div class="dateFilter">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="locPostalCode">Location Postal Code:</label>
        <input type="text" id="locPostalCode" name="locPostalCode" value="<?php echo htmlspecialchars($locPostalCode); ?>" required>
        <br><br>
        <label for="locPhoneNum">Location Phone Number:</label>
        <input type="text" id="locPhoneNum" name="locPhoneNum" value="<?php echo htmlspecialchars($locPhoneNum); ?>" required>
        <br><br>
        <label for="dateTime">Date (YYYY-MM-DD):</label>
        <input type="text" id="dateTime" name="dateTime" value="<?php echo htmlspecialchars($dateTime); ?>" required>
        <br><br>
        <input type="submit" value="Submit">
    </form>
</div>

<!-- Display results -->
<table>
    <thead>
        <tr>
            <th>Coach First Name</th>
            <th>Coach Last Name</th>
            <th>Session Time</th>
            <th>Address</th>
            <th>Session Type</th>
            <th>Team Name</th>
            <th>Score</th>
            <th>Member First Name</th>
            <th>Member Last Name</th>
            <th>Role</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['coachFirstName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['coachLastName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['sessionTime']) . "</td>";
                echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                echo "<td>" . htmlspecialchars($row['teamName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['score']) . "</td>";
                echo "<td>" . htmlspecialchars($row['firstName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['lastName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='10'>No data found</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php
// Close the statement and connection
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>
</body>
</html>
