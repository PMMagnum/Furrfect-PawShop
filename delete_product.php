<?php
include 'db.php';

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$productId]);

    header('Location: dashboard.php');
    exit();
}
?>
