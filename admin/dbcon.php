<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "thesis";

    // Create connection
    try{
        $con = new PDO("mysql:host={$servername};dbname={$db}",$username, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOEXCEPTION $e){
        echo $e->getMessage();
    }
?>