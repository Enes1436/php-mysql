<?php
$host = "localhost";
$user = "root";
$pass = "";


try{
    $conn = new PDO("mysql:host=$host", $user, $pass);

$sql = "CREATE DATABASE testenes";

$conn->exec($sql);

    echo"Create Database";
}catch(Exception $e){
    echo("Not create database");
}

?>