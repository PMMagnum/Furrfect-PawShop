<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $barcode = $_POST['barcode'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $supplier = $_POST['supplier'];

    if ($barcode && $name && $price && $stock && $supplier) {
        $check = $pdo->prepare("SELECT * FROM products WHERE barcode = ?");
        $check->execute([$barcode]);

        if ($check->rowCount() === 0) {
            $stmt = $pdo->prepare("INSERT INTO products (barcode, name, price, stock, supplier) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$barcode, $name, $price, $stock, $supplier]);

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Product already exists']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'All fields required']);
    }
}
