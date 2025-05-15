<?php
// Include database connection
include 'db_connection.php'; 

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['cart']) || !isset($data['total']) || !isset($data['cashHanded']) || !isset($data['receiptNumber'])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input"]);
    exit;
}

$receiptNumber = $data['receiptNumber'];
$total = $data['total'];
$cash = $data['cashHanded'];
$date = $data['date'];
$time = $data['time'];
$cart = $data['cart'];
$userId = $data['userId'];  // Cashier's user ID

try {
    // Start transaction
    $conn->begin_transaction();

    // Insert into sales
    $stmt = $conn->prepare("INSERT INTO sales (receipt_number, total, cash, date, time, user_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sddssi", $receiptNumber, $total, $cash, $date, $time, $userId);
    $stmt->execute();
    $sale_id = $stmt->insert_id; // Get the sale ID

    // Insert each item in the sale
    $stmt_item = $conn->prepare("INSERT INTO sale_items (sale_id, name, qty, price, total) VALUES (?, ?, ?, ?, ?)");

    foreach ($cart as $item) {
        $name = $item['name'];
        $qty = $item['qty'];
        $price = $item['price'];
        $itemTotal = $item['total'];
        $stmt_item->bind_param("isidd", $sale_id, $name, $qty, $price, $itemTotal);
        $stmt_item->execute();
    }

    $conn->commit();
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(["error" => "Database error", "message" => $e->getMessage()]);
}
?>
