<?php

    function validateDate($date) {
        $Date = explode('-', $date);
        return checkdate($Date[1], $Date[2], $Date[0]);
    }

    include('Session.php');
    $username = $_SESSION['username'];

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $date = $_POST['date'];
        $conditionSatisfactory = $_POST['conditionSatisfactory'];
        $placeNumber = $_POST['placeNumber'];
        $format=false;
        
        if(!empty($conditionSatisfactory)) {
            $conditionSatisfactory="OR conditionSatisfactory IS NULL";
            $placeNumber="";
            $date="";
            $format=true;
        }
        else {
            $conditionSatisfactory="";
        }
        
        $valid=true;
        
        if(!empty($date) && !validateDate($date)) {
            $valid=false;
            $numResults = "Enter a valid date (YYYY-MM-DD)";
        }
        
        $sql="SELECT DISTINCT inspectionID, date, conditionSatisfactory, additionalComments, staffID, placeNumber FROM Inspection WHERE date='$date' OR placeNumber='$placeNumber' ". $conditionSatisfactory;
      
        if(isset($sql) && $format==false && $valid) {
            $result = mysqli_query($db,$sql) or die(mysqli_error($db));

            $count = mysqli_num_rows($result);

            if($count >= 1) {
                $yes="No";
                if($row["conditionSatisfactory"] == "1") {
                    $yes="Yes";
                }
                $numResults = $count . " results";
                $results = "<table><tr><th>Inspection Id</th><th>Date</th><th>Condition Satisfactory</th><th>Additional Comments</th><th>Staff Id</th><th>Place Number</th></tr>";
                while($row = mysqli_fetch_array($result)) {
                    $results = $results . "<tr><th>".$row["inspectionID"]."</th><th>".$row["date"]."</th><th>".$yes."</th><th>".$row["additionalComments"]."</th><th>".$row["staffID"]."</th><th>".$row["placeNumber"]."</th></tr>";
                }
                $results = $results . "</table>";
            }
            else {
                $numResults = "0 results";
            }
        }
        else if(isset($sql) && $format==true) {
            $result = mysqli_query($db,$sql) or die(mysqli_error($db));

            $count = mysqli_num_rows($result);

            if($count >= 1) {
                $numResults = $count . " results";
                $results = "<table><tr><th>Inspection Id</th><th>Date</th><th>Place Number</th><th>Update Log</th></tr>";
                while($row = mysqli_fetch_array($result)) {
                    $results = $results . "<tr><th>".$row["inspectionID"]."</th><th>".$row["date"]."</th><th>".$row["placeNumber"]."</th><th><form action='InspectionView.php' method='post'><input style='margin-top:15px;' type='submit' value='Update'/><input type='hidden' name='search' value='".$row["inspectionID"]."'</input></form></th></tr>";
                }
                $results = $results . "</table>";
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
                <input class="searchField" placeholder="Place Number" type = "text" name = "placeNumber"/>
                <input class="searchField" placeholder="Date (YYYY-MM-DD)" type = "text" name = "date"/>
                <p style="color:white; margin-left:5px; margin-right:5px;">Tick to search for uncompleted logs</p><input type="checkbox" name="conditionSatisfactory" value="Yes"/>
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

