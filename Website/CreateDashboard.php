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
                <li><a href="CreateLease.php">New Lease</a></li>
                <li><a href="CreateInspection.php">New Inspection Log</a></li>
                <li><a href="CreateStaff.php">New Staff</a></li>
                <li><a href="CreateApartment.php">New Apartment</a></li>
            </ul>
            
            <ul class="navBar">
                <li><a href="CreateHallOfRes.php">New Hall of Residents</a></li>
                <li><a href="CreateUniversity.php">New University</a></li>
                <li><a href="CreateCourse.php">New Course</a></li>
                <li><a href="CreateRoom.php">New New Room</a></li>
            </ul>
            
            <ul class="navBar">
                <li><a href="CreateInvoice.php">New Invoice</a></li>
                <li><a href="CreateAdviser.php">New Adviser</a></li>
                <li><a href="Dashboard.php">Back</a></li>
            </ul>
        
        </div>
    </body>
</html>

