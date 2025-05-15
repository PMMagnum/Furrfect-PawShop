<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];

    $stmt = $pdo->prepare("INSERT INTO products (name, price) VALUES (?, ?)");
    $stmt->execute([$name, $price]);

    header('Location: pos.php'); // Redirect back to POS page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Add Product - Furrfect Pawshop</title>
  
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

    .text-center::before {
      content: "🐾 ";
    }

    .text-center::after {
      content: " 🐾";
    }
  </style>
</head>
<body>
  <!-- Admin Panel -->
  <div id="admin-panel" class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card p-4" style="max-width: 400px; width: 100%">
      <h1 class="text-center mb-4">Add a New Product</h1>
      <form action="add_product.php" method="POST">
        <div class="mb-3">
          <label for="name" class="form-label">Product Name</label>
          <input type="text" class="form-control" id="name" name="name" required />
        </div>
        <div class="mb-3">
          <label for="price" class="form-label">Price</label>
          <input type="number" class="form-control" id="price" name="price" required />
        </div>
        <button type="submit" class="btn btn-primary w-100">Add Product</button>
      </form>
      <br>
      <a href="pos.php" class="btn btn-secondary w-100">Back to POS</a>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
