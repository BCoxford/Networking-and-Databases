<?php
    include('Session.php');
    $username = $_SESSION['username'];

    if($_SERVER["REQUEST_METHOD"] == "POST") {
      
        $placeNumber = $_POST['placeNumber'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        
        $byName=false;
        
        if(!empty($placeNumber)) {
            $placeNumber = mysqli_real_escape_string($db, $placeNumber); 
            
            $sql = "IF EXISTS (SELECT 1 FROM Room a INNER JOIN Lease b ON a.placeNumber=b.placeNumber INNER JOIN Student c ON c.leaseNumber=b.placeNumber INNER JOIN GeneralDetails d ON d.detailsID=c.detailsID WHERE a.placeNumber='$placeNumber')
            THEN 
            SELECT a.placeNumber, a.roomNumber, a.floorNumber, a.monthlyRate, d.firstname, d.lastname FROM Room a INNER JOIN Lease b ON a.placeNumber=b.placeNumber INNER JOIN Student c ON c.leaseNumber=b.placeNumber INNER JOIN GeneralDetails d ON d.detailsID=c.detailsID WHERE a.placeNumber='$placeNumber' LIMIT 1;
            ELSE
            SELECT placeNumber, roomNumber, floorNumber, monthlyRate FROM Room WHERE placeNumber='$placeNumber' LIMIT 1;
            END IF;";
        }
        else if(!empty($firstname) && !empty($lastname)) {
            $placeNumber = mysqli_real_escape_string($db, $placeNumber);
            $sql = "SELECT d.firstname, d.lastname, a.placeNumber, a.roomNumber, a.floorNumber, a.monthlyRate FROM Room a INNER JOIN Lease b ON a.placeNumber=b.placeNumber INNER JOIN Student c ON c.leaseNumber=b.placeNumber INNER JOIN GeneralDetails d ON d.detailsID=c.detailsID WHERE d.firstname='$firstname' AND d.lastname='$lastname' LIMIT 1";
        }
        else {
            $numResults = "You can search by a place number or by the occupants full name!";
        }
      
        if(isset($sql)) {
            $result = mysqli_query($db,$sql) or die(mysqli_error($db));

            $count = mysqli_num_rows($result);

            if($count >= 1) {
                $numResults = $count . " results";
                $row = mysqli_fetch_assoc($result);
                
                if(isset($row['firstname'])) {
                    $results = "<table><tr><th>Place Number</th><th>Room Number</th><th>Floor Number</th><th>Monthly Rate</th><th>Occupant</th><th>More Details</th></tr><tr><th>".$row["placeNumber"]."</th><th>".$row["roomNumber"]."</th><th>".$row["floorNumber"]."</th><th>£".$row["monthlyRate"]."</th><th>".ucfirst($row["firstname"])." ".ucfirst($row['lastname'])."</th><th><form action='RoomView.php' method='post'><input style='margin-top:15px;' type='submit' value='View'/><input type='hidden' name='search' value='".$row["placeNumber"]."'</input></form></th></tr></table>";
                }
                else {
                    $results = "<table><tr><th>Place Number</th><th>Room Number</th><th>Floor Number</th><th>Monthly Rate</th><th>More Details</th></tr><tr><th>".$row["placeNumber"]."</th><th>".$row["roomNumber"]."</th><th>".$row["floorNumber"]."</th><th>£".$row["monthlyRate"]."</th><th><form action='RoomView.php' method='post'><input style='margin-top:15px;' type='submit' value='View'/><input type='hidden' name='search' value='".$row["placeNumber"]."'</input></form></th></tr></table>";
                }
            }
            else {
                $numResults = "0 results";
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
            <li style="float:right"><a><?php if(isset($username)){ echo $username; } ?></a></li>
        </ul> 
        <div class="form">
            
            <form class="centerBoxes" action = "" method = "post">
                <br/>
                <input class="searchField" placeholder="Place number" type = "text" name = "placeNumber"/>
                <input class="searchField" placeholder="First name" type = "text" name = "firstname"/>
                <input class="searchField" placeholder="Last name" type = "text" name = "lastname"/>
                <input class="searchField" type = "submit" value = "Search"/>
                <br/>
            </form>
            
            <p><?php if(isset($numResults)){echo $numResults;}?></p>
            
            <div id="results">
            
                <?php if(isset($results)){echo $results; }?>
            
            </div>
        
        </div>
    </body>
</html>

