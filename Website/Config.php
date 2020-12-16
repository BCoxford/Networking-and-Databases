<?php
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'ben.coxford');
    define('DB_PASSWORD', 'DTREX2D8');
    define('DB_DATABASE', 'bencoxford_AccommodationDB');
    $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    $dbError = NULL;
    if ($db==false) {
        $dbError = mysqli_connect_error();
    }
?>