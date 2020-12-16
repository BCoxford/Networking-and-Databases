<?php
    include('Config.php');
    session_start();
   
    $user_check = $_SESSION['username'];
    $splitName = explode(".", $user_check);

    $ses_sql = mysqli_query($db,"SELECT firstname || '.' || lastname FROM GeneralDetails WHERE firstname = '$splitName[0]' AND lastname = '$splitName[1]'"); 

    $username = $ses_sql;
   
    if(!isset($_SESSION['username'])){
        header("location:Login.php");
        die();
    }
?>