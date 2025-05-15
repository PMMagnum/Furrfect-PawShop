<?php
session_start();

// Redirect to correct dashboard if already logged in
if (isset($_SESSION['user'])) {
  $role = $_SESSION['user']['role'];
  if ($role === 'admin') {
    header('Location: dashboard.php');
    exit();
  } else {
    header('Location: pos.php');
    exit();
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Furrfect Pawshop POS</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet" />

  <!-- FontAwesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      font-family: 'Fredoka', sans-serif;
      background: url('https://www.transparenttextures.com/patterns/paw-print.png') repeat, #fff8f0;
      background-size: 60px 60px;
    }

    .card {
      background-color: #fffbea;
      border: 2px solid #f7d9a4;
      border-radius: 20px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-10px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    h1, h2, h3 {
      color: #86592d;
    }

    .btn-primary {
      background-color: #f4a261;
      border: none;
      transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #e76f51;
      transform: scale(1.05);
    }

    .btn-success {
      background-color: #2a9d8f;
      border: none;
      transition: background-color 0.3s ease;
    }

    .btn-success:hover {
      background-color: #21867a;
      transform: scale(1.05);
    }

    .btn-secondary {
      background-color: #b5838d;
      border: none;
      transition: background-color 0.3s ease;
    }

    .btn-secondary:hover {
      background-color: #9b6f76;
      transform: scale(1.05);
    }

    .form-label {
      font-weight: bold;
      color: #5a3e2b;
    }

    .form-control {
      transition: border-color 0.3s ease;
    }

    .form-control:focus {
      border-color: #f4a261;
      box-shadow: 0 0 5px rgba(244, 162, 97, 0.6);
    }

    .form-control:hover {
      border-color: #f4a261;
    }

    .text-center::before {
      content: "🐾 ";
    }

    .text-center::after {
      content: " 🐾";
    }

    .alert {
      background-color: #ffe3e3;
      border: 1px solid #f5c2c7;
      color: #a94442;
      font-weight: bold;
    }

    .logo-title {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      margin-bottom: 0rem;
    }

    .logo-title img {
      height: 130px;
      width: auto;
    }
  </style>
</head>
<body>
  <!-- Login Container -->
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card p-4" style="max-width: 400px; width: 100%">
      
      <!-- Logo + Title -->
      <div class="logo-title">
        <img src="Logoo.png" alt="Furrfect Pawshop Logo">
      </div>

      <!-- Error message -->
      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger text-center">
          <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
      <?php endif; ?>

      <!-- Login Form -->
      <form action="auth.php" method="POST">
        <div class="mb-3">
          <label for="username" class="form-label"><i class="fa fa-user"></i> Username</label>
          <input type="text" class="form-control" id="username" name="username" placeholder="Username" required />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label"><i class="fa fa-lock"></i> Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Password" required />
        </div>
        <div class="mb-3">
          <label for="role" class="form-label"><i class="fa fa-user-tag"></i> Role</label>
          <select class="form-select" name="role">
            <option value="admin">Admin/Owner</option>
            <option value="cashier">Cashier</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
