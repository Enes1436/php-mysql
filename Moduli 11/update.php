<?php

include_once('config.php');

if(isset($_POST['update'])){

    $id = $_POST['id'];

    $username = $_POST['username'];

    $name = $_POST['name'];

    $email = $_POST['email'];

    $password = $_POST['password'];

    $sql = "UPDATE users SET username=:username, name=:name, email=:email, password=:password WHERE id=:id";

    $sqlQUERY = $connect->prepare($sql);

    $sqlQUERY->bindparam(':username', $username );

     $sqlQUERY->bindparam(":name", $name );

     $sqlQUERY->bindparam(":email",  $email );

     $sqlQUERY->bindparam(":password",  $password );

     $sqlQUERY->bindparam(":id",  $id );

     $sqlQUERY->execute();
}

