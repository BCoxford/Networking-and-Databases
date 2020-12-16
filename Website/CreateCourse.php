<?php
    include('Session.php');
    $username = $_SESSION['username'];
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $courseNumber = $_POST['courseNumber'];
        $courseTitle = $_POST['courseTitle'];
        $courseInstructor = $_POST['courseInstructor'];
        $campusTelephone = $_POST['courseTelephone'];
        $campusEmail = $_POST['courseEmail'];
        $campusRoomNumber = $_POST['roomNumber'];
        $campusDepartment = $_POST['department'];
        $universityID = $_POST['universityID'];
        
        $valid = true;
        
        if(!empty($universityID)) {
            if (!is_int((int) $universityID)) {
                $message = "Please enter the university ID";
                $valid=false;
            }
        }
        else {
            $message = "Please enter the university ID";
            $valid=false;
        }
        
        if(empty($courseNumber)) {
            $message = "Please enter the course number";
            $valid=false;
        }
        
        if(empty($courseTitle)) {
            $message = "Please enter the course title";
            $valid=false;
        }
        
        if(empty($courseInstructor)) {
            $message = "Please enter the course's instructors name";
            $valid=false;
        }
        
        if(!empty($campusEmail)) { //Not an empty field?
            if(!filter_var($campusEmail, FILTER_VALIDATE_EMAIL)) { //Correct Format?
                $message = "Please enter a valid email address";
                $valid=false;
            }
        }
        else {
            $message = "Please enter a valid email address";
            $valid=false;
        }

        if(!empty($campusTelephone)) {
            $campusTelephone = filter_var($campusTelephone, FILTER_SANITIZE_NUMBER_INT);
            $campusTelephone = str_replace("-", "", $campusTelephone);
            if (strlen($campusTelephone)!=11) {
                $message = "Please enter a valid UK telephone number";
                $valid=false;
            }
        }
        else {
            $message = "Please enter a telephone number";
            $valid=false;
        }
        
        if(empty($campusRoomNumber)) {
            $message = "Please enter the course's room number";
            $valid=false;
        }
        
        if(empty($campusDepartment)) {
            $message = "Please enter the course's department name";
            $valid=false;
        }
        
        if($valid==true) {
            
            $sqlCourse = "INSERT INTO Course (courseNumber, courseTitle, courseInstructor, courseTelephone, courseEmail, roomNumber, department, universityID) VALUES ('$courseNumber','$courseTitle','$courseInstructor','$campusTelephone','$campusEmail','$campusRoomNumber','$campusDepartment','$universityID');";
            
            if($valid==true) {
                if ($db->query($sqlCourse)) {
                    $message = "A new course has been created!";
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
                
                <p>Course Number</p>
                <input placeholder="Required" class="resultField" type = "text" name = "courseNumber"/>
                
                <p>Course Title</p>
                <input placeholder="Required" class="resultField" type = "text" name = "courseTitle"/>
                
                <p>Course Instructors' Name</p>
                <input placeholder="Required" class="resultField" type = "text" name = "courseInstructor"/>
                
                <p>Course Telephone</p>
                <input placeholder="Required" class="resultField" type = "text" name = "courseTelephone"/>
                
                <p>Course Email</p>
                <input placeholder="Required" class="resultField" type = "text" name = "courseEmail"/>
                
                <p>Room Number</p>
                <input placeholder="Required" class="resultField" type = "text" name = "roomNumber"/>
                
                <p>Department</p>
                <input placeholder="Required" class="resultField" type = "text" name = "department"/>
                
                <p>University ID</p>
                <input placeholder="Required" class="resultField" type = "text" name = "universityID"/>
                
                <hr>
                
                <input type="submit" name="action" value="Create Course"/>
                
                <hr>
                
                <a href="CreateDashboard.php"><input class="backButton" type="button" value="Dashboard" /></a>
                
            </form>
        </div>
        
    </body>
</html>

