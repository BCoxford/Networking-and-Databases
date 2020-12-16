<?php
    include('Session.php');
    $username = $_SESSION['username'];

    if($_SERVER["REQUEST_METHOD"] == "POST") {
      
        $universityID = $_POST['universityID'];
        $buildingNumber = $_POST['buildingNumber'];
        $firstLine = $_POST['firstLine'];
        $postcode = $_POST['postcode'];

        $sql = "SELECT b.universityID, c.buildingNumber, c.firstLine, c.secondLine, c.postcode FROM University b INNER JOIN Address c ON b.addressID=c.addressID WHERE b.universityID='$universityID' OR c.buildingNumber='$buildingNumber' OR postcode='$postcode' OR firstLine='$firstLine'";
      
        if(isset($sql)) {
            $result = mysqli_query($db,$sql) or die(mysqli_error($db));

            $count = mysqli_num_rows($result);

            if($count >= 1) {
                $numResults = $count . " results";
                $results = "<table><tr><th>University Id</th><th>Building Number</th><th>First Line</th><th>Second Line</th><th>Postcode</th><th>More Details</th></tr>";
                while($row = mysqli_fetch_array($result)) {
                    $results = $results . "<tr><th>".$row["universityID"]."</th><th>".$row["buildingNumber"]."</th><th>".$row["firstLine"]."</th><th>".$row["secondLine"]."</th><th>".$row["postcode"]."</th><th><form action='UniversityView.php' method='post'><input style='margin-top:15px;' type='submit' value='View'/><input type='hidden' name='search' value='".$row["universityID"]."'</input></form></th></tr>";
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
                <input class="searchField" placeholder="University Id" type = "text" name = "universityID"/>
                <input class="searchField" placeholder="Building Number" type = "text" name = "buildingNumber"/>
                <input class="searchField" placeholder="First Line" type = "text" name = "firstLine"/>
                <input class="searchField"placeholder="Postcode" type = "text" name = "postcode"/>
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

