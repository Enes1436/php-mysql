<?php
include 'config.php';

// Create users table
$users_table = "CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(255),
    role ENUM('customer', 'seller', 'admin') DEFAULT 'customer',
    profile_picture VARCHAR(255),
    bio TEXT,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Create products table
$products_table = "CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    seller_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    category VARCHAR(100),
    image_url VARCHAR(255),
    stock INT DEFAULT 0,
    social_share_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(id)
)";

// Create cart table
$cart_table = "CREATE TABLE IF NOT EXISTS cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
)";

// Create orders table
$orders_table = "CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    payment_method VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

// Create order items table
$order_items_table = "CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT,
    price DECIMAL(10, 2),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
)";

// Create social shares table
$shares_table = "CREATE TABLE IF NOT EXISTS social_shares (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    platform VARCHAR(50),
    user_id INT,
    shared_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

$tables = [
    $users_table,
    $products_table,
    $cart_table,
    $orders_table,
    $order_items_table,
    $shares_table
];

foreach ($tables as $table) {
    if ($conn->query($table) === TRUE) {
        echo "Table created/verified successfully<br>";
    } else {
        echo "Error: " . $conn->error . "<br>";
    }
}

// Insert sample data
$check_users = $conn->query("SELECT COUNT(*) as count FROM users");
$user_count = $check_users->fetch_assoc()['count'];

if ($user_count == 0) {
    echo "Inserting sample data...<br>";
    
    // Insert sample users
    $admin_pass = password_hash('admin123', PASSWORD_DEFAULT);
    $seller_pass = password_hash('seller123', PASSWORD_DEFAULT);
    $customer_pass = password_hash('customer123', PASSWORD_DEFAULT);
    
    $users = [
        "INSERT INTO users (username, email, password, full_name, role) VALUES ('admin', 'admin@social-commerce.com', '$admin_pass', 'Admin User', 'admin')",
        "INSERT INTO users (username, email, password, full_name, role, bio) VALUES ('seller1', 'seller@social-commerce.com', '$seller_pass', 'Professional Seller', 'seller', 'Quality products at best prices')",
        "INSERT INTO users (username, email, password, full_name, role) VALUES ('customer1', 'customer@social-commerce.com', '$customer_pass', 'Happy Customer', 'customer')"
    ];
    
    foreach ($users as $user) {
        $conn->query($user);
    }
    
    // Insert sample products
    $products = [
        "INSERT INTO products (seller_id, name, description, price, category, stock) VALUES (2, 'Premium Headphones', 'High-quality wireless headphones with noise cancellation', 99.99, 'Electronics', 50)",
        "INSERT INTO products (seller_id, name, description, price, category, stock) VALUES (2, 'Smartphone Case', 'Durable protective case for all devices', 19.99, 'Accessories', 100)",
        "INSERT INTO products (seller_id, name, description, price, category, stock) VALUES (2, 'USB-C Cable', 'Fast charging and data transfer cable', 12.99, 'Cables', 200)",
        "INSERT INTO products (seller_id, name, description, price, category, stock) VALUES (2, 'Portable Power Bank', '20000mAh power bank with fast charging', 49.99, 'Electronics', 75)"
    ];
    
    foreach ($products as $product) {
        $conn->query($product);
    }
    
    echo "Sample data inserted successfully<br>";
}

echo "<br><strong>Database setup complete!</strong><br>";
echo '<a href="index.php">Go to Social Commerce App</a><br><br>';
echo '<p><strong>Test Credentials:</strong></p>';
echo '<p>Admin: admin / admin123</p>';
echo '<p>Seller: seller1 / seller123</p>';
echo '<p>Customer: customer1 / customer123</p>';

$conn->close();
?>
