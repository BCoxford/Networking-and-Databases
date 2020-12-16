<?php
    include('Session.php');
    $username = $_SESSION['username'];

    $date = date("Y-m-d");

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $sql = "SELECT a.inspectionID, b.placeNumber, b.apartmentID, b.hallID, a.date FROM Inspection a INNER JOIN Room b ON a.placeNumber=b.placeNumber WHERE conditionSatisfactory IS NULL";
      
        if(isset($sql)) {
            $result = mysqli_query($db,$sql) or die(mysqli_error($db));

            $count = mysqli_num_rows($result);

            if($count >= 1) {
                $numResults = $count . " results";
                $results = "<table><tr><th>Inspection Id</th><th>Date</th><th>Place Number</th><th>Hall ID</th><th>Apartment ID</th></tr>";
                
                while($row = mysqli_fetch_array($result)) {
                    
                    $apartmentID=$row["apartmentID"];
                    $hallID=$row["hallID"];
                
                    if(empty($apartmentID)) {
                        $apartmentID= "N/A";
                    }

                    if(empty($hallID)) {
                        $hallID= "N/A";
                    }
                    
                    $results = $results . "<tr><th>".$row["inspectionID"]."</th><th>".$row["date"]."</th><th>".$row["placeNumber"]."</th><th>".$hallID."</th><th>".$apartmentID."</th></tr>";
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
                <input class="searchField" type = "submit" value = "Produce Planned Inspection Report"/>
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

