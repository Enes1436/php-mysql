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
    <form method="POST">
      <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
      <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

      <button class="btn btn-success w-100">Login</button>
    </form>

    <div class="text-center mt-3">
      <a href="index.php">Create account</a>
    </div>
  </div>
</div>

</body>
</html>