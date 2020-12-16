<?php
    include('Session.php');
    $username = $_SESSION['username'];
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        //Read Data
        if(isset($_POST['pass']) && isset($_POST['confirm'])) {
            $pass = $_POST['pass'];
            $confirm = $_POST['confirm'];
            $oldPass = $_POST['oldPass'];
            $oldPass = md5($oldPass);
            $sql = NULL;
            
            $username = $_SESSION['username'];
            $splitName = explode(".", $username);
        
            $firstname = mysqli_real_escape_string($db,$splitName[0]);
            $lastname = mysqli_real_escape_string($db,$splitName[1]);
            
            $valid=true;

            if(!empty($pass) && !empty($confirm)) {
                if($pass == $confirm) {
                    if (!(strlen($pass)>=8)) {
                        $message = "Please enter a password greater than 8 characters long!";
                        $valid=false;
                    }
                }
                else {
                    $message = "The passwords do not match!";
                    $valid=false;
                }
            }
            else {
                $message = "Please confirm your password!";
                $valid=false;
            } 
            
            $pass = md5($pass);
            
            $pass = mysqli_real_escape_string($db, $pass);
            $oldPass = mysqli_real_escape_string($db, $oldPass);
            
            $username = $_SESSION['username'];
            
            $splitName = explode(".", $username);
        
            $firstname = mysqli_real_escape_string($db,$splitName[0]);
            $lastname = mysqli_real_escape_string($db,$splitName[1]);
            
            $sql = "UPDATE Login a INNER JOIN ResidentStaff b ON a.loginID=b.loginID INNER JOIN GeneralDetails c ON b.detailsID=c.detailsID SET a.password='$pass' WHERE c.firstname='$firstname' AND c.lastname='$lastname' AND a.password='$oldPass'";
        
            if($valid==true && !empty($sql)) {
                if ($db->query($sql)) {
                    $message = "Your new password has been updated!";
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
                
                <input placeholder="Old Password" class="resultField" type = "password" name = "oldPass"/>
                
                <input placeholder="New Password" class="resultField" type = "password" name = "pass"/>
                
                <input placeholder="Confirm New Password" class="resultField" type = "password" name = "confirm"/>
                                
                <hr>
                     
                <input type="submit" value="Update Password"/>
                
                <a href="Dashboard.php"><input class="backButton" type="button" value="Back" /></a>
                
            </form>
        </div>
        
    </body>
</html>

