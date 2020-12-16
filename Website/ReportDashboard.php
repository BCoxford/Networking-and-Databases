<?php
    include('Session.php');
    $username = $_SESSION['username'];
?>
<html>
   
    <head>
        <title>Dashboard</title>
        <link rel="stylesheet" type="text/css" href="stylesheet.css">
    </head>
    
    <body>
        <ul>
            <li><a id="link" class="active" href="Dashboard.php">Dashboard</a></li>
            <li id="link"  style="float:right"><a href="Logout.php">Logout</a></li>
            <li style="float:right"><a><?php echo $username;?></a></li>
        </ul> 
        <div class="dashboard">
            
            <ul class="navBar">
                <li><a href="RoomVacancyReport.php">Room Vacancy Report</a></li>
                <li><a href="WaitingReport.php">Waiting Student's Report</a></li>
                <li><a href="OverdueInvoiceReport.php">Unpaid Invoice Report</a></li>
                <li><a href="InspectionReport.php">Inspection Damage Report</a></li>
            </ul>
            
            <ul class="navBar">
                <li><a href="PlannedInspectionReport.php">Planned Inspection Report</a></li>
                <li><a href="OccupancyReport.php">Occupancy Report</a></li>
                <li><a href="IncomeReport.php">Income Report</a></li>
                <li><a href="UnknownLeavingReport.php">Unknown Leaving Date Report</a></li>
            </ul>
            
            <ul class="navBar">
                <li><a href="Dashboard.php">Back</a></li>
            </ul>
        
        </div>
    </body>
</html>

