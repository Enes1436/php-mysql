<?php
$host = "localhost";
$db = "testenes";
$user = "root";
$pass = "";


try{
    $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);

$username="Enes";

$password= "Nesi1234";

$sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

$conn->exec($sql);

    echo" ROW ADDED ";
}catch(Exception $e){
    echo(" ROW NOT ADDED" );
}

?>