<?php
    include('Session.php');
    $username = $_SESSION['username'];

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $semester = $_POST['semester'];

        if(!empty($semester) && is_int((int)$semester)) {
            $sql = "SELECT a.invoiceID, a.paymentDue, a.dateDue, a.datePaid, a.semester, a.leaseNumber, d.firstname, d.lastname, d.email, d.mobile FROM Invoice a INNER JOIN Lease b ON a.leaseNumber=b.leaseNumber INNER JOIN Student c ON b.leaseNumber=c.leaseNumber INNER JOIN GeneralDetails d ON c.detailsID=d.detailsID WHERE a.datePaid IS NULL AND a.semester='$semester'";
        }
        else {
            $numResults = "Please enter the semester number!";
        }
      
        if(isset($sql)) {
            $result = mysqli_query($db,$sql) or die(mysqli_error($db));

            $count = mysqli_num_rows($result);

            if($count >= 1) {
                $numResults = $count . " results";
                $results = "<table><tr><th>Invoice Id</th><th>Date Due</th><th>Semester</th><th>Lease Number</th><th>Payment Due</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Mobile</th></tr>";
                
                while($row = mysqli_fetch_array($result)) {
                    $results = $results . "<tr><th>".$row["invoiceID"]."</th><th>".$row["dateDue"]."</th><th>".$row["semester"]."</th><th>".$row["leaseNumber"]."</th><th>£".$row["paymentDue"]."</th><th>".ucfirst($row['firstname'])."</th><th>".ucfirst($row['lastname'])."</th><th>".$row['email']."</th><th>".$row['mobile']."</th></tr>";
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
                <input class="searchField" placeholder="Semester (1/2/3)" type = "text" name = "semester"/>
                <input class="searchField" type = "submit" value = "Produce Unpaid Invoice Report"/>
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

