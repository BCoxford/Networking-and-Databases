<?php
    include('Session.php');
    $username = $_SESSION['username'];
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $roomNumber = $_POST['roomNumber'];
        $floorNumber = $_POST['floorNumber'];
        $monthlyRate = $_POST['monthlyRate'];
        $apartmentID = $_POST['apartmentID'];
        $hallID = $_POST['hallID'];
        
        $valid = true;
        
        if(!empty($apartmentID)) {
            if (!is_int((int) $apartmentID)) {
                $message = "Please enter the apartment ID as an integer";
                $valid=false;
            }
        }
        
        if(!empty($hallID)) {
            if (!is_int((int) $hallID)) {
                $message = "Please enter the hall ID as an integer";
                $valid=false;
            }
        }
        
        if(!empty($hallID) && !empty($apartmentID)) {
            $message = "Please enter either an apartment ID or a hall ID";
            $valid=false;
        }
        
        if(!empty($monthlyRate)) {
            if (!is_integer((int) $monthlyRate)) {
                $message = "Please enter the monthly rate as an integer";
                $valid=false;
            }
        }
        else {
            $message = "Please enter the monthly rate as an integer";
            $valid=false;
        }

        if(empty($roomNumber)) {
            $message= "Please enter a room number";
            $valid=false;
        }
        
        if($valid==true) {
            
            $sqlRoom = "INSERT INTO Room (placeNumber, roomNumber, floorNumber, monthlyRate, apartmentID, hallID) VALUES (NULL, '$roomNumber', '$floorNumber', '$monthlyRate', NULL, NULL);";
            
            if(!empty($hallID)) {
                $sqlRoom = "INSERT INTO Room (placeNumber, roomNumber, floorNumber, monthlyRate, apartmentID, hallID) VALUES (NULL, '$roomNumber', '$floorNumber', '$monthlyRate', NULL, '$hallID');";
            }
            
            if(!empty($apartmentID)) {
                $sqlRoom = "INSERT INTO Room (placeNumber, roomNumber, floorNumber, monthlyRate, apartmentID, hallID) VALUES (NULL, '$roomNumber', '$floorNumber', '$monthlyRate', '$apartmentID', NULL);";
            }
            
            if($valid==true) {
                if ($db->query($sqlRoom)) {
                    $message = "A new room has been created!";
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
                
                <p>Room Number</p>
                <input placeholder="Required" class="resultField" type = "text" name = "roomNumber"/>
                
                <p>Floor Number</p>
                <input placeholder="Optional" class="resultField" type = "text" name = "floorNumber"/>
                
                <p>Monthly Rate</p>
                <input placeholder="Required" class="resultField" type = "text" name = "monthlyRate"/>
                
                <p>Apartment ID</p>
                <input placeholder="Optional" class="resultField" type = "text" name = "apartmentID"/>
                
                <p>Hall ID</p>
                <input placeholder="Optional" class="resultField" type = "text" name = "hallID"/>
                
                <hr>
                
                <input type="submit" name="action" value="Create Room"/>
                
                <hr>
                
                <a href="CreateDashboard.php"><input class="backButton" type="button" value="Dashboard" /></a>
                
            </form>
        </div>
        
    </body>
</html>

