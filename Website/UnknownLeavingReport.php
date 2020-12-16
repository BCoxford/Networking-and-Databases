<?php
    include('Session.php');
    $username = $_SESSION['username'];

    if($_SERVER["REQUEST_METHOD"] == "POST") {

        $sql = "SELECT c.placeNumber, b.firstname, b.lastname, b.email, b.mobile FROM Student a INNER JOIN GeneralDetails b ON a.detailsID=b.detailsID INNER JOIN Lease c ON a.leaseNumber=c.leaseNumber WHERE c.leaseEnd IS NULL";
      
        if(isset($sql)) {
            $result = mysqli_query($db,$sql) or die(mysqli_error($db));

            $count = mysqli_num_rows($result);

            if($count >= 1) {
                $numResults = $count . " results";
                $results = "<table><tr><th>Place Number</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Mobile</th></tr>";
                
                while($row = mysqli_fetch_array($result)) {
                    $results = $results . "<tr><th>".$row["placeNumber"]."</th><th>".ucfirst($row["firstname"])."</th><th>".ucfirst($row["lastname"])."</th><th>".$row['email']."</th><th>".$row['mobile']."</th></tr>";
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
                <input class="searchField" type = "submit" value = "Produce Unknown Leaving Date Report"/>
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

