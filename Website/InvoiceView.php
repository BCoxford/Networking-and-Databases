<?php

    function validateDate($date) {
        $Date = explode('-', $date);
        return checkdate($Date[1], $Date[2], $Date[0]);
    }

    include('Session.php');
    $username = $_SESSION['username'];
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        //Read Data
        if(isset($_POST['search'])) {
            $invoiceID = $_POST['search'];
            $sql = NULL;

            $invoiceID = mysqli_real_escape_string($db, $invoiceID);
            
            $sql = $sql = "SELECT a.invoiceID, a.semester, a.dateDue, a.datePaid, b.firstReminder, b.secondReminder, a.paymentMethod, a.paymentDue FROM Invoice a INNER JOIN ReminderDates b ON a.dateDue=b.dateDue WHERE invoiceID='$invoiceID'";

            if(isset($sql)) {
                $result = mysqli_query($db,$sql) or die(mysqli_error($db));
                $count = mysqli_num_rows($result);

                $result = mysqli_fetch_array($result);

                if($count >= 1) {
                    $invoiceID = $result['invoiceID'];
                    $semester = $result['semester']; 
                    $datePaid = $result['datePaid']; 
                    $dateDue = $result['dateDue'];
                    $paymentMethod = $result['paymentMethod'];
                    $firstReminder = $result['firstReminder'];
                    $secondReminder = $result['secondReminder'];
                    $paymentDue = $result['paymentDue'];
                }
                else {
                    header("location: InvoiceSearch.php");
                }
            }
        }
        
        if ($_POST['action'] == "Update Details") {
            //Update Data

            $message = "";
            $valid=true;

            //error_log("Reached 1", 0);

            //Check shared fields
            if(isset($_POST['invoiceID']) && isset($_POST['semester'])) {
                //Generic Details Defined
                $invoiceID = $_POST['invoiceID'];
                $semester = $_POST['semester']; 
                $datePaid = $_POST['datePaid']; 
                $dateDue = $_POST['dateDue'];
                $paymentMethod = $_POST['paymentMethod'];
                $firstReminder = $_POST['firstReminder'];
                $secondReminder = $_POST['secondReminder'];
                $paymentDue = $_POST['paymentDue'];

                if(!empty($dateDue)) {
                    if (!validateDate($dateDue)) {
                        $message = "Please enter a valid date due (YYYY-MM--DD)";
                        $valid=false;
                    }
                }
                else {
                    $message = "Please enter a valid date due (YYYY-MM--DD)";
                    $valid=false;
                }

                if(!empty($datePaid)) {
                    if (!validateDate($datePaid)) {
                        $message = "Please enter a valid date paid (YYYY-MM--DD)";
                        $valid=false;
                    }
                }

                if(!empty($firstReminder)) {
                    if (!validateDate($firstReminder)) {
                        $message = "Please enter a valid date paid (YYYY-MM--DD)";
                        $valid=false;
                    }
                }

                if(!empty($secondReminder)) {
                    if (!validateDate($secondReminder)) {
                        $message = "Please enter a valid date paid (YYYY-MM--DD)";
                        $valid=false;
                    }
                }

                if(!empty($firstReminder) && !empty($secondReminder) && validateDate($firstReminder) && validateDate($secondReminder)) {
                    if(strtotime($firstReminder) >= strtotime($secondReminder)) {
                        $message = "The first reminder date must come after the second reminder date!";
                        $valid=false;
                    }
                }

                if(!empty($paymentDue)) {
                    if (!is_numeric((float)$paymentDue)) {
                        $message = "Please enter the valid payment amount number";
                        $valid=false;
                    }
                }

                if(!empty($semester)) {
                    if (!is_int((int)$semester)) {
                        $message = "Please enter a semester number";
                        $valid=false;
                    }
                }

                $sqlGeneric = "";

                if(!empty($invoiceID)) {
                    if(empty($datePaid)) {
                        $sqlGeneric = "UPDATE Invoice SET datePaid=NULL, dateDue='$dateDue', paymentMethod='$paymentMethod', semester='$semester', paymentDue='$paymentDue' WHERE invoiceID='$invoiceID'";
                    }
                    else {
                        $sqlGeneric = "UPDATE Invoice SET datePaid='$datePaid', dateDue='$dateDue', paymentMethod='$paymentMethod', semester='$semester', paymentDue='$paymentDue' WHERE invoiceID='$invoiceID'";
                    }
                }
                else {
                    if(empty($message)) {
                        $message = "An error occurred! The information was not updated!";
                        $valid=false;
                    }
                }

                if($valid==true && !empty($sqlGeneric)) {
                    
                    $sqlDates = "UPDATE ReminderDates SET firstReminder='$firstReminder', secondReminder='$secondReminder' WHERE dateDue='$dateDue'";
                    
                    $db->autocommit(FALSE);
                    $db->query($sqlGeneric);
                    $db->query($sqlDates);

                    if ($db->commit()) {
                        $message = "The invoice has been updated!";
                    } 
                    else {
                        $message = $db->error;
                    }
                    $db->autocommit(TRUE); 
                }
            }
        }
        else if ($_POST['action'] == "Delete Record") {
            if(!empty($_POST['deleteRecord']) && isset($_POST['invoiceID'])) {
                $id = $_POST['invoiceID'];
                $sqlDelete = "DELETE FROM Invoice WHERE invoiceID='$id'";
                
                $db->autocommit(FALSE);
                $db->query($sqlDelete);
                
                if ($db->commit()) {
                    $message = "The invoice has been deleted!";
                } 
                else {
                    $message = $db->error;
                }
                $db->autocommit(TRUE); 
            }
            else {
                $message = "You must confirm by ticking the box to delete the record!";
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
            <li style="float:right"><a><?php if(isset($username)){echo $username;}?></a></li>
        </ul>
        <div class="updateResults">
            <form action = "" method = "post">
                <a><?php if(isset($message)){echo $message;}?></a>
                <p>Invoice Id</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($invoiceID)){echo $invoiceID;} ?>" type = "text" name = "invoiceID" readonly/>
                
                <p>Date Paid</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($datePaid)){echo $datePaid;} ?>" type = "text" name = "datePaid"/>
                
                <p>Date Due</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($dateDue)){echo $dateDue;} ?>" type = "text" name = "dateDue"/>
                
                <p>Payment Method</p>
                <input placeholder="N/A" placeholder="N/A" class="resultField" value="<?php if(isset($paymentMethod)){echo ucfirst($paymentMethod);} ?>" type = "text" name = "paymentMethod"/>
                
                <p>First Reminder Date</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($firstReminder)){echo $firstReminder;} ?>" type = "text" name = "firstReminder"/>
                
                <p>Second Reminder Date</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($secondReminder)){echo $secondReminder;} ?>" type = "text" name = "secondReminder"/>
                
                <p>Semester</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($semester)){echo $semester;} ?>" type = "text" name = "semester"/>
                
                <p>Payment Due (£GBP)</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($paymentDue)){echo $paymentDue;} ?>" type = "text" name = "paymentDue"/>
                                
                <hr>
                     
                <input type="submit" name="action" value="Update Details"/>
                
                <hr>
                
                <div style="display:flex; justify-content:center;">
                
                    <a>Tick the box to delete the record</a><input type="checkbox" name="deleteRecord" value="Yes"/>
                    
                </div>
                
                <hr>
                
                <input type="submit" name="action" value="Delete Record"/>
                
                <hr>
                
                <a href="ApartmentSearch.php"><input class="backButton" type="button" value="Create New Search" /></a>
                
            </form>
        </div>
        
    </body>
</html>

