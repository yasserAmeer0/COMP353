<?php
include '../config.php'; // Include your database connection settings

// Define the SQL query to fetch all necessary details for upcoming sessions
$sql = "
SELECT FamilyMembers.firstName AS familyFirstName, 
       FamilyMembers.lastName AS familyLastName, 
       FamilyMembers.emailAddress AS familyEmail, 
       Session.DateTime AS sessionDateTime, 
       Session.type AS sessionType,
       Area.address AS sessionAddress, 
       TeamFormation.coachFirstName, 
       TeamFormation.coachLastName, 
       TeamFormation.coachTelephoneNum, 
       Session.coachFirstName1,
       Session.coachLastName1,
       Session.coachTelephoneNum1,
       Session.coachFirstName2,
       Session.coachLastName2,
       Session.coachTelephoneNum2,
       Personnels.emailAddress AS coachEmail, 
       ClubMembers.firstName AS clubMemberFirstName, 
       ClubMembers.lastName AS clubMemberLastName, 
       TeamMembers.role AS clubMemberRole
FROM Session
INNER JOIN Location ON Location.postalCode = Session.locPostalCode AND Location.phoneNum = Session.locPhoneNum
INNER JOIN Area ON Area.postalCode = Location.postalCode
INNER JOIN TeamFormation ON Session.DateTime = TeamFormation.dateTime AND 
    ((Session.coachFirstName1 = TeamFormation.coachFirstName AND Session.coachLastName1 = TeamFormation.coachLastName AND Session.coachTelephoneNum1 = TeamFormation.coachTelephoneNum) OR
    (Session.coachFirstName2 = TeamFormation.coachFirstName AND Session.coachLastName2 = TeamFormation.coachLastName AND Session.coachTelephoneNum2 = TeamFormation.coachTelephoneNum))
INNER JOIN TeamMembers ON TeamMembers.dateTime = TeamFormation.dateTime AND TeamMembers.coachFirstName = TeamFormation.coachFirstName AND TeamMembers.coachLastName = TeamFormation.coachLastName AND TeamMembers.coachTelephoneNum = TeamFormation.coachTelephoneNum
INNER JOIN ClubMembers ON ClubMembers.membershipNum = TeamMembers.membershipNum
INNER JOIN Personnels ON Personnels.firstName = TeamFormation.coachFirstName AND Personnels.lastName = TeamFormation.coachLastName AND Personnels.telephoneNum = TeamFormation.coachTelephoneNum
INNER JOIN Relations ON Relations.membershipNum = ClubMembers.membershipNum
INNER JOIN FamilyMembers ON FamilyMembers.firstName = Relations.firstName AND FamilyMembers.lastName = Relations.lastName AND FamilyMembers.telephoneNum = Relations.telephoneNum
WHERE Relations.endDate IS NULL AND Relations.primFamFirstName IS NULL AND Relations.primFamLastName IS NULL AND Relations.primFamTelephoneNum IS NULL
AND WEEK(Session.DateTime) = WEEK('2024-06-03');
";

$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Prepare email content
        $subject = "Montreal Youth Group 6 " . $row['sessionDateTime'] . " " . $row['sessionType'];
        $subject = substr($subject, 0, 255);  // Ensure subject does not exceed 255 characters
        $body = "Dear " . $row['clubMemberFirstName'] . " " . $row['clubMemberLastName'] . ",\n" .
                "You are scheduled to play as a " . $row['clubMemberRole'] . " in the upcoming " . $row['sessionType'] . " session.\n" .
                "Coach: " . $row['coachFirstName'] . " " . $row['coachLastName'] . " (" . $row['coachEmail'] . ")\n" .
                "Session Address: " . $row['sessionAddress'] . "\n" .
                "Please be on time. Thank you!\n";

     

        // Set defaults for missing secondary coach details
        $coachFirstName2 = $row['coachFirstName2'] ?? '';
        $coachLastName2 = $row['coachLastName2'] ?? '';
        $coachTelephoneNum2 = $row['coachTelephoneNum2'] ?? '';

        
        // Check if the entry already exists
        $check_sql = "SELECT 1 FROM EmailLog WHERE dateTime = ? AND coachFirstName1 = ? AND coachLastName1 = ? AND coachTelephoneNum1 = ? AND coachFirstName2 = ? AND coachLastName2 = ? AND coachTelephoneNum2 = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("sssssss",
            $row['sessionDateTime'],
            $row['coachFirstName1'], $row['coachLastName1'], $row['coachTelephoneNum1'],
            $coachFirstName2, $coachLastName2, $coachTelephoneNum2
        );
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows == 0) {
            // Proceed with the insert
            $log_sql = "INSERT INTO EmailLog (date, dateTime, coachFirstName1, coachLastName1, coachTelephoneNum1, coachFirstName2, coachLastName2, coachTelephoneNum2, subject, body) 
                        VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            if ($log_stmt = $conn->prepare($log_sql)) {
                $log_stmt->bind_param("sssssssss", 
                    $row['sessionDateTime'], 
                    $row['coachFirstName1'], $row['coachLastName1'], $row['coachTelephoneNum1'],
                    $coachFirstName2, $coachLastName2, $coachTelephoneNum2, 
                    $subject, $body);
                if (!$log_stmt->execute()) {
                    echo "Error inserting into EmailLog: " . $log_stmt->error;
                }
                $log_stmt->close();
            }
        } 

        $check_stmt->close();
    }
} 

// Fetch email logs to display
$log_result = $conn->query("SELECT * FROM EmailLog ORDER BY date DESC, dateTime DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Log</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #5f9ea0;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #e9e9e9;
        }
        button {
            padding: 10px 15px;
            background-color: #5f9ea0;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #4a7a83;
        }
        .log {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <h1>Email Log</h1>
    <button onclick="window.location.href='http://localhost/YSCS/index.php'">Back to Main Menu</button>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>DateTime</th>
                <th>Coach 1</th>
                <th>Coach 2</th>
                <th>Subject</th>
                <th>Body Preview</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($log_result->num_rows > 0): ?>
                <?php while($log_row = $log_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($log_row['date']) ?></td>
                        <td><?= htmlspecialchars($log_row['dateTime']) ?></td>
                        <td><?= htmlspecialchars($log_row['coachFirstName1'] . ' ' . $log_row['coachLastName1'] . ' (' . $log_row['coachTelephoneNum1'] . ')') ?></td>
                        <td><?= htmlspecialchars($log_row['coachFirstName2'] . ' ' . $log_row['coachLastName2'] . ' (' . $log_row['coachTelephoneNum2'] . ')') ?></td>
                        <td><?= htmlspecialchars($log_row['subject']) ?></td>
                        <td><?= htmlspecialchars(substr($log_row['body'], 0, 100)) ?>...</td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6">No email logs found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="log">
        <!-- You can add additional logging information here if needed -->
         
        Log generated at <?= date('Y-m-d H:i:s') ?>
    </div>
    <?php $conn->close(); ?>
</body>
</html>
