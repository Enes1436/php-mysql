<?php
include 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Social Commerce</title>
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
            display: flex;
            justify-content: space-between;
        }
        
        nav a {
            color: white;
            text-decoration: none;
        }
        
        .container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 2rem;
        }
        
        h2 {
            color: #667eea;
            margin-bottom: 2rem;
        }
        
        .order-card {
            background: white;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 1rem;
        }
        
        .order-id {
            font-weight: bold;
            color: #667eea;
        }
        
        .order-status {
            padding: 0.5rem 1rem;
            border-radius: 3px;
            font-size: 0.9em;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        
        .order-details {
            display: flex;
            justify-content: space-between;
            font-size: 0.95em;
        }
        
        .empty-orders {
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
        <h1>📦 Your Orders</h1>
        <a href="index.php">← Back to Store</a>
    </nav>

    <div class="container">
        <?php if ($result->num_rows > 0): ?>
            <?php
            while ($order = $result->fetch_assoc()) {
                $status_class = 'status-' . $order['status'];
            ?>
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <div class="order-id">Order #<?php echo $order['id']; ?></div>
                            <div style="font-size: 0.85em; color: #999;"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></div>
                        </div>
                        <div class="order-status <?php echo $status_class; ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </div>
                    </div>
                    
                    <div class="order-details">
                        <div>
                            <strong>Amount:</strong> $<?php echo number_format($order['total_amount'], 2); ?>
                        </div>
                        <div>
                            <strong>Payment:</strong> <?php echo ucfirst($order['payment_method'] ?? 'Pending'); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php else: ?>
            <div class="empty-orders">
                <h3>No orders yet</h3>
                <p>Start shopping to place your first order</p>
                <a href="index.php" style="color: #667eea; text-decoration: none; margin-top: 1rem; display: inline-block;">Go Shopping</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>
