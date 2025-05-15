<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['items']) || !is_array($data['items']) || empty($data['items'])) {
        echo json_encode(['success' => false, 'message' => 'No items in cart']);
        exit();
    }
    
    $userId = $_SESSION['user']['id'];
    $total = $data['total'];
    
    try {
        // Start transaction
        $pdo->beginTransaction();
        
        // Create sale record
        $stmt = $pdo->prepare("INSERT INTO sales (user_id, total, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$userId, $total]);
        $saleId = $pdo->lastInsertId();
        
        // Add sale items
        $itemStmt = $pdo->prepare("INSERT INTO sale_items (sale_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        
        // Update inventory
        $updateStmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        
        foreach ($data['items'] as $item) {
            $itemStmt->execute([$saleId, $item['id'], $item['quantity'], $item['price']]);
            $updateStmt->execute([$item['quantity'], $item['id']]);
        }
        
        // Commit transaction
        $pdo->commit();
        
        echo json_encode(['success' => true, 'sale_id' => $saleId]);
    } catch (Exception $e) {
        // Rollback on error
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
