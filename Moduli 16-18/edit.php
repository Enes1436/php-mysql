<?php
include_once('config.php');

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $selectUser = $connect->prepare($sql);
    $selectUser->execute([$id]);
    $user = $selectUser->fetch();
}
?>