<?php
include 'config.php';

// Get all products
$query = "SELECT p.*, u.full_name, u.username FROM products p JOIN users u ON p.seller_id = u.id ORDER BY p.created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Commerce - Sell & Buy Online</title>
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
            align-items: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        nav h1 {
            font-size: 1.5em;
        }
        
        nav .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }
        
        nav a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s;
        }
        
        nav a:hover {
            opacity: 0.8;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .header {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .header h2 {
            color: #667eea;
            margin-bottom: 1rem;
        }
        
        .search-box {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1rem;
        }
        
        .search-box input {
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 300px;
        }
        
        .search-box button {
            padding: 0.8rem 1.5rem;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .search-box button:hover {
            background: #764ba2;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
        }
        
        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3em;
            color: white;
        }
        
        .product-info {
            padding: 1.5rem;
        }
        
        .product-name {
            font-size: 1.1em;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #333;
        }
        
        .seller-info {
            font-size: 0.85em;
            color: #999;
            margin-bottom: 0.5rem;
        }
        
        .product-description {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 1rem;
            line-height: 1.4;
        }
        
        .product-price {
            font-size: 1.5em;
            color: #667eea;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        
        .product-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn {
            flex: 1;
            padding: 0.7rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
            transition: background 0.3s;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #764ba2;
        }
        
        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }
        
        .btn-secondary:hover {
            background: #e0e0e0;
        }
        
        .social-buttons {
            display: flex;
            gap: 0.3rem;
            margin-top: 0.5rem;
        }
        
        .social-btn {
            flex: 1;
            padding: 0.5rem;
            font-size: 0.8em;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: opacity 0.3s;
        }
        
        .facebook {
            background: #3b5998;
            color: white;
        }
        
        .twitter {
            background: #1da1f2;
            color: white;
        }
        
        .whatsapp {
            background: #25d366;
            color: white;
        }
        
        .social-btn:hover {
            opacity: 0.8;
        }
        
        .no-products {
            text-align: center;
            padding: 2rem;
            color: #999;
        }
    </style>
</head>
<body>
    <nav>
        <h1>🛍️ Social Commerce</h1>
        <div class="nav-links">
            <?php if (isLoggedIn()): ?>
                <a href="cart.php">🛒 Cart</a>
                <a href="orders.php">📦 Orders</a>
                <?php if (getUserRole() == 'seller' || getUserRole() == 'admin'): ?>
                    <a href="admin/dashboard.php">📊 Dashboard</a>
                <?php endif; ?>
                <a href="profile.php">👤 Profile</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <div class="header">
            <h2>Welcome to Social Commerce</h2>
            <p>Discover amazing products and share with your friends!</p>
            <div class="search-box">
                <form method="GET" action="" style="display: flex; gap: 1rem; width: 100%; justify-content: center;">
                    <input type="text" name="search" placeholder="Search products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit">Search</button>
                </form>
            </div>
        </div>

        <div class="products-grid">
            <?php
            if ($result->num_rows > 0) {
                while ($product = $result->fetch_assoc()) {
                    echo '<div class="product-card">';
                    echo '<div class="product-image">📦</div>';
                    echo '<div class="product-info">';
                    echo '<div class="product-name">' . htmlspecialchars($product['name']) . '</div>';
                    echo '<div class="seller-info">By: ' . htmlspecialchars($product['full_name']) . '</div>';
                    echo '<div class="product-description">' . substr(htmlspecialchars($product['description']), 0, 60) . '...</div>';
                    echo '<div class="product-price">$' . number_format($product['price'], 2) . '</div>';
                    
                    echo '<div class="product-actions">';
                    echo '<button class="btn btn-primary" onclick="addToCart(' . $product['id'] . ')">Add to Cart</button>';
                    echo '</div>';
                    
                    echo '<div class="social-buttons">';
                    echo '<button class="social-btn facebook" onclick="shareOnFacebook(' . $product['id'] . ')">f</button>';
                    echo '<button class="social-btn twitter" onclick="shareOnTwitter(' . $product['id'] . ')">𝕏</button>';
                    echo '<button class="social-btn whatsapp" onclick="shareOnWhatsApp(' . $product['id'] . ')">W</button>';
                    echo '</div>';
                    
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div class="no-products" style="grid-column: 1/-1;">No products found</div>';
            }
            ?>
        </div>
    </div>

    <script>
        function addToCart(productId) {
            <?php if (!isLoggedIn()): ?>
                alert('Please login first');
                window.location.href = 'login.php';
                return;
            <?php endif; ?>
            
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'product_id=' + productId
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
            });
        }

        function shareOnFacebook(productId) {
            alert('Share on Facebook feature - Integrate Facebook Share Dialog');
            recordShare(productId, 'facebook');
        }

        function shareOnTwitter(productId) {
            alert('Share on Twitter feature - Integrate Twitter Share Dialog');
            recordShare(productId, 'twitter');
        }

        function shareOnWhatsApp(productId) {
            alert('Share on WhatsApp feature - Integrate WhatsApp Share Dialog');
            recordShare(productId, 'whatsapp');
        }

        function recordShare(productId, platform) {
            fetch('record_share.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'product_id=' + productId + '&platform=' + platform
            });
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>
