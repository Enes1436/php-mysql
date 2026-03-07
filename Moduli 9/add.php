<?php

include_once('config.php');

if(isset($_POST["submit"])){

    $username = $_POST['username'];

    $email = $_POST['email'];

    $password = $_POST['password'];

    $sql = "INSERT INTO users(username,email,password) VALUES (:username,:email,:password)";

    $sqlQUERY = $connect->prepare($sql);

    $sqlQUERY->bindparam(':username', $username );

     $sqlQUERY->bindparam(":password", $password );

     $sqlQUERY->bindparam(":email",  $email );

     $sqlQUERY->execute();

echo"The users was added successfully";
}
?>