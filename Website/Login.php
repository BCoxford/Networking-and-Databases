<?php
    include("Config.php");
    session_start();  

    $error= " ";

    if(isset($dbError)) {
        $error = $dbError;
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
      
        $username = $_POST['username'];
        $splitName = explode(".", $username);
        
        $firstname = mysqli_real_escape_string($db,$splitName[0]);
        $lastname = mysqli_real_escape_string($db,$splitName[1]);
        $password = md5(mysqli_real_escape_string($db,$_POST['password'])); 
      
        $sql = "SELECT a.staffID FROM ResidentStaff a INNER JOIN GeneralDetails b ON a.detailsID=b.detailsID INNER JOIN Login c ON a.loginID=c.loginID WHERE b.firstname='$firstname' AND b.lastname='$lastname' AND c.password='$password'";
        
        $result = mysqli_query($db,$sql) or die(mysqli_error($connection));
      
        $count = mysqli_num_rows($result);

        if($count == 1) { //If the result returns 1 row, then the login is accepted.
            $_SESSION['username'] = $username;
            header("location: Dashboard.php");
        }
        else {
            $error = "Either the password or username was incorrect!";
        }
    }
?>

<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" type="text/css" href="stylesheet.css">  
    </head>
    <body bgcolor="#FFFFFF">
        <div align="center">
            <div class="centered" align="center">
                
                <div class="heading"><b>YAHUAS Staff Login</b></div>

                <div style = "margin:30px">

                    <form action = "" method = "post">
                        <input style="width:80%" placeholder="Username" type = "text" name = "username" class = "box"/>
                        <br/><br/>
                        <input style="width:80%" placeholder="Password" type = "password" name = "password" class = "box" />
                        <br/><br/>
                        <input style="width:80%" type = "submit" value = "Login"/>
                        <br/>
                    </form>

                    <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>	
                </div>	
            </div>
        </div>
    </body>
</html>