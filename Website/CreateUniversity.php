<?php
    include('Session.php');
    $username = $_SESSION['username'];
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $universityName = $_POST['universityName'];
        $buildingNumber = $_POST['buildingNumber'];
        $firstLine = $_POST['firstLine'];
        $secondLine = $_POST['secondLine'];
        $postcode = $_POST['postcode'];
        
        $valid = true;

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
        
        if(empty($universityName)) {
            $message= "Please enter the university name";
            $valid=false;
        }
        
        if($valid==true) {
            $sqlAddress = "INSERT INTO Address (addressID, buildingNumber, firstLine, secondLine, postcode) VALUES (NULL, '$buildingNumber', '$firstLine', '$secondLine', '$postcode');";
            
            $sqlUniversity = "INSERT INTO University (universityID, universityName, addressID) VALUES (NULL, '$universityName', (SELECT addressID FROM Address WHERE buildingNumber='$buildingNumber' AND firstLine='$firstLine' AND postcode='$postcode'));";
            
            $errors = [];
            $db->autocommit(FALSE);
            if(!$db->query($sqlAddress)) {
                $errors[] = $db->error;   
            }
            if(!$db->query($sqlUniversity)) {
                $errors[] = $db->error;  
            }

            if (count($errors) ===0) {
                $db->commit();
                $message = "The university has been created.";
            } 
            else {
                $db->rollback();
                foreach($errors as $e) {
                    $message = $message . "Error: " . $e . "<br>";    
                }
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
                
                <p>University Name</p>
                <input placeholder="Required" class="resultField" type = "text" name = "universityName"/>
                
                <p>Building Number</p>
                <input placeholder="Required" class="resultField" type = "text" name = "buildingNumber"/>
                
                <p>First Line</p>
                <input placeholder="Required" class="resultField" type = "text" name = "firstLine"/>
                
                <p>Second Line</p>
                <input placeholder="Optional" class="resultField" type = "text" name = "secondLine"/>
                
                <p>Postcode</p>
                <input placeholder="Required" class="resultField" type = "text" name = "postcode"/>
                
                <hr>
                
                <input type="submit" name="action" value="Create New University"/>
                
                <hr>
                
                <a href="CreateDashboard.php"><input class="backButton" type="button" value="Dashboard" /></a>
                
            </form>
        </div>
        
    </body>
</html>

