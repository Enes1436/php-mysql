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
<!DOCTYPE html>
<html lang="en">
<head>
  
    <title>Add Users</title>


</head>
<body>
   <a href="index.php">Dashboard</a>
   <form action="add.php" method="POST">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <input type="email" name="email" placeholder="Email">
    <button type="submit" name="submit">Add </button>
</body>
</html>