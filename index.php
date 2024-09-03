<?php
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YSCS</title>
    <link rel="stylesheet" href="indextt.css">
</head>
<body>
    <div class="top">
        <h1 class="welcomeMessage">Welcome to the Youth Soccer Club System (YSCS)</h1>
    </div>
    <div class="menu-container">
        <div class="menu">
            <h2>Menu</h2>
            <ul>
                <!-- <li><button onclick="window.location.href='area/area.php'">Manage Area</button></li> -->
               
                
                <li><button onclick="window.location.href='personnel/personnel.php'">Manage Personnels</button></li>
                <li><button onclick="window.location.href='clubmembers/clubmembers.php'">Manage Club Members</button></li>
                <li><button onclick="window.location.href='familymembers/familymembers.php'">Manage Family Members</button></li>
                <li><button onclick="window.location.href='location/location.php'">Manage Locations</button></li>
                <li><button onclick="window.location.href='emaillog/emaillog.php'">Manage Email Log</button></li>
                </ul>

                <h2>Extra Table Display </h2>
                <ul>
                <li><button onclick="window.location.href='association/association.php'">familymembers association</button></li>
                <li><button onclick="window.location.href='operationlength/operationlength.php'">Personel operationlength</button></li>
                
                
                <!-- <li><button onclick="window.location.href='individual/individual.php'">Manage Individual</button></li> -->
                <li><button onclick="window.location.href='relations/relations.php'">Family Relation</button></li>
                </ul>

                <h2>To be Completed</h2>
                <ul>
                <li><button onclick="window.location.href='session/session.php'">Manage Session</button></li>
                <li><button onclick="window.location.href='teamformation/teamformation.php'">Manage Team Formation</button></li>
                <li><button onclick="window.location.href='teammembers/teammembers.php'">Manage Team Members</button></li>
            
        </div>
        <div class="common-operations">
            <h2>Common Operations (QUERIES)</h2>
            <ul>
                <li><button onclick="window.location.href='queries/query7.php'">Location Details (Query7)</button></li>
                <li><button onclick="window.location.href='queries/query8.php'">Filter Club Members (Query8)</button></li>
                <li><button onclick="window.location.href='queries/query9.php'">Filter Team Sessions (Query9)</button></li>
                <li><button onclick="window.location.href='queries/query10.php'">Club Members with Specific Criteria (Query10)</button></li>
                <li><button onclick="window.location.href='queries/query11.php'">Game Sessions (Query11)</button></li>
                <li><button onclick="window.location.href='queries/query12.php'">Report of Active Club Members (Query12)</button></li>
                <li><button onclick="window.location.href='queries/query13.php'">Report of Goalkeeper Assignments (Query13)</button></li>
                <li><button onclick="window.location.href='queries/query14.php'">Report of Members Assigned to Every Role (Query14)</button></li>
                <li><button onclick="window.location.href='queries/query15.php'">Family Members (Query15)</button></li>
                <li><button onclick="window.location.href='queries/query16.php'">Winners (Query16)</button></li>
                <li><button onclick="window.location.href='queries/query17.php'">Administrator Operation Length (Query17)</button></li>
                <li><button onclick="window.location.href='queries/query18.php'">Volunteer Personnels (Query18)</button></li>
            </ul>
        </div>
    </div>
</body>
</html>
