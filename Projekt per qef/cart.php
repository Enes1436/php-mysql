<?php
include 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$query = "SELECT c.*, p.name, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = $user_id";
$result = $conn->query($query);

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Social Commerce</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }
        
        nav {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1rem 2rem;
            color: white;
        }
        
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
        }
        
        h2 {
            color: #667eea;
            margin-bottom: 2rem;
        }
        
        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background: #667eea;
            color: white;
        }
        
        .remove-btn {
            padding: 0.5rem 1rem;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        
        .summary {
            background: white;
            padding: 2rem;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-top: 2rem;
        }
        
        .checkout-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: opacity 0.3s;
        }
        
        .checkout-btn:hover {
            opacity: 0.9;
        }
        
        .empty-cart {
            background: white;
            padding: 2rem;
            text-align: center;
            border-radius: 5px;
            color: #999;
        }
    </style>
</head>
<body>
    <nav>
        <h1>🛒 Shopping Cart</h1>
    </nav>

    <div class="container">
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($item = $result->fetch_assoc()) {
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo number_format($subtotal, 2); ?></td>
                            <td><a href="remove_from_cart.php?item_id=<?php echo $item['id']; ?>" class="remove-btn">Remove</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            
            <div class="summary">
                <h3 style="margin-bottom: 1rem;">Order Summary</h3>
                <p style="font-size: 1.5em; color: #667eea; margin-bottom: 1.5rem;"><strong>Total: $<?php echo number_format($total, 2); ?></strong></p>
                <button class="checkout-btn">Proceed to Checkout</button>
                <a href="index.php" style="display: block; text-align: center; margin-top: 1rem; color: #667eea;">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <h3>Your cart is empty</h3>
                <p>Start shopping to add items to your cart</p>
                <a href="index.php" style="color: #667eea; text-decoration: none; margin-top: 1rem; display: inline-block;">Go Shopping</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>
