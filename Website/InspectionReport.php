<?php
    include('Session.php');
    $username = $_SESSION['username'];

    function validateDate($date) {
        $Date = explode('-', $date);
        return checkdate($Date[1], $Date[2], $Date[0]);
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
      
        $dateTo = $_POST['dateTo'];
        $dateFrom = $_POST['dateFrom'];
        
        if(!empty($dateTo) && !empty($dateFrom) && validateDate($dateTo) && validateDate($dateFrom)) {
            $sql = "SELECT a.inspectionID, a.date, a.additionalComments, a.staffID, a.placeNumber FROM Inspection a WHERE a.date < '$dateTo' AND a.date > '$dateFrom' AND a.conditionSatisfactory=0";
        }
        else {
            $numResults = "You can produce a report on all unsatisfactory inspection rooms using the date to and date from fields";
        }
      
        if(isset($sql)) {
            $result = mysqli_query($db,$sql) or die(mysqli_error($db));

            $count = mysqli_num_rows($result);

            if($count >= 1) {
                $numResults = $count . " results";
                $results = "<table><tr><th>Inspection Id</th><th>Date</th><th>Additional Comments</th><th>Staff Id</th><th>Place Number</th></tr>";
                
                while($row = mysqli_fetch_array($result)) {
                    $results = $results . "<tr><th>".$row["inspectionID"]."</th><th>".$row["date"]."</th><th>".$row["additionalComments"]."</th><th>".$row["staffID"]."</th><th>".$row["placeNumber"]."</th></tr>";
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
                <input class="searchField" placeholder="Date From (YYYY-MM-DD)" type = "text" name = "dateFrom"/>
                <input class="searchField" placeholder="Date To (YYYY-MM-DD)" type = "text" name = "dateTo"/>
                <input class="searchField" type = "submit" value = "Produce Inspection Damage Report"/>
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

