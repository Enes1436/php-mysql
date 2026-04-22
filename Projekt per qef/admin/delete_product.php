<?php
include '../config.php';

if (!isLoggedIn() || (getUserRole() != 'seller' && getUserRole() != 'admin')) {
    redirect('../index.php');
}

$product_id = (int)$_GET['id'];
$seller_id = $_SESSION['user_id'];

if (getUserRole() == 'seller') {
    $query = "DELETE FROM products WHERE id = $product_id AND seller_id = $seller_id";
} else {
    $query = "DELETE FROM products WHERE id = $product_id";
}

$conn->query($query);
redirect('dashboard.php');
?>
