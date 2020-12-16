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
                <li><a href="SearchDashboard.php">Search Tool</a></li>
                <li><a href="ReportDashboard.php">Report Tool</a></li>
                <li><a href="CreateDashboard.php">Create Tool</a></li>
                <li><a href="ChangePass.php">Change Password</a></li>
            </ul>
        
        </div>
    </body>
</html>

