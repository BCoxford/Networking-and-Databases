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
                <li><a href="PeopleSearch.php">Person Search</a></li>
                <li><a href="ApartmentSearch.php">Apartment Search</a></li>
                <li><a href="HORSearch.php">Hall of Resident Search</a></li>
                <li><a href="RoomSearch.php">Room Search</a></li>
            </ul>
            
            <ul class="navBar">
                <li><a href="UniversitySearch.php">University Search</a></li>
                <li><a href="LeaseSearch.php">Lease Search</a></li>
                <li><a href="InvoiceSearch.php">Invoice Search</a></li>
                <li><a href="InspectionSearch.php">Inspection Log Search</a></li>
            </ul>
            
            <ul class="navBar">
                <li><a href="CourseSearch.php">Course Search</a></li>
                <li><a href="Dashboard.php">Back</a></li>
            </ul>
        
        </div>
    </body>
</html>

