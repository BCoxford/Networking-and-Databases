<?php

    function validateDate($date) {
        $Date = explode('-', $date);
        return checkdate($Date[1], $Date[2], $Date[0]);
    }

    include('Session.php');
    $username = $_SESSION['username'];
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $dateDue = $_POST['dateDue'];
        $semester = $_POST['semester'];
        $paymentDue = $_POST['paymentDue'];
        $leaseNumber = $_POST['leaseNumber'];
        $firstReminder = "";
        $secondReminder = "";
        
        $valid = true;
        
        if(!empty($dateDue) && validateDate($dateDue)) {
            $firstReminder = date("Y-m-d", strtotime($dateDue . ' - 15 days'));
            $secondReminder = date("Y-m-d", strtotime($dateDue . ' - 5 days'));
        }
        else {
            $message = "Please enter a validate date in format YYYY-MM-DD";
            $valid=false;
        }
        
        if(!empty($semester)) {
            if (!is_int((int) $semester)) {
                $message = "Please enter the semester number 1/2/3(Summer)";
                $valid=false;
            }
        }
        else {
            $message = "Please enter the semester number 1/2/3(Summer)";
            $valid=false;
        }
        
        if(!empty($paymentDue)) {
            if (!is_numeric((float) $paymentDue)) {
                $message = "Please enter the payment amount due";
                $valid=false;
            }
        }
        else {
            $message = "Please enter the payment amount due";
            $valid=false;
        }
        
        if(!empty($leaseNumber)) {
            if (!is_int((int) $leaseNumber)) {
                $message = "Please enter the lease number";
                $valid=false;
            }
        }
        else {
            $message = "Please enter the lease number";
            $valid=false;
        }
        
        if($valid==true) {
            
            $sqlInvoice = "INSERT INTO Invoice (invoiceID, dateDue, semester, paymentDue, leaseNumber) VALUES (NULL, '$dateDue', '$semester', '$paymentDue', '$leaseNumber');";
            
            $sqlDates = "INSERT INTO ReminderDates (dateDue, firstReminder, secondReminder) VALUES ('$dateDue', '$firstReminder', '$secondReminder');";
            
            $db->autocommit(FALSE);
            $db->query($sqlInvoice);
            $db->query($sqlDates);

            if ($db->commit()) {
                $message = "The invoice has been created!";
            } 
            else {
                $message = $db->error;
            }
            $db->autocommit(TRUE); 
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
            <li style="float:right"><a><?php if(isset($username)){echo $username;}?></a></li>
        </ul>
        <div class="updateResults">
            <form action = "" method = "post">
                <a><?php if(isset($message)){echo $message;}?></a>
                
                <p>Date Due</p>
                <input placeholder="Required (YYYY-MM-DD)" class="resultField" type = "text" name = "dateDue"/>
                
                <p>Semester</p>
                <input placeholder="Required (1/2/3(Summer))" class="resultField" type = "text" name = "semester"/>
                
                 <p>Payment Due (£GBP)</p>
                <input placeholder="Required" class="resultField" type = "text" name = "paymentDue"/>
                
                 <p>Lease Number</p>
                <input placeholder="Required" class="resultField" type = "text" name = "leaseNumber"/>
                
                <hr>
                
                <input type="submit" name="action" value="Create Invoice"/>
                
                <hr>
                
                <a href="CreateDashboard.php"><input class="backButton" type="button" value="Dashboard" /></a>
                
            </form>
        </div>
        
    </body>
</html>

