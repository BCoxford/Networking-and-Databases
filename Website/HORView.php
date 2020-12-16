<?php
    include('Session.php');
    $username = $_SESSION['username'];
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        //Read Data
        if(isset($_POST['search'])) {
            $hallID = $_POST['search'];
            $sql = NULL;

            $hallID = mysqli_real_escape_string($db, $hallID);
            
            $sql = "SELECT a.hallID, a.numberSingleBeds, b.buildingNumber, b.firstLine, b.secondLine, b.postcode FROM HallOfResidents a INNER JOIN Address b ON a.addressID=b.addressID WHERE a.hallID='$hallID'";

            if(isset($sql)) {
                $result = mysqli_query($db,$sql) or die(mysqli_error($db));
                $count = mysqli_num_rows($result);

                $result = mysqli_fetch_array($result);

                if($count >= 1) {
                    $hallID = $result['hallID'];
                    $numberSingleBeds = $result['numberSingleBeds']; 
                    $buildingNumber = $result['buildingNumber']; 
                    $firstLine = $result['firstLine']; 
                    $secondLine = $result['secondLine'];
                    $postcode = $result['postcode'];
                }
                else {
                    header("location: HORSearch.php");
                }
            }
        }
        
        if ($_POST['action'] == "Update Details") {
            //Update Data

            $message = "";
            $valid=true;

            //error_log("Reached 1", 0);

            //Check shared fields
            if(isset($_POST['hallID']) && isset($_POST['numberSingleBeds']) && isset($_POST['buildingNumber']) && isset($_POST['firstLine']) && isset($_POST['postcode'])) {
                //Generic Details Defined
                $hallID = $_POST['hallID'];
                $numberSingleBeds = $_POST['numberSingleBeds']; 
                $buildingNumber = $_POST['buildingNumber']; 
                $firstLine = $_POST['firstLine']; 
                $secondLine = $_POST['secondLine'];
                $postcode = $_POST['postcode'];

                if(!empty($postcode)) {
                    if (!(strlen($postcode)>=5 && strlen($postcode)<=8)) {
                        $message = "Please enter a valid UK postcode";
                        $valid=false;
                    }
                }
                else {
                    $message = "Please enter a postcode";
                    $valid=false;
                }

                if(empty($firstLine)) {
                    $message= "Please enter the first line address";
                    $valid=false;
                }

                if(empty($buildingNumber)) {
                    $message= "Please enter the building number";
                    $valid=false;
                }

                if(empty($numberSingleBeds)) {
                    $message= "Please enter the number of single beds";
                    $valid=false;
                }
                else if(!is_int((int)$numberSingleBeds)) {
                    $message= "Please enter a valid number of single beds";
                    $valid=false;
                }

                $sqlGeneric = "";

                if(!empty($hallID)) {
                    $sqlGeneric = "UPDATE HallOfResidents a INNER JOIN Address b ON a.addressID=b.addressID SET b.buildingNumber='$buildingNumber', b.firstLine='$firstLine', b.secondLine='$secondLine', b.postcode='$postcode', a.numberSingleBeds='$numberSingleBeds' WHERE a.hallID='$hallID'; ";
                }
                else {
                    $message = "An error occurred! The information was not updated!";
                    $valid=false;
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
            if(!empty($_POST['deleteRecord']) && isset($_POST['hallID'])) {
                $id = $_POST['hallID'];
                $sqlUpdate = "UPDATE Room SET hallID = NULL WHERE hallID='$id'";
                $sqlDelete = "DELETE FROM HallOfResidents WHERE hallID='$id'";
                
                $errors = [];
                $db->autocommit(FALSE);
                if(!$db->query($sqlUpdate)) {
                    $errors[] = $db->error;   
                }
                if(!$db->query($sqlDelete)) {
                    $errors[] = $db->error;  
                }

                if (count($errors) ===0) {
                    $db->commit();
                    $message = "The hall has been deleted.";
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
                <p>Hall of Residents Id</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($hallID)){echo ucfirst($hallID);} ?>" type = "text" name = "hallID" readonly/>
                
                <p>Number of Single Beds</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($numberSingleBeds)){echo ucfirst($numberSingleBeds);}?>" type = "text" name = "numberSingleBeds"/>
                
                <p>Building Number</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($buildingNumber)){echo $buildingNumber;} ?>" type = "text" name = "buildingNumber"/>
                
                <p>First Line Address</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($firstLine)){echo $firstLine;} ?>" type = "text" name = "firstLine"/>
                
                <p>Second Line Address</p>
                <input placeholder="N/A" placeholder="N/A" class="resultField" value="<?php if(isset($secondLine) && !empty($secondLine)){echo $secondLine;} ?>" type = "text" name = "secondLine"/>
                
                <p>Postcode</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($postcode)){echo $postcode;} ?>" type = "text" name = "postcode"/>
                                
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

