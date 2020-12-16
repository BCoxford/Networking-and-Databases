<?php
    include('Session.php');
    $username = $_SESSION['username'];

    if($_SERVER["REQUEST_METHOD"] == "POST") {
      
        $dateTo = $_POST['dateTo'];
        $dateFrom = $_POST['dateFrom'];
        
        if(!empty($dateTo) && !empty($dateFrom)) {
            //If All
            $sql = "SELECT a.leaseNumber, a.paymentDue, a.datePaid, a.paymentMethod, d.firstname, d.lastname FROM Invoice a INNER JOIN Lease b ON a.leaseNumber=b.leaseNumber INNER JOIN Student c ON b.leaseNumber=c.leaseNumber INNER JOIN GeneralDetails d ON c.detailsID=d.detailsID WHERE a.datePaid < '$dateTo' AND a.datePaid > '$dateFrom'";
        }
        else {
            $numResults = "You can produce an income report on all paid invoices using the date from and date to";
        }
      
        if(isset($sql)) {
            $result = mysqli_query($db,$sql) or die(mysqli_error($db));

            $count = mysqli_num_rows($result);

            if($count >= 1) {
                $numResults = $count . " results";
                $results = "<table><tr><th>Lease Number</th><th>Amount Paid</th><th>Date Paid</th><th>Payment Method</th><th>First Name</th><th>Last Name</th></tr>";
                
                $total = 0;
                
                while($row = mysqli_fetch_array($result)) {
                    $total = $total + (int)$row['paymentDue'];
                    $results = $results . "<tr><th>".$row["leaseNumber"]."</th><th>£".$row["paymentDue"]."</th><th>".$row["datePaid"]."</th><th>".$row["paymentMethod"]."</th><th>".ucfirst($row["firstname"])."</th><th>".ucfirst($row["lastname"])."</th></tr>";
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
                <input class="searchField" type = "submit" value = "Produce Income Report"/>
                <br/>
            </form>
            </div>
            
            <p><?php if(isset($numResults)){echo $numResults;}?></p>
            
            <div id="results">
            
                <?php if(isset($results)){echo $results; }?>
                
                <br>
                
                <?php if(isset($total) && isset($dateTo) && isset($dateFrom)){echo "Total Income Between ".$dateFrom." and ".$dateTo.": <b>£".$total."</b>"; }?>
                
                <br>
                <br>
            
            </div>
            
            <div class="noPrint">
            
            <?php if(isset($button)){echo $button; }?>
            
            </div>
        </div>
    </body>
</html>

