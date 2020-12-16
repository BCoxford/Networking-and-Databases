<?php

    function validateDate($date) {
        $Date = explode('-', $date);
        return checkdate($Date[1], $Date[2], $Date[0]);
    }

    include('Session.php');
    $username = $_SESSION['username'];
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $date = $_POST['date'];
        $duration = $_POST['duration'];
        
        $valid = true;
        
        $sql = "SELECT placeNumber FROM Room;";
        
        $result = mysqli_query($db,$sql) or die(mysqli_error($db));

        $count = mysqli_num_rows($result);

        if($count >= 1) {

            $placeNumbers=[];
            
            while ($row = mysqli_fetch_row($result)) {
                $placeNumbers[] = $row[0];
            }
            
            if(empty($date) || !validateDate($date)) {
                $message = "Please enter a validate start date in format YYYY-MM-DD";
                $valid=false;
            }
            
            $numberInspectionsPerDay = 0;
            $dates = [];

            if(!empty($duration)) {
                if (!is_int((int) $duration)) {
                    $message = "Please enter the duration in days as an integer";
                    $valid=false;
                }
                else {
                    for ($i=0; $i<=$duration-1; $i++) {
                        $dates[] = date("Y-m-d", strtotime($date . ' + ' . $i . ' days'));
                    }
                }
            }
            else {
                $message = "Please enter the duration in days as an integer";
                $valid=false;
            }

            if($valid==true) {
                $errors = array();
                $numberInspectionsPerDay = round($count / $duration);
                $db->autocommit(FALSE);
                
                if($count%$duration == 0) {
                    for ($i=0; $i<=$duration-1; $i++) {
                        for ($z=($numberInspectionsPerDay*$i); $z<(($numberInspectionsPerDay*$i)+$numberInspectionsPerDay); $z++) {
                            $sql = "INSERT INTO Inspection (inspectionID, date, placeNumber) VALUES (NULL, '$dates[$i]', '$placeNumbers[$z]');";
                            if(!$db->query($sql)) {
                                $errors[] = $db->error;
                            }
                        }
                    }
                }
                else {
                    
                    $numberInspectionsPerDay = $count/$duration;
                    $numberInspectionsPerDay = (int) $numberInspectionsPerDay; 
                    $remainder = $count%$duration;
                    
                    $z=0;
                    for ($i=0; $i<=$duration-1; $i++) {
                        for ($z=($numberInspectionsPerDay*$i); $z<(($numberInspectionsPerDay*$i)+$numberInspectionsPerDay); $z++) {
                            $sql = "INSERT INTO Inspection (inspectionID, date, placeNumber) VALUES (NULL, '$dates[$i]', '$placeNumbers[$z]');";
                            if(!$db->query($sql)) {
                                $errors[] = $db->error;
                            }
                        }
                    }
                    
                    for ($i=$z; $i<=$z+$remainder-1; $i++) {
                        $date = $i-$z;
                        $sql = "INSERT INTO Inspection (inspectionID, date, placeNumber) VALUES (NULL, '$dates[$date]', '$placeNumbers[$i]');";
                        if(!$db->query($sql)) {
                            $errors[] = $db->error;
                        }
                    }
                }
                
                if (count($errors) === 0) {
                    $db->commit();
                    $date2 = date("Y-m-d", strtotime($date . ' + ' . $duration . ' days'));
                    $message = "Inspections for all dates have been created. Produce an inspection report between '$date' and '$date2'";
                } 
                else {
                    $db->rollback();
                    foreach($errors as $e) {
                        $message = $message . "Error: " . $e . "<br>";    
                    }
                }
                $db->autocommit(TRUE);  
            }
        }
        else {
           $message = "An error has occurred"; 
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
                
                <p>Start Date</p>
                <input placeholder="Required" class="resultField" type = "text" name = "date"/>
                
                <p>Duration (In Days)</p>
                <input placeholder="Required (Max 7 days)" class="resultField" type = "text" name = "duration"/>
                
                <hr>
                
                <input type="submit" name="action" value="Create Inspection Records"/>
                
                <hr>
                
                <a href="CreateDashboard.php"><input class="backButton" type="button" value="Dashboard" /></a>
                
            </form>
        </div>
        
    </body>
</html>

