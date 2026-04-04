<?php
session_start();
include "config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if ($username && $email && $password) {

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        try {
            $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
            $stmt = $conn->prepare($sql);

            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $hashed
            ]);

            $message = "Registration successful!";

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = "Email already exists!";
            } else {
                $message = "Error: " . $e->getMessage();
            }
        }

    } else {
        $message = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card p-4 shadow" style="width:400px;">
    <h3 class="text-center">Sign Up</h3>

    <?php if($message): ?>
      <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST">
      <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
      <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
      <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

      <button class="btn btn-primary w-100">Register</button>
    </form>

    <div class="text-center mt-3">
      <a href="login.php">Already have an account?</a>
    </div>
  </div>
</div>

</body>
</html>