<?php

    function validateDate($date) {
        $Date = explode('-', $date);
        return checkdate($Date[1], $Date[2], $Date[0]);
    }

    include('Session.php');
    $username = $_SESSION['username'];
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $placeNumber = $_POST['placeNumber'];
        $leaseDurationInSemesters = $_POST['leaseDurationInSemesters'];
        $leaseStart = $_POST['leaseStart'];
        $leaseEnd = $_POST['leaseEnd'];
        
        $valid = true;
        
        if(!empty($leaseStart) && validateDate($leaseStart)) {
            $message = "Please enter a validate start date in format YYYY-MM-DD";
            $valid=false;
        }
        
        if(!empty($leaseEnd) && validateDate($leaseEnd)) {
            $message = "Please enter a validate end date in format YYYY-MM-DD";
            $valid=false;
        }
        
        if(!empty($leaseDurationInSemesters)) {
            if (!is_int((int) $leaseDurationInSemesters)) {
                $message = "Please enter the duration in semesters 1/2/3(Summer)";
                $valid=false;
            }
        }
        
        if(!empty($placeNumber)) {
            if (!is_int((int) $placeNumber)) {
                $message = "Please enter the place number as an integer";
                $valid=false;
            }
        }
        
        if($valid==true) {
            
            if(empty($placeNumber) && empty($leaseStart) && empty($leaseEnd)) {
                $sqlLease = "INSERT INTO Lease (leaseNumber, leaseDurationInSemesters, leaseStart, leaseEnd, placeNumber) VALUES (NULL, '$leaseDurationInSemesters',NULL,NULL, NULL);";
            }
            else if(empty($placeNumber) && !empty($leaseStart) && empty($leaseEnd)) {
                $sqlLease = "INSERT INTO Lease (leaseNumber, leaseDurationInSemesters, leaseStart, leaseEnd, placeNumber) VALUES (NULL, '$leaseDurationInSemesters','$leaseStart',NULL, NULL);";
            }
            else if(empty($placeNumber) && empty($leaseStart) && !empty($leaseEnd)) {
                $sqlLease = "INSERT INTO Lease (leaseNumber, leaseDurationInSemesters, leaseStart, leaseEnd, placeNumber) VALUES (NULL, '$leaseDurationInSemesters',NULL,'$leaseEnd', NULL);";
            }
            else {
                $sqlLease = "INSERT INTO Lease (leaseNumber, leaseDurationInSemesters, leaseStart, leaseEnd, placeNumber) VALUES (NULL, '$leaseDurationInSemesters','$leaseStart','$leaseEnd','$placeNumber');";
            }
            
            if($valid==true) {
                if ($db->query($sqlLease)) {
                    $message = "A new lease has been created!";
                } 
                else {
                    $message = $db->error;
                }
            }
        }

        
    }
?>

<html>
   
    <head>
        <title>Dashboard</title>
        <link rel="stylesheet" type="text/css" href="stylesheet.css?<?php echo time(); ?>">
    </head>
    
    <body>
        <ul>
            <li><a id="link" class="active" href="Dashboard.php">Dashboard</a></li>
            <li id="link"  style="float:right"><a href="Logout.php">Logout</a></li>
            <li style="float:right"><a><?php if(isset($username)){echo $username;}?></a></li>
        </ul>
        <div class="updateResults">
            <form action = "" method = "post">
                <a><?php if(isset($message)){echo $message;}?></a>
                
                <p>Place Number</p>
                <input placeholder="Optional" class="resultField" type = "text" name = "placeNumber"/>
                
                <p>Duration (Semesters)</p>
                <input placeholder="Optional (1/2/3(Summer))" class="resultField" type = "text" name = "leaseDurationInSemesters"/>
                
                 <p>Start Date</p>
                <input placeholder="Optional" class="resultField" type = "text" name = "leaseStart"/>
                
                 <p>End Date</p>
                <input placeholder="Optional" class="resultField" type = "text" name = "leaseEnd"/>
                
                <hr>
                
                <input type="submit" name="action" value="Create Lease"/>
                
                <hr>
                
                <a href="CreateDashboard.php"><input class="backButton" type="button" value="Dashboard" /></a>
                
            </form>
        </div>
        
    </body>
</html>

