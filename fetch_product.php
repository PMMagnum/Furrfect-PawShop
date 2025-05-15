<?php
include 'db.php';

if (isset($_GET['barcode'])) {
    $barcode = $_GET['barcode'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE barcode = ?");
    $stmt->execute([$barcode]);
    $product = $stmt->fetch();

    if ($product) {
        echo json_encode(['success' => true, 'product' => $product]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
    }
}
?>
