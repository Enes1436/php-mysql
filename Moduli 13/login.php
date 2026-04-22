<?php
session_start();
include "config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':email' => $email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            header("Location: dashboard.php");
            exit();

        } else {
            $message = "Wrong password!";
        }

    } else {
        $message = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card p-4 shadow" style="width:400px;">
    <h3 class="text-center">Login</h3>

    <?php if($message): ?>
      <div class="alert alert-danger"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST">
      <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
      <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

      <button class="btn btn-success w-100">Login</button>
    </form>

    <div class="text-center mt-3">
      <a href="register.php">Create account</a>
    </div>
  </div>
</div>

</body>
</html>