<?php
    include('Session.php');
    $username = $_SESSION['username'];

    if($_SERVER["REQUEST_METHOD"] == "POST") {
      
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        
        $firstname = mysqli_real_escape_string($db, strtolower($firstname));
        $lastname = mysqli_real_escape_string($db, strtolower($lastname));
        $email = mysqli_real_escape_string($db, $email);
        $mobile = mysqli_real_escape_string($db, $mobile);
        $sql = "SELECT detailsID, firstname, lastname, email, mobile FROM GeneralDetails WHERE firstname='$firstname' OR lastname='$lastname' OR email='$email' OR mobile='$mobile'";
      
        if(isset($sql)) {
            $result = mysqli_query($db,$sql) or die(mysqli_error($db));

            $count = mysqli_num_rows($result);

            if($count >= 1) {
                $numResults = $count . " results";
                $results = "<table><tr><th>First name</th><th>Last name</th><th>Email</th><th>Mobile</th><th>More Details</th></tr>";
                while($row = mysqli_fetch_array($result)) {
                    $results = $results . "<tr><th>".ucfirst($row["firstname"])."</th><th>".ucfirst($row["lastname"])."</th><th>".$row["email"]."</th><th>".$row["mobile"]."</th><th><form action='PeopleView.php' method='post'><input style='margin-top:15px;' type='submit' value='View'/><input type='hidden' name='search' value='". $row["detailsID"] ."'</input></form></th></tr>";
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
                <input class="searchField" placeholder="First name" type = "text" name = "firstname"/>
                <input class="searchField" placeholder="Last name" type = "text" name = "lastname"/>
                <input class="searchField" placeholder="Email" type = "text" name = "email"/>
                <input class="searchField"placeholder="Mobile" type = "text" name = "mobile"/>
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

