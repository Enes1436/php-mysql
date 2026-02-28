<?php
$host = "localhost";
$db = "testenes";
$user = "root";
$pass = "";


try{
    $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);

$sql ="DROP TABLE products";

$conn->exec($sql);

    echo" COLUMN DROP ";
}catch(Exception $e){
    echo(" COLUMN NOT DROP" );
}

?>