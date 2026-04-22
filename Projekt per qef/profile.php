<?php
include 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($query);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Social Commerce</title>
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
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
        }
        
        .profile-card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h2 {
            color: #667eea;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .profile-info {
            margin-bottom: 1.5rem;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 1rem;
        }
        
        .info-label {
            font-weight: bold;
            min-width: 120px;
            color: #667eea;
        }
        
        .info-value {
            color: #333;
        }
        
        .role-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #667eea;
            color: white;
            border-radius: 20px;
            font-size: 0.9em;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <nav>
        <h1>👤 Profile</h1>
        <a href="index.php">← Back to Store</a>
    </nav>

    <div class="container">
        <div class="profile-card">
            <h2><?php echo htmlspecialchars($user['full_name']); ?></h2>
            
            <div class="profile-info">
                <div class="info-row">
                    <div class="info-label">Username:</div>
                    <div class="info-value"><?php echo htmlspecialchars($user['username']); ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value"><?php echo htmlspecialchars($user['email']); ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Phone:</div>
                    <div class="info-value"><?php echo htmlspecialchars($user['phone'] ?? 'Not set'); ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Account Type:</div>
                    <div>
                        <span class="role-badge"><?php echo ucfirst($user['role']); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
