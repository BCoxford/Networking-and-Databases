<?php

    function validateDate($date) {
        $Date = explode('-', $date);
        return checkdate($Date[1], $Date[2], $Date[0]);
    }

    include('Session.php');
    $username = $_SESSION['username'];

    if($_SERVER["REQUEST_METHOD"] == "POST") {
      
        $leaseNumber = $_POST['leaseNumber'];
        $paymentMethod = strtolower($_POST['paymentMethod']);
        $semester = $_POST['semester'];
        $dateDue = $_POST['dateDue'];
        $datePaid = $_POST['datePaid'];
        
        $sql="";
        
        if(!empty($dateDue) && !validateDate($dateDue)) {
            $numResults="Enter a valid date due.";
        }
        else if(!empty($datePaid) && !validateDate($datePaid)) {
            $numResults="Enter a valid date paid.";
        }
        else {
            $sql = "SELECT DISTINCT invoiceID, leaseNumber, semester, dateDue FROM Invoice WHERE leaseNumber='$leaseNumber' OR semester='$semester' OR paymentMethod='$paymentMethod' OR dateDue='$dateDue' OR datePaid='$datePaid'";
        }
      
        if(isset($sql)) {
            $result = mysqli_query($db,$sql) or die(mysqli_error($db));

            $count = mysqli_num_rows($result);

            if($count >= 1) {
                $numResults = $count . " results";
                $results = "<table><tr><th>Lease Number</th><th>Semester</th><th>Date Due</th><th>More Details</th></tr>";
                while($row = mysqli_fetch_array($result)) {
                    $results = $results . "<tr><th>".$row["leaseNumber"]."</th><th>".$row["semester"]."</th><th>".$row["dateDue"]."</th><th><form action='InvoiceView.php' method='post'><input style='margin-top:15px;' type='submit' value='View'/><input type='hidden' name='search' value='".$row["invoiceID"]."'</input></form></th></tr>";
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
                <input class="searchField" placeholder="Payment Method" type = "text" name = "paymentMethod"/>
                <input class="searchField" placeholder="Semester" type = "text" name = "semester"/>
                <input class="searchField" placeholder="Date Due (YYYY-MM-DD)" type = "text" name = "dateDue"/>
                <input class="searchField"placeholder="Date Paid (YYYY-MM-DD)" type = "text" name = "datePaid"/>
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

