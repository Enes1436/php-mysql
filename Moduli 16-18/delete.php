<?php
include_once('config.php');


    $id = $_GET['id'];
    $sql = "DELETE FROM users WHERE id = ?";
    $prep = $connect->prepare($sql);
    $prep->bindParam(':id', $id);
    $prep->execute();

    
?>