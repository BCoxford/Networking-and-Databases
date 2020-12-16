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
            $courseNumber = $_POST['search'];
            $sql = NULL;

            $courseNumber = mysqli_real_escape_string($db, $courseNumber);
            
            $sql = $sql = "SELECT * FROM Course WHERE courseNumber='$courseNumber'";

            if(isset($sql)) {
                $result = mysqli_query($db,$sql) or die(mysqli_error($db));
                $count = mysqli_num_rows($result);

                $result = mysqli_fetch_array($result);

                if($count >= 1) {
                    $courseNumber = $result['courseNumber'];
                    $courseTitle = $result['courseTitle']; 
                    $courseInstructor = $result['courseInstructor']; 
                    $campusTelephone = $result['courseTelephone'];
                    $campusEmail = $result['courseEmail'];
                    $campusRoomNumber = $result['roomNumber'];
                    $campusDepartment = $result['department'];
                    $universityID = $result['universityID'];
                }
                else {
                    header("location: CourseSearch.php");
                }
            }
        }
        
        if ($_POST['action'] == "Update Details") {
            //Update Data

            $message = "";
            $valid=true;

            //error_log("Reached 1", 0);

            //Check shared fields
            if(isset($_POST['courseNumber']) && isset($_POST['courseTitle'])) {
                //Generic Details Defined
                $courseNumber = $_POST['courseNumber'];
                $courseTitle = $_POST['courseTitle']; 
                $courseInstructor = $_POST['courseInstructor']; 
                $campusTelephone = $_POST['courseTelephone'];
                $campusEmail = $_POST['courseEmail'];
                $campusRoomNumber = $_POST['roomNumber'];
                $campusDepartment = $_POST['department'];
                $universityID = $_POST['universityID'];

                if(!empty($universityID)) {
                    if (!is_int((int)$universityID)) {
                        $message = "Please enter a valid university Id";
                        $valid=false;
                    }
                }
                else {
                    $message = "Please enter a valid university Id";
                    $valid=false;
                }

                if(empty($courseTitle)) {
                    $message = "Please enter a course title";
                    $valid=false;
                }

                if(empty($courseInstructor)) {
                    $message = "Please enter a course instructor";
                    $valid=false;
                }

                if(empty($campusTelephone)) {
                    $message = "Please enter a course telephone";
                    $valid=false;
                }

                if(!empty($campusEmail)) { //Not an empty field?
                    if(!filter_var($campusEmail, FILTER_VALIDATE_EMAIL)) { //Correct Format?
                        $message = "Please enter a valid campus email address";
                        $valid=false;
                    }
                }
                else {
                    $message = "Please enter a campus email address";
                    $valid=false;
                }

                if(empty($campusRoomNumber)) {
                    $message = "Please enter a campus room number";
                    $valid=false;
                }

                if(empty($campusDepartment)) {
                    $message = "Please enter a campus department";
                    $valid=false;
                }

                $sqlGeneric = "";

                if(!empty($courseNumber)) {
                    $sqlGeneric = "UPDATE Course SET courseTitle='$courseTitle', courseInstructor='$courseInstructor', courseTelephone='$campusTelephone', courseEmail='$campusEmail', roomNumber='$campusRoomNumber', department='$campusDepartment', universityID='$universityID' WHERE courseNumber='$courseNumber'";
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
                else {
                        $message = "An error occurred! The information was not updated!";
                }
            }
        }
        else if ($_POST['action'] == "Delete Record") {
            if(!empty($_POST['deleteRecord']) && isset($_POST['courseNumber'])) {
                $id = $_POST['courseNumber'];
                $sqlUpdate = "UPDATE Student SET courseNumber = NULL WHERE courseNumber='$id'";
                $sqlDelete = "DELETE FROM Course WHERE courseNumber='$id'";
                
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
                    $message = "The course has been deleted!";
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
                
                <p>Course Number</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($courseNumber)){echo $courseNumber;} ?>" type = "text" name = "courseNumber" readonly/>
                
                <p>Course Title</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($courseTitle)){echo $courseTitle;} ?>" type = "text" name = "courseTitle"/>
                
                <p>Course Instructor</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($courseInstructor)){echo $courseInstructor;} ?>" type = "text" name = "courseInstructor"/>
                
                <p>Campus Telephone</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($campusTelephone)){echo $campusTelephone;} ?>" type = "text" name = "courseTelephone"/>
                
                <p>Campus Email</p>
                <input placeholder="N/A" placeholder="N/A" class="resultField" value="<?php if(isset($campusEmail)){echo $campusEmail;} ?>" type = "text" name = "courseEmail"/>
                
                <p>Campus Room Number</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($campusRoomNumber)){echo $campusRoomNumber;} ?>" type = "text" name = "roomNumber"/>
                
                <p>Campus Department</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($campusDepartment)){echo $campusDepartment;} ?>" type = "text" name = "department"/>
                
                <p>University Id</p>
                <input placeholder="N/A" class="resultField" value="<?php if(isset($universityID)){echo $universityID;} ?>" type = "text" name = "universityID"/>
                                
                <hr>
                     
                <input type="submit" name="action" value="Update Details"/>
                
                <hr>
                
                <div style="display:flex; justify-content:center;">
                
                    <a>Tick the box to delete the record</a><input type="checkbox" name="deleteRecord" value="Yes"/>
                    
                </div>
                
                <hr>
                
                <input type="submit" name="action" value="Delete Record"/>
                
                <hr>
                
                <a href="CourseSearch.php"><input class="backButton" type="button" value="Create New Search" /></a>
                
            </form>
        </div>
        
    </body>
</html>

