<?php
    include('Session.php');
    $username = $_SESSION['username'];
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $universityID = $_POST['universityID'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $middlename = $_POST['middlename'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $buildingNumber = $_POST['buildingNumber'];
        $firstLine = $_POST['firstLine'];
        $secondLine = $_POST['secondLine'];
        $postcode = $_POST['postcode'];
        
        $valid = true;
        
        //Validate the email address
        if(!empty($email)) { //Not an empty field?
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { //Correct Format?
                $message = "Please enter a valid email address";
                $valid=false;
            }
        }
        else {
            $message = "Please enter a valid email address";
            $valid=false;
        }

        if(!empty($mobile)) {
            $mobile = filter_var($mobile, FILTER_SANITIZE_NUMBER_INT);
            $mobile = str_replace("-", "", $mobile);
            if (strlen($mobile)!=11) {
                $message = "Please enter a valid UK mobile number";
                $valid=false;
            }
        }
        else {
            $message = "Please enter a mobile number";
            $valid=false;
        }

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

        if(empty($firstname)) {
            $message= "Please enter a first name";
            $valid=false;
        }

        if(empty($lastname)) {
            $message= "Please enter the last name";
            $valid=false;
        }
        
        if(empty($universityID)) {
            $message= "Please enter a university Id";
            $valid=false;
        }
        
        if($valid==true) {
            $sqlAddress = "INSERT INTO Address (addressID, buildingNumber, firstLine, secondLine, postcode) VALUES (NULL, '$buildingNumber', '$firstLine', '$secondLine', '$postcode');";
            
            $sqlDetails = "INSERT INTO GeneralDetails (detailsID, firstname, middlename, lastname, email, mobile, addressID) VALUES (NULL, '$firstname', '$middlename', '$lastname', '$email', '$mobile', (SELECT addressID FROM Address WHERE buildingNumber='$buildingNumber' AND firstLine='$firstLine' AND postcode='$postcode'));";
            
            $sqlAdviser = "INSERT INTO Adviser (adviserID, detailsID, universityID) VALUES (NULL, (SELECT detailsID FROM GeneralDetails WHERE email='$email' AND mobile='$mobile'), '$universityID');";
            
            $errors = [];
            $db->autocommit(FALSE);
            if(!$db->query($sqlAddress)) {
                $errors[] = $db->error;   
            }
            if(!$db->query($sqlDetails)) {
                $errors[] = $db->error;  
            }
            if(!$db->query($sqlAdviser)) {
                $errors[] = $db->error;  
            }

            if (count($errors) ===0) {
                $db->commit();
                $message = "The adviser has been created!";
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
                
                <p>University ID</p>
                <input placeholder="Required" class="resultField" type = "text" name = "universityID"/>
                
                 <p>First Name</p>
                <input placeholder="Required" class="resultField" type = "text" name = "firstname"/>
                
                <p>Middle Name</p>
                <input placeholder="Optional" class="resultField" type = "text" name = "middlename"/>
                
                <p>Last Name</p>
                <input placeholder="Required" class="resultField" type = "text" name = "lastname"/>
                
                <p>Email</p>
                <input placeholder="Required" class="resultField" type = "text" name = "email"/>
                
                <p>Mobile</p>
                <input placeholder="Required" class="resultField" type = "text" name = "mobile"/>
                
                <p>Building Number</p>
                <input placeholder="Required" class="resultField" type = "text" name = "buildingNumber"/>
                
                <p>First Line</p>
                <input placeholder="Required" class="resultField" type = "text" name = "firstLine"/>
                
                <p>Second Line</p>
                <input placeholder="Optional" class="resultField" type = "text" name = "secondLine"/>
                
                <p>Postcode</p>
                <input placeholder="Required" class="resultField" type = "text" name = "postcode"/>
                
                <hr>
                
                <input type="submit" name="action" value="Create New Student Adviser"/>
                
                <hr>
                
                <a href="CreateDashboard.php"><input class="backButton" type="button" value="Dashboard" /></a>
                
            </form>
        </div>
        
    </body>
</html>

