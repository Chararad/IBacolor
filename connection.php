<?php
    $host = "localhost";
    $dbname = "ibacolor";
    $username = "root";
    $password = "";
    try{
        $iBacolorConnection = new PDO("mysql:host=".  $host ."; dbname=". $dbname .";", $username, $password);
        $iBacolorConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    }
    catch(PDOException $exc){
        echo "Connection Failed! ". $exc.getMessage();
    }
    

?>