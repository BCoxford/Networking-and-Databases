<?php
    include('Session.php');
    $username = $_SESSION['username'];

    if($_SERVER["REQUEST_METHOD"] == "POST") {
      
        $apartmentID = $_POST['apartmentID'];
        $hallID = $_POST['hallID'];
        $all = $_POST['all'];
        
        if(isset($all) && !empty($all)) {
            //If All
            $sql = "SELECT placeNumber, roomNumber, floorNumber, monthlyRate, hallID, apartmentID FROM Room WHERE placeNumber NOT IN (SELECT a.placeNumber FROM Lease a)";
        }
        else if(!empty($apartmentID) && empty($hallID)) {
            //If Apartment Id
            $sql = "SELECT placeNumber, roomNumber, floorNumber, monthlyRate, hallID, apartmentID FROM Room WHERE placeNumber NOT IN (SELECT a.placeNumber FROM Lease a) AND apartmentID='$apartmentID'";
        }
        else if(!empty($hallID) && empty($apartmentID)) {
            //If Hall Id
            $sql = "SELECT placeNumber, roomNumber, floorNumber, monthlyRate, hallID, apartmentID FROM Room WHERE placeNumber NOT IN (SELECT a.placeNumber FROM Lease a) AND hallID='$hallID'";
        }
        else if(!empty($hallID) && !empty($apartmentID)) {
            //If Hall Id
            $sql = "SELECT placeNumber, roomNumber, floorNumber, monthlyRate, hallID, apartmentID FROM Room WHERE placeNumber NOT IN (SELECT a.placeNumber FROM Lease a) AND (hallID='$hallID' OR apartmentID='$apartmentID')";
        }
        else {
            $numResults = "You can produce a report on all rooms or those beloning to an apartment and/or hall";
        }
      
        if(isset($sql)) {
            $result = mysqli_query($db,$sql) or die(mysqli_error($db));

            $count = mysqli_num_rows($result);

            if($count >= 1) {
                $numResults = $count . " results";
                $results = "<table><tr><th>Place Number</th><th>Room Number</th><th>Floor Number</th><th>Monthly Rate</th><th>Hall Id</th><th>Apartment Id</th></tr>";
                
                while($row = mysqli_fetch_array($result)) {
                    $apartmentID=$row["apartmentID"];
                    $hallID=$row["hallID"];
                
                    if(empty($apartmentID)) {
                        $apartmentID= "N/A";
                    }

                    if(empty($hallID)) {
                        $hallID= "N/A";
                    }
                    
                    $results = $results . "<tr><th>".$row["placeNumber"]."</th><th>".$row["roomNumber"]."</th><th>".$row["floorNumber"]."</th><th>".$row["monthlyRate"]."</th><th>".$hallID."</th><th>".$apartmentID."</th></tr>";
                }
                $results = $results . "</table>";
                $button = "<input class='print' type='button' value='Print' onclick='window.print()'>";
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
        <div class="noPrint">
        <ul>
            <li><a id="link" class="active" href="Dashboard.php">Dashboard</a></li>
            <li id="link"  style="float:right"><a href="Logout.php">Logout</a></li>
            <li style="float:right"><a><?php if(isset($username)){ echo $username; } ?></a></li>
        </ul> 
        </div>
        <div class="form">
            
            <div class="noPrint">
            <form class="centerBoxes" action = "" method = "post">
                <br/>
                <input class="searchField" placeholder="Apartment Id" type = "text" name = "apartmentID"/>
                <input class="searchField" placeholder="Hall Id" type = "text" name = "hallID"/>
                <p style="color:white; margin-left:5px; margin-right:5px;">Tick to search for all vancant rooms</p>
                <input type="checkbox" name="all" value="Yes"/>
                <input class="searchField" type = "submit" value = "Produce Vacancy Report"/>
                <br/>
            </form>
            </div>
            
            <p><?php if(isset($numResults)){echo $numResults;}?></p>
            
            <div id="results">
            
                <?php if(isset($results)){echo $results; }?>
            
            </div>
            
            <div class="noPrint">
            
            <?php if(isset($button)){echo $button; }?>
            
            </div>
        </div>
    </body>
</html>

