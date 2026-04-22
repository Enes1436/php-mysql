<?php
include 'config.php';

if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Please login first']);
    exit;
}

$product_id = (int)$_POST['product_id'];
$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $update_query = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id";
    $conn->query($update_query);
    echo json_encode(['status' => 'success', 'message' => 'Item quantity updated in cart']);
} else {
    $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)";
    if ($conn->query($insert_query)) {
        echo json_encode(['status' => 'success', 'message' => 'Item added to cart']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error adding to cart']);
    }
}

$conn->close();
?>
