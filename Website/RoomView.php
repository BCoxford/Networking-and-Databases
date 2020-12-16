<?php
    include('Session.php');
    $username = $_SESSION['username'];
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        //Read Data
        if(isset($_POST['search'])) {
            $placeNumber = $_POST['search'];
            $sql = NULL;

            $placeNumber = mysqli_real_escape_string($db, $placeNumber);
            
            $sql = "SELECT placeNumber, roomNumber, floorNumber, monthlyRate, hallID, apartmentID FROM Room WHERE placeNumber='$placeNumber'";

            if(isset($sql)) {
                $result = mysqli_query($db,$sql) or die(mysqli_error($db));
                $count = mysqli_num_rows($result);

                $result = mysqli_fetch_array($result);

                if($count >= 1) {
                    $placeNumber = $result['placeNumber'];
                    $roomNumber = $result['roomNumber']; 
                    $floorNumber = $result['floorNumber']; 
                    $monthlyRate = $result['monthlyRate'];
                    $hallID = $result['hallID']; 
                    $apartmentID = $result['apartmentID']; 
                }
                else {
                    header("location: RoomSearch.php");
                }
            }
        }
        
        //Update Data
        
        if($_POST['action'] == "Update Details") {

            $message = "";
            $valid=true;

            //error_log("Reached 1", 0);

            //Check shared fields
            if(isset($_POST['placeNumber']) && isset($_POST['roomNumber'])) {
                //Generic Details Defined
                $placeNumber = $_POST['placeNumber'];
                $roomNumber = $_POST['roomNumber']; 
                $floorNumber = $_POST['floorNumber']; 
                $monthlyRate = $_POST['monthlyRate']; 
                $hallID = $_POST['hallID'];
                $apartmentID = $_POST['apartmentID'];

                if(empty($roomNumber)) {
                    $message = "Please enter a a room number";
                    $valid=false;
                }

                if(!empty($hallID) && !empty($apartmentID)) {
                    $message= "Please enter either an apartment Id or a hall Id";
                    $valid=false;
                }
                else if(empty($hallID) && !empty($apartmentID)) {
                    if(!is_int((int)$apartmentID)) {
                        $message= "Please enter a valid integer for the apartment Id";
                        $valid=false;
                    }
                }
                else if(!empty($hallID) && empty($apartmentID)) {
                    if(!is_int((int)$hallID)) {
                        $message= "Please enter a valid integer for the hall Id";
                        $valid=false;
                    }
                }
                else {
                    $message= "Please enter either an apartment Id or a hall Id";;
                    $valid=false;
                }

                if(empty($monthlyRate)) {
                    $message= "Please enter the monthly rate";
                    $valid=false;
                }
                else if(!is_int((int)$monthlyRate)) {
                    $message= "Please enter a valid integer for the monthly rate";
                    $valid=false;
                }

                $sqlGeneric = "";

                if(!empty($placeNumber) && !empty($apartmentID)) {
                    $sqlGeneric = "UPDATE Room SET roomNumber='$roomNumber', floorNumber='$floorNumber', monthlyRate='$monthlyRate', hallID=NULL, apartmentID='$apartmentID' WHERE placeNumber='$placeNumber'";
                }
                else if(!empty($placeNumber) && !empty($hallID)) {
                    $sqlGeneric = "UPDATE Room SET roomNumber='$roomNumber', floorNumber='$floorNumber', monthlyRate='$monthlyRate', hallID='$hallID', apartmentID=NULL WHERE placeNumber='$placeNumber'";
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
            if(!empty($_POST['deleteRecord']) && isset($_POST['placeNumber'])) {
                $id = $_POST['placeNumber'];
                $sqlUpdate = "UPDATE Lease SET placeNumber = NULL WHERE placeNumber='$id'";
                $sqlDelete = "DELETE FROM Inspection WHERE placeNumber='$id'";
                $sqlDelete2 = "DELETE FROM Room WHERE placeNumber='$id'";
                
                $errors = [];
                $db->autocommit(FALSE);
                if(!$db->query($sqlUpdate)) {
                    $errors[] = $db->error;   
                }
                if(!$db->query($sqlDelete)) {
                    $errors[] = $db->error;  
                }
                if(!$db->query($sqlDelete2)) {
                    $errors[] = $db->error;  
                }

                if (count($errors) ===0) {
                    $db->commit();
                    $message = "The room has been deleted along with its inspection logs.";
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
                
                <p>Place Number</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($placeNumber)){echo $placeNumber;} ?>" type = "text" name = "placeNumber" readonly/>
                
                <p>Room Number</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($roomNumber)){echo $roomNumber;} ?>" type = "text" name = "roomNumber"/>
                
                <p>Floor Number</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($floorNumber)){echo $floorNumber;} ?>" type = "text" name = "floorNumber"/>
                
                <p>Monthly Rate (£GBP)</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($monthlyRate)){echo $monthlyRate;} ?>" type = "text" name = "monthlyRate"/>
                
                <p>Hall Id</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($hallID)){echo $hallID;} ?>" type = "text" name = "hallID"/>
                
                <p>Apartment Id</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($apartmentID)){echo $apartmentID;} ?>" type = "text" name = "apartmentID"/>
                                
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

