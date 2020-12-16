<?php

    function validateDate($date) {
        $Date = explode('-', $date);
        return checkdate($Date[1], $Date[2], $Date[0]);
    }

    include('Session.php');
    $username = $_SESSION['username'];

    if($_SERVER["REQUEST_METHOD"] == "POST") {
      
        $leaseNumber = $_POST['leaseNumber'];
        $leaseStart = $_POST['leaseStart'];
        $leaseEnd = $_POST['leaseEnd'];
        $leaseDurationInSemesters = $_POST['leaseDurationInSemesters'];

        if (!empty($leaseStart) && !empty($leaseEnd) && validateDate($leaseStart) && validateDate($leaseEnd)) {
            $sql="SELECT DISTINCT leaseNumber, leaseDurationInSemesters, leaseStart, leaseEnd, placeNumber FROM Lease WHERE leaseNumber='$leaseNumber' OR leaseDurationInSemesters='$leaseDurationInSemesters' OR (leaseStart BETWEEN '$leaseStart' AND '$leaseEnd' OR leaseEnd BETWEEN '$leaseStart' AND '$leaseEnd')";
        }
        else {
            $sql="SELECT DISTINCT leaseNumber, leaseDurationInSemesters, leaseStart, leaseEnd, placeNumber FROM Lease WHERE leaseNumber='$leaseNumber' OR leaseDurationInSemesters='$leaseDurationInSemesters'";
        }
      
        if(isset($sql)) {
            $result = mysqli_query($db,$sql) or die(mysqli_error($db));

            $count = mysqli_num_rows($result);

            if($count >= 1) {
                $numResults = $count . " results";
                $results = "<table><tr><th>Lease Number</th><th>Duration (Semesters)</th><th>Start Date</th><th>End Date</th><th>Place Number</th><th>More Details</th></tr>";
                while($row = mysqli_fetch_array($result)) {
                    $results = $results . "<tr><th>".$row["leaseNumber"]."</th><th>".$row["leaseDurationInSemesters"]."</th><th>".$row["leaseStart"]."</th><th>".$row["leaseEnd"]."</th><th>".$row["placeNumber"]."</th><th><form action='LeaseView.php' method='post'><input style='margin-top:15px;' type='submit' value='View'/><input type='hidden' name='search' value='".$row["leaseNumber"]."'</input></form></th></tr>";
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
                <input class="searchField" placeholder="Lease Number" type = "text" name = "leaseNumber"/>
                <input class="searchField" placeholder="Duration (Semesters)" type = "text" name = "leaseDurationInSemesters"/>
                <input class="searchField" placeholder="Start Date (YYYY-MM-DD)" type = "text" name = "leaseStart"/>
                <input class="searchField"placeholder="End Date (YYYY-MM-DD)" type = "text" name = "leaseEnd"/>
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

