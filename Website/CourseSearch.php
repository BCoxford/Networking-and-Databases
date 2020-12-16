<?php

    function validateDate($date) {
        $Date = explode('-', $date);
        return checkdate($Date[1], $Date[2], $Date[0]);
    }

    include('Session.php');
    $username = $_SESSION['username'];

    if($_SERVER["REQUEST_METHOD"] == "POST") {
      
        $courseNumber = $_POST['courseNumber'];
        $universityID = $_POST['universityID'];
        
        $sql = "SELECT DISTINCT * FROM Course WHERE courseNumber='$courseNumber' OR universityID='$universityID'";
      
        if(isset($sql)) {
            $result = mysqli_query($db,$sql) or die(mysqli_error($db));

            $count = mysqli_num_rows($result);

            if($count >= 1) {
                $numResults = $count . " results";
                $results = "<table><tr><th>Course Number</th><th>Course Title</th><th>Course Instructor</th><th>Course Email</th><th>Course Mobile</th><th>More Details</th></tr>";
                while($row = mysqli_fetch_array($result)) {
                    $results = $results . "<tr><th>".$row["courseNumber"]."</th><th>".$row["courseTitle"]."</th><th>".$row["courseInstructor"]."</th><th>".$row["courseEmail"]."</th><th>".$row["courseTelephone"]."</th><th><form action='CourseView.php' method='post'><input style='margin-top:15px;' type='submit' value='View'/><input type='hidden' name='search' value='".$row["courseNumber"]."'</input></form></th></tr>";
                }
                $results = $results . "</table>";
            }
            else {
                $numResults = "0 results";
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
            <li style="float:right"><a><?php if(isset($username)){ echo $username; } ?></a></li>
        </ul> 
        <div class="form">
            
            <form class="centerBoxes" action = "" method = "post">
                <br/>
                <input class="searchField" placeholder="University Id" type = "text" name = "universityID"/>
                <input class="searchField" placeholder="Course Number" type = "text" name = "courseNumber"/>
                <input class="searchField" type = "submit" value = "Search"/>
                <br/>
            </form>
            
            <p><?php if(isset($numResults)){echo $numResults;}?></p>
            
            <div id="results">
            
                <?php if(isset($results)){echo $results; }?>
            
            </div>
        
        </div>
    </body>
</html>

