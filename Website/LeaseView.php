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
            $leaseNumber = $_POST['search'];
            $sql = NULL;

            $leaseNumber = mysqli_real_escape_string($db, $leaseNumber);
            
            $sql = "SELECT leaseNumber, leaseDurationInSemesters, leaseStart, leaseEnd, placeNumber FROM Lease WHERE leaseNumber='$leaseNumber'";

            if(isset($sql)) {
                $result = mysqli_query($db,$sql) or die(mysqli_error($db));
                $count = mysqli_num_rows($result);

                $result = mysqli_fetch_array($result);

                if($count >= 1) {
                    $leaseNumber = $result['leaseNumber'];
                    $leaseDurationInSemesters = $result['leaseDurationInSemesters']; 
                    $leaseStart = $result['leaseStart']; 
                    $leaseEnd = $result['leaseEnd'];
                    $placeNumber = $result['placeNumber'];
                }
                else {
                    header("location: LeaseSearch.php");
                }
            }
        }
        
        if ($_POST['action'] == "Update Details") {
        
            //Update Data

            $message = "";
            $valid=true;

            //error_log("Reached 1", 0);

            //Check shared fields
            if(isset($_POST['leaseNumber']) && isset($_POST['leaseDurationInSemesters'])) {
                //Generic Details Defined
                $leaseNumber = $_POST['leaseNumber'];
                $leaseDurationInSemesters = $_POST['leaseDurationInSemesters']; 
                $leaseStart = $_POST['leaseStart']; 
                $leaseEnd = $_POST['leaseEnd'];
                $placeNumber = $_POST['placeNumber'];

                if(!empty($leaseStart)) {
                    if (!validateDate($leaseStart)) {
                        $message = "Please enter a valid start date (YYYY-MM--DD)";
                        $valid=false;
                    }
                }
                else {
                    $message = "Please enter a valid start date (YYYY-MM--DD)";
                    $valid=false;
                }

                if(!empty($leaseEnd)) {
                    if (!validateDate($leaseEnd)) {
                        $message = "Please enter a valid end date (YYYY-MM--DD)";
                        $valid=false;
                    }
                }

                if(!empty(leaseStart) && !empty(leaseEnd) && validateDate($leaseStart) && validateDate($leaseEnd)) {
                    if(strtotime($leaseStart) >= strtotime($leaseEnd)) {
                        $message = "The end date must come after the start date!";
                        $valid=false;
                    }
                }

                if(!empty($leaseDurationInSemesters)) {
                    if (!is_int((int)$leaseDurationInSemesters)) {
                        $message = "Please enter a valid integer duration (in semesters)";
                        $valid=false;
                    }
                }
                else {
                    $message = "Please enter a valid integer duration (in semesters)";
                    $valid=false;
                }

                if(!empty($placeNumber)) {
                    if (!is_int((int)$placeNumber)) {
                        $message = "Please enter a valid place number";
                        $valid=false;
                    }
                }
                else {
                    $message = "Please enter a valid place number";
                    $valid=false;
                }

                $sqlGeneric = "";

                if(!empty($leaseNumber) && !empty($leaseEnd)) {
                    $sqlGeneric = "UPDATE Lease SET leaseDurationInSemesters='$leaseDurationInSemesters', leaseStart='$leaseStart', leaseEnd='$leaseEnd', placeNumber='$placeNumber' WHERE leaseNumber='$leaseNumber'";
                }
                else if(!empty($leaseNumber) && empty($leaseEnd)) {
                    $sqlGeneric = "UPDATE Lease SET leaseDurationInSemesters='$leaseDurationInSemesters', leaseStart='$leaseStart', placeNumber='$placeNumber' WHERE leaseNumber='$leaseNumber'";
                }
                else {
                    if(empty($message)) {
                        $message = "An error occurred! The information was not updated!";
                        $valid=false;
                    }
                }

                if($valid==true && !empty($sqlGeneric)) {
                    if ($db->query($sqlGeneric)) {
                        $message = "The update is successful!";
                    } 
                    else {
                        $message = $db->error;
                    }
                }
            }
        }
        else if ($_POST['action'] == "Delete Record") {
            if(!empty($_POST['deleteRecord']) && isset($_POST['leaseID'])) {
                $id = $_POST['leaseID'];
                $sqlUpdate = "UPDATE Student SET leaseID = NULL WHERE leaseID='$id'";
                $sqlUpdate2 = "UPDATE Invoice SET leaseID = NULL WHERE leaseID='$id'";
                $sqlDelete = "DELETE FROM Lease WHERE leaseID='$id'";
                
                $errors = [];
                $db->autocommit(FALSE);
                if(!$db->query($sqlUpdate)) {
                    $errors[] = $db->error;   
                }
                if(!$db->query($sqlUpdate2)) {
                    $errors[] = $db->error;  
                }
                if(!$db->query($sqlDelete)) {
                    $errors[] = $db->error;  
                }

                if (count($errors) ===0) {
                    $db->commit();
                    $message = "The staff member has been created.";
                } 
                else {
                    $db->rollback();
                    foreach($errors as $e) {
                        $message = $message . "Error: " . $e . "<br>";    
                    }
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
                <p>Lease Number</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($leaseNumber)){echo $leaseNumber;} ?>" type = "text" name = "leaseNumber" readonly/>
                
                <p>Lease Duration (In Semesters)</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($leaseDurationInSemesters)){echo $leaseDurationInSemesters;} ?>" type = "text" name = "leaseDurationInSemesters"/>
                
                <p>Lease Start Date</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($leaseStart)){echo $leaseStart;} ?>" type = "text" name = "leaseStart"/>
                
                <p>Lease End Date</p>
                <input placeholder="N/A" placeholder="N/A" class="resultField" value="<?php if(isset($leaseEnd)){echo $leaseEnd;} ?>" type = "text" name = "leaseEnd"/>
                
                <p>Place Number</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($placeNumber)){echo $placeNumber;} ?>" type = "text" name = "placeNumber"/>
                                
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

