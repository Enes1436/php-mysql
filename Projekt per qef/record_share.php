<?php
include 'config.php';

$product_id = (int)$_POST['product_id'];
$platform = $conn->real_escape_string($_POST['platform']);
$user_id = isLoggedIn() ? $_SESSION['user_id'] : null;

$query = "INSERT INTO social_shares (product_id, platform, user_id) VALUES ($product_id, '$platform', " . ($user_id ? $user_id : 'NULL') . ")";
$conn->query($query);

$update_query = "UPDATE products SET social_share_count = social_share_count + 1 WHERE id = $product_id";
$conn->query($update_query);

echo json_encode(['status' => 'success']);
$conn->close();
?>
