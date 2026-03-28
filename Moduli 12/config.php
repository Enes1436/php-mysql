<?php
$host ='localhost';
$db ='mod13';
$username = 'root';
$password = '';

try{
    $connect = new PDO("mysql:host=$host; dbname=$db", $username, $password);

    echo"Connection succesful <br> <br>";

} 
catch(Exception $e){
    echo "Somethng went wrong";
}
?>