<?php
include '../config.php'; // Make sure this includes your database connection settings

// Initialize variables with default values
$primFamFirstName = '';
$primFamLastName = '';
$primFamTelephoneNum = '';

// Check if form is submitted and sanitize input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $primFamFirstName = $conn->real_escape_string($_POST['primFamFirstName']);
    $primFamLastName = $conn->real_escape_string($_POST['primFamLastName']);
    $primFamTelephoneNum = $conn->real_escape_string($_POST['primFamTelephoneNum']);

    // Prepare SQL query with user inputs
    $sql = "SELECT ClubMembers.*, 
                   Relations.firstName AS secondaryFirstName, 
                   Relations.lastName AS secondaryLastName, 
                   Relations.telephoneNum AS secondaryTelephoneNum, 
                   Relations.relationship
            FROM Relations
            INNER JOIN ClubMembers 
                ON ClubMembers.membershipNum = Relations.membershipNum
            WHERE Relations.primFamFirstName = ?
              AND Relations.primFamLastName = ?
              AND Relations.primFamTelephoneNum = ?
              AND Relations.endDate IS NULL";
    
    // Prepare statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $primFamFirstName, $primFamLastName, $primFamTelephoneNum);
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
    <title>Filter Club Members</title>
    <link rel="stylesheet" href="../styles.css"> <!-- Link to CSS file -->
</head>
<body>
<div class="top">
    <h1>Filter Club Members</h1>
</div>

<!-- Form for user input -->
<div class="dateFilter">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="primFamFirstName">Primary Family Member's First Name:</label>
        <input type="text" id="primFamFirstName" name="primFamFirstName" value="<?php echo htmlspecialchars($primFamFirstName); ?>" required>
        <br><br>
        <label for="primFamLastName">Primary Family Member's Last Name:</label>
        <input type="text" id="primFamLastName" name="primFamLastName" value="<?php echo htmlspecialchars($primFamLastName); ?>" required>
        <br><br>
        <label for="primFamTelephoneNum">Primary Family Member's Telephone Number:</label>
        <input type="text" id="primFamTelephoneNum" name="primFamTelephoneNum" value="<?php echo htmlspecialchars($primFamTelephoneNum); ?>" required>
        <br><br>
        <input type="submit" value="Submit">
    </form>
</div>
<button onclick="window.location.href='http://localhost/YSCS/index.php'">Back to Main Menu</button>
<!-- Display results -->
<table>
    <thead>
        <tr>
            <th>Membership Number</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Telephone Number</th>
            <th>Gender</th>
            <th>Secondary First Name</th>
            <th>Secondary Last Name</th>
            <th>Secondary Telephone Number</th>
            <th>Relationship</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['membershipNum']) . "</td>";
                echo "<td>" . htmlspecialchars($row['firstName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['lastName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['telephoneNum']) . "</td>";
                echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                echo "<td>" . htmlspecialchars($row['secondaryFirstName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['secondaryLastName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['secondaryTelephoneNum']) . "</td>";
                echo "<td>" . htmlspecialchars($row['relationship']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='9'>No data found</td></tr>";
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
