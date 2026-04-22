<?php
include '../config.php';

if (!isLoggedIn() || (getUserRole() != 'seller' && getUserRole() != 'admin')) {
    redirect('../index.php');
}

$seller_id = $_SESSION['user_id'];

// Get seller's products
if (getUserRole() == 'admin') {
    $products_query = "SELECT * FROM products ORDER BY created_at DESC";
} else {
    $products_query = "SELECT * FROM products WHERE seller_id = $seller_id ORDER BY created_at DESC";
}
$products_result = $conn->query($products_query);

// Get sales stats
if (getUserRole() == 'admin') {
    $sales_query = "SELECT SUM(total_amount) as total_sales, COUNT(*) as total_orders FROM orders WHERE status = 'completed'";
} else {
    $sales_query = "SELECT SUM(oi.price * oi.quantity) as total_sales, COUNT(DISTINCT o.id) as total_orders 
                    FROM order_items oi 
                    JOIN orders o ON oi.order_id = o.id 
                    JOIN products p ON oi.product_id = p.id 
                    WHERE p.seller_id = $seller_id AND o.status = 'completed'";
}
$sales_result = $conn->query($sales_query);
$sales = $sales_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard - Social Commerce</title>
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
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
        }
        
        h2 {
            color: #667eea;
            margin-bottom: 2rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-value {
            font-size: 2.5em;
            color: #667eea;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #999;
            font-size: 0.95em;
        }
        
        .add-product-btn {
            padding: 0.8rem 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 2rem;
        }
        
        .products-table {
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
        
        .action-btn {
            padding: 0.5rem 1rem;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            margin-right: 0.5rem;
        }
        
        .delete-btn {
            background: #e74c3c;
        }
    </style>
</head>
<body>
    <nav>
        <h1>📊 Seller Dashboard</h1>
        <div>
            <a href="../index.php" style="margin-right: 1rem;">Store</a>
            <a href="../logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?php echo number_format($sales['total_sales'] ?? 0, 2); ?></div>
                <div class="stat-label">Total Sales</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $sales['total_orders'] ?? 0; ?></div>
                <div class="stat-label">Total Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $products_result->num_rows; ?></div>
                <div class="stat-label">Active Products</div>
            </div>
        </div>
        
        <button class="add-product-btn" onclick="window.location.href='add_product.php'">+ Add New Product</button>
        
        <table class="products-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Shares</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $products_result->data_seek(0);
                while ($product = $products_result->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td>$<?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo $product['stock']; ?></td>
                        <td><?php echo $product['social_share_count']; ?></td>
                        <td>
                            <button class="action-btn" onclick="window.location.href='edit_product.php?id=<?php echo $product['id']; ?>'">Edit</button>
                            <button class="action-btn delete-btn" onclick="confirmDelete(<?php echo $product['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
        function confirmDelete(productId) {
            if (confirm('Are you sure you want to delete this product?')) {
                window.location.href = 'delete_product.php?id=' + productId;
            }
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>
