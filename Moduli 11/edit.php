<?php
include_once('config.php');


    $id = $_GET['id'];

    $sql = "SELECT * FROM users WHERE id = :id";

    $getUsers = $conn->prepare($sql);

    $getUsers->bindParam(':id', $id);

    $getUsers->execute();

    $data = $prep->fetch();


?>
<!DOCTYPE html>
<html lang="en">
<head>
 
    <title>Document</title>
</head>
<body>
 
    <form action="update.php" method="POST">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">
        <input type="text" name="username" value="<?= $data['username'] ?>">
        <input type="text" name="name" value="<?= $data['name'] ?>">
        <input type="email" name="email" value="<?= $data['email'] ?>">
        <input type="password" name="password" value="<?= $data['password'] ?>">
        <button type="submit">Update</button>
    </form>
</body>
</html>