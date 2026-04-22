<?php
include 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$item_id = (int)$_GET['item_id'];
$user_id = $_SESSION['user_id'];

$query = "DELETE FROM cart WHERE id = $item_id AND user_id = $user_id";
$conn->query($query);

redirect('cart.php');
?>
