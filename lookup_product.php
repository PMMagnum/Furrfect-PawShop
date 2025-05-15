<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['barcode'])) {
    $barcode = $_POST['barcode'];
    $stmt = $pdo->prepare("SELECT name, price FROM products WHERE barcode = ?");
    $stmt->execute([$barcode]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($product ?: []);
    exit();
}
?>
