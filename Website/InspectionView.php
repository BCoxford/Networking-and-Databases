<?php
    include('Session.php');
    $username = $_SESSION['username'];
    if($_SERVER["REQUEST_METHOD"] == "POST") {   
        if(isset($_POST['search'])) {
            $id = $_POST['search'];
        }
        else if (isset($_POST['updateLog'])) {
            $valid = true;
            $condition = $_POST['condition'];
            $additional = $_POST['additionalComments'];
            $staffID = $_POST['staffID'];
            $logID = $_POST['inspectionID'];
            if(!empty($staffID)) {
                if (!is_int((int)$staffID)) {
                    $message = "Please enter your staff ID";
                    $valid=false;
                }
            }
            else {
                $message = "Please enter your staff ID";
                $valid=false;
            }
            
            if(empty($condition) && empty($additional)) {
                $message = "Please enter why the condition was unsatisfactory.";
                $valid=false;
            }
            
            if(!empty($condition)) {
                $condition = 1;
            }
            else {
                $condition = 0;
            }
            
            $sql = "UPDATE Inspection SET conditionSatisfactory='$condition', additionalComments='$additional', staffID='$staffID' WHERE inspectionID='$logID'";
            
            if($valid==true && !empty($sql)) {
                if ($db->query($sql)) {
                    $message = "The update is successful!";
                } 
                else {
                    $message = $db->error;
                }
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
                
                <p>Was the condition of the room satisfactory?</p>
                <input type="checkbox" name="condition" value="Yes" checked/>
                
                <p>Additional Comments</p>
                <input placeholder="Enter here" class="resultField" type = "text" name = "additionalComments"/>
                
                <p>Staff ID</p>
                <input placeholder="Enter here" class="resultField" type = "text" name = "staffID"/>
                
                <input type='hidden' name='inspectionID' value="<?php if(isset($id)){echo $id;} ?>"/>
                                
                <hr>
                     
                <input type="submit" name="updateLog" value="Update Inspection Log"/>
                
                <a href="InspectionSearch.php"><input class="backButton" type="button" value="Search Inspections" /></a>
                
            </form>
        </div>
        
    </body>
</html>

