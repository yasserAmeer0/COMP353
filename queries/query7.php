<?php
include '../config.php';

// Fetch data from the database
$sql = "SELECT 
            Area.address, 
            Area.city, 
            Area.province, 
            Area.postalCode, 
            Location.phoneNum, 
            Location.webAddress, 
            Location.type, 
            Location.capacity, 
            manager.generalManager, 
            members.memberAmount
        FROM 
            Location
        INNER JOIN 
            Area ON Area.postalCode = Location.postalCode
        INNER JOIN (
            SELECT 
                Location.postalCode, 
                Location.phoneNum, 
                CONCAT_WS(' ', firstName, lastName) AS generalManager
            FROM 
                Personnels
            INNER JOIN 
                OperationLength ON OperationLength.personnelFirstName = Personnels.firstName 
                               AND OperationLength.personnelLastName = Personnels.lastName 
                               AND OperationLength.personnelTelephoneNum = Personnels.telephoneNum
            INNER JOIN 
                Location ON Location.postalCode = OperationLength.locPostalCode 
                         AND Location.phoneNum = OperationLength.locPhoneNum
            WHERE 
                OperationLength.endDate IS NULL 
                AND OperationLength.role = 'Administrator'
        ) AS manager ON manager.postalCode = Location.postalCode 
                    AND manager.phoneNum = Location.phoneNum
        INNER JOIN (
            SELECT 
                Association.locPostalCode, 
                Association.locPhoneNum, 
                COUNT(ClubMembers.membershipNum) AS memberAmount 
            FROM 
                ClubMembers 
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
            WHERE 
                Relations.endDate IS NULL 
                AND Relations.primFamFirstName IS NULL 
                AND Relations.primFamLastName IS NULL 
                AND Relations.primFamTelephoneNum IS NULL
                AND Association.endDate IS NULL
                AND Association.locPostalCode = locPostalCode 
                AND Association.locPhoneNum = locPhoneNum
            GROUP BY 
                Association.locPostalCode, Association.locPhoneNum
        ) AS members ON members.locPostalCode = Location.postalCode 
                    AND members.locPhoneNum = Location.phoneNum";

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
    <title>Location Details</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<div class="top">
    <h1>Location Details</h1>
</div>
<button onclick="window.location.href='http://localhost/YSCS/index.php'">Back to Main Menu</button>
<table>
    <thead>
        <tr>
            <th>Address</th>
            <th>City</th>
            <th>Province</th>
            <th>Postal Code</th>
            <th>Phone Number</th>
            <th>Web Address</th>
            <th>Type</th>
            <th>Capacity</th>
            <th>General Manager</th>
            <th>Member Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['address'] . "</td>";
                echo "<td>" . $row['city'] . "</td>";
                echo "<td>" . $row['province'] . "</td>";
                echo "<td>" . $row['postalCode'] . "</td>";
                echo "<td>" . $row['phoneNum'] . "</td>"; // Closed double quote
                echo "<td>" . $row['webAddress'] . "</td>";
                echo "<td>" . $row['type'] . "</td>";
                echo "<td>" . $row['capacity'] . "</td>";
                echo "<td>" . $row['generalManager'] . "</td>";
                echo "<td>" . $row['memberAmount'] . "</td>";
                echo "</tr>";
            }
        }